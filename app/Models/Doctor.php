<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    const FIELD_ID              = 'id';
    const FIELD_NAME            = 'name';
    const FIELD_CLINIC_NAME     = 'clinic_name';
    const FIELD_CLINIC_ADDRESS  = 'clinic_address';
    const FIELD_SPECIALITY      = 'specialty';
    const FIELD_CREATED_AT      = 'created_at';
    const FIELD_UPDATED_AT      = 'updated_at';

    protected $guarded = [];

    public function tests()
    {
        return $this->hasMany(Test::class, Test::FIELD_REFERRING_DOCTOR_ID);
    }
}
