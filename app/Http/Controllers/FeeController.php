<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('schoolClass')
            ->when(request('academic_year'), fn ($query, $year) => $query->where('academic_year', $year))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('fees.index', compact('fees'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('fees.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $fee = Fee::create($this->validated($request));
        Log::info('Fee structure created.', ['fee_id' => $fee->id, 'user_id' => auth()->id()]);

        return redirect()->route('fees.index')->with('success', 'Fee structure saved successfully.');
    }

    public function show(string $id)
    {
        return redirect()->route('fees.edit', $id);
    }

    public function edit(string $id)
    {
        $fee = Fee::findOrFail($id);
        $classes = SchoolClass::orderBy('class_name')->get();

        return view('fees.edit', compact('fee', 'classes'));
    }

    public function update(Request $request, string $id)
    {
        $fee = Fee::findOrFail($id);
        $fee->update($this->validated($request, $fee->id));
        Log::info('Fee structure updated.', ['fee_id' => $fee->id, 'user_id' => auth()->id()]);

        return redirect()->route('fees.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(string $id)
    {
        $fee = Fee::findOrFail($id);
        $feeId = $fee->id;
        $fee->delete();
        Log::warning('Fee structure deleted.', ['fee_id' => $feeId, 'user_id' => auth()->id()]);

        return redirect()->route('fees.index')->with('success', 'Fee structure deleted successfully.');
    }

    private function validated(Request $request, ?int $feeId = null): array
    {
        $data = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'academic_year' => ['required', 'string', 'max:9'],
        ]);

        $exists = Fee::where('class_id', $data['class_id'])
            ->where('academic_year', $data['academic_year'])
            ->when($feeId, fn ($query) => $query->where('id', '!=', $feeId))
            ->exists();

        if ($exists) {
            back()->withInput()->withErrors(['academic_year' => 'A fee structure already exists for this class and academic year.'])->throwResponse();
        }

        return $data;
    }
}
