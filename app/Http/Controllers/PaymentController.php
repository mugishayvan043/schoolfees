<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student.schoolClass', 'recorder'])
            ->when(request('search'), function ($query, $search) {
                $query->where('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($query) use ($search) {
                        $query->where('student_code', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::with('schoolClass')->orderBy('first_name')->get();
        $receiptNumber = Payment::nextReceiptNumber();
        $paymentToken = (string) Str::uuid();
        session(['payment_form_token' => $paymentToken]);

        return view('payments.create', compact('students', 'receiptNumber', 'paymentToken'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'amount_paid' => ['required', 'numeric', 'gt:0'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_method' => ['required', 'string', 'max:50'],
            'payment_token' => ['required', 'string'],
        ]);

        if (! hash_equals((string) session('payment_form_token'), $data['payment_token'])) {
            Log::warning('Duplicate or expired payment submission blocked.', [
                'student_id' => $data['student_id'],
                'user_id' => auth()->id(),
            ]);

            return back()->withInput()->withErrors(['amount_paid' => 'This payment form has already been submitted. Please open a new payment form.']);
        }

        try {
            $payment = DB::transaction(function () use ($data) {
                $student = Student::with(['schoolClass.fees', 'payments'])->lockForUpdate()->findOrFail($data['student_id']);
                $totalFee = $student->totalFee();

                if ($totalFee <= 0) {
                    back()->withInput()->withErrors(['student_id' => 'This student does not have a fee structure for the current academic year.'])->throwResponse();
                }

                if ($data['amount_paid'] > $student->balance()) {
                    back()->withInput()->withErrors(['amount_paid' => 'Payment cannot exceed the remaining balance.'])->throwResponse();
                }

                return Payment::create([
                    'student_id' => $data['student_id'],
                    'amount_paid' => $data['amount_paid'],
                    'payment_date' => $data['payment_date'],
                    'payment_method' => $data['payment_method'],
                    'receipt_number' => Payment::nextReceiptNumber(),
                    'recorded_by' => auth()->id(),
                ]);
            });
        } catch (\Throwable $exception) {
            Log::error('Payment recording failed.', [
                'error' => $exception->getMessage(),
                'student_id' => $data['student_id'],
                'user_id' => auth()->id(),
            ]);

            throw $exception;
        }

        session()->forget('payment_form_token');
        Log::info('Payment recorded.', [
            'payment_id' => $payment->id,
            'receipt_number' => $payment->receipt_number,
            'student_id' => $payment->student_id,
            'amount' => $payment->amount_paid,
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('payments.receipt', $payment)->with('success', 'Payment recorded and receipt generated.');
    }

    public function show(string $id)
    {
        return $this->receipt($id);
    }

    public function receipt(string $id)
    {
        $payment = Payment::with(['student.schoolClass.fees', 'recorder'])->findOrFail($id);

        return view('payments.receipt', compact('payment'));
    }

    public function print(string $id)
    {
        return $this->receipt($id);
    }

    public function edit(string $id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::with('schoolClass')->orderBy('first_name')->get();

        return view('payments.edit', compact('payment', 'students'));
    }

    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'amount_paid' => ['required', 'numeric', 'gt:0'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        $student = Student::with(['schoolClass.fees', 'payments'])->findOrFail($data['student_id']);
        $availableBalance = $student->totalFee() - ($student->payments()->where('id', '!=', $payment->id)->sum('amount_paid'));

        if ($student->totalFee() <= 0) {
            return back()->withInput()->withErrors(['student_id' => 'This student does not have a fee structure for the current academic year.']);
        }

        if ($data['amount_paid'] > $availableBalance) {
            return back()->withInput()->withErrors(['amount_paid' => 'Payment cannot exceed the remaining balance.']);
        }

        $payment->update($data);
        Log::info('Payment updated.', ['payment_id' => $payment->id, 'user_id' => auth()->id()]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $paymentId = $payment->id;
        $payment->delete();
        Log::warning('Payment deleted.', ['payment_id' => $paymentId, 'user_id' => auth()->id()]);

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
