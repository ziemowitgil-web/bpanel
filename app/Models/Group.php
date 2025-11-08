<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_class',
    ];

    /**
     * Relacja do beneficjentów (many-to-many)
     */
    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class);
    }

    /**
     * Relacja do terminów zajęć (one-to-many)
     */
    public function schedules()
    {
        return $this->hasMany(GroupSchedule::class);
    }
}
