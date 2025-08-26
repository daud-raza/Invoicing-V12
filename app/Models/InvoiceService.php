<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceService extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id', 
        'service_name', 
        'quantity', 
        'cost', 
        'subtotal'
    ];
    public $timestamps = false;


    public function invoice() { 
        return $this->belongsTo(Invoice::class); 
    }
}
