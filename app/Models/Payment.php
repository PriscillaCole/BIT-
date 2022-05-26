<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;
    

    protected $fillable = [ 
        'amount',
        'registration_id',
        'receipt_id',
        'academic_year',
        'semster',
        'currency',
        'studentID',

    ];

    public function registration(){
        return $this->belongsTo(Registration::class);
    }

    public function accountant(){
        return $this->belongsTo(Accountant::class);
    }
    

}
