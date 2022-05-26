<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Lecturer extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $fillable = [
        'user_id',
        'EmployID',
        'district',
        'nationality',
        'country',
        'Town',
        'postal',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function registration(){
        return $this->hasMany(Registration::class);
    }
    public function getActivitylogOptions():LogOptions{
        return LogOptions::defaults();
    }

}
