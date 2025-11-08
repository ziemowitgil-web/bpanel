<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'class_link',
        'active',
        'slug',
        'iinstructor_id',
    ];

    // Relacja do użytkownika
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function instructorName()
    {
        return $this->instructor ? $this->instructor->first_name . ' ' . $this->instructor->last_name : '-';
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }
}
