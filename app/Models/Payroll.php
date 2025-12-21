<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'month',
        'year',
        'basic_salary',
        'hra_allowance',
        'conveyance',
        'medical_allowance',
        'bonus',
        'other_allowance',
        'pf_deduction',
        'professional_tax',
        'tds',
        'loans_deduction',
        'other_deduction',
        'total_allowance',
        'total_deduction',
        'net_salary',
        'payment_date',
        'payment_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'basic_salary' => 'decimal:2',
        'hra_allowance' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'bonus' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'pf_deduction' => 'decimal:2',
        'professional_tax' => 'decimal:2',
        'tds' => 'decimal:2',
        'loans_deduction' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getMonthYearAttribute()
    {
        return date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    public function calculateTotals()
    {
        $this->total_allowance = $this->hra_allowance + $this->conveyance + 
            $this->medical_allowance + $this->bonus + $this->other_allowance;
        
        $this->total_deduction = $this->pf_deduction + $this->professional_tax + 
            $this->tds + $this->loans_deduction + $this->other_deduction;
        
        $this->net_salary = $this->basic_salary + $this->total_allowance - $this->total_deduction;
    }
}


