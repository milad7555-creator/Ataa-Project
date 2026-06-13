<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    protected $fillable = [
        'full_name',
        'address',
        'email',
        'phone',
        'personal_picture',
    ];

    public function requests()
    {
        return $this->hasMany(RequestModel::class);
    }
}