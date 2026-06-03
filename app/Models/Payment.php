<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount_paid',
        'payment_date',
        'receipt_number',
        'payment_method',
        'recorded_by',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by')->withDefault([
            'name' => 'Deleted User',
            'email' => 'deleted@example.com',
            'role' => 'accountant',
        ]);
    }

    public static function nextReceiptNumber(): string
    {
        $year = now()->year;
        $last = static::where('receipt_number', 'like', "REC-{$year}-%")
            ->orderByDesc('id')
            ->value('receipt_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return sprintf('REC-%s-%04d', $year, $next);
    }
}
