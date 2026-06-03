<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'gender',
        'class_id',
        'phone',
        'address',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function totalFee(?string $academicYear = null): float
    {
        $year = $academicYear ?: config('fees.current_academic_year');

        return (float) optional($this->schoolClass?->fees()->where('academic_year', $year)->first())->amount;
    }

    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount_paid');
    }

    public function balance(?string $academicYear = null): float
    {
        return max($this->totalFee($academicYear) - $this->totalPaid(), 0);
    }
}
