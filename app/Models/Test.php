<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Test extends Model
{
    use HasFactory;

    const TABLE                               = 'tests';
    const FIELD_ID                            = 'id';
    const FIELD_NAME                          = 'name';
    const FIELD_DESCRIPTION                   = 'description';
    const FIELD_HEALTH_PROFESSIONAL_CLINIC_ID = 'health_professional_clinic_id';
    const FIELD_REFERRING_DOCTOR_ID           = 'referring_doctor_id';
    const FIELD_CREATED_AT                    = 'created_at';
    const FIELD_UPDATED_AT                    = 'updated_at';

    const RELATION_HEALTH_PROFESSIONAL_CLINICS = 'healthProfessionalClinics';

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function healthProfessionalClinics(): BelongsTo
    {
        return $this->belongsTo(HealthProfessionalClinic::class, self::FIELD_HEALTH_PROFESSIONAL_CLINIC_ID, HealthProfessionalClinic::FIELD_ID);
    }
}
