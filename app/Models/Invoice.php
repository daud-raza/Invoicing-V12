<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 
        'invoice_number', 
        'email', 
        'invoice_month', 
        'status',
    ];


    protected $casts = [
        'invoice_month' => 'date:Y-m',
    ];


    public function client() { 
        return $this->belongsTo(Client::class); 
    }
    public function services() { 
        return $this->hasMany(InvoiceService::class); 
    }
    public function refunds() { 
        return $this->hasMany(Refund::class); 
    }


    public function getTotalAttribute(): float
    {
        return (float) $this->services()->sum('subtotal');
    }


    public function getRefundedAmountAttribute(): float
    {
        return (float) $this->refunds()->sum('amount');
    }
}
