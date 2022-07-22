<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSummary extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'registration_id',
        'finance_id',
        'semester',
        'date_of_first_payment',
        'payment_status'
        
    ];
}
