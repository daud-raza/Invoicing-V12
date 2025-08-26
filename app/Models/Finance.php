<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;
    protected $fillable = [
        'month',
        'additional_revenue',
        'staffing_expense',
        'technology_expense',
        'misc_expense',
        'total_profit'
    ];


    protected $casts = [
        'month' => 'date:Y-m',
    ];
}
