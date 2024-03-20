<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $health_professional_id
 * @property int    $clinic_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read HealthProfessional $healthProfessional
 * @property-read Clinic             $clinic
 */
class HealthProfessionalClinic extends Model
{
    const TABLE                        = 'health_professionals_clinics';
    const FIELD_ID                     = 'id';
    const FIELD_HEALTH_PROFESSIONAL_ID = 'health_professional_id';
    const FIELD_CLINIC_ID              = 'clinic_id';
    const FIELD_CREATED_AT             = 'created_at';
    const FIELD_UPDATED_AT             = 'updated_at';

    const RELATION_HEALTH_PROFESSIONAL = 'healthProfessional';
    const RELATION_CLINIC              = 'clinic';

    protected $table = self::TABLE;

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function healthProfessional(): BelongsTo
    {
        return $this->belongsTo(HealthProfessional::class, self::FIELD_HEALTH_PROFESSIONAL_ID, HealthProfessional::FIELD_ID);
    }

    /**
     * @return BelongsTo
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, self::FIELD_CLINIC_ID, Clinic::FIELD_ID);
    }
}
