<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 * @property string $specialty
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read HealthProfessionalClinic $clinics
 */
class HealthProfessional extends Model
{
    const TABLE             = 'health_professionals';
    const FIELD_ID          = 'id';
    const FIELD_NAME        = 'name';
    const FIELD_SPECIALITY  = 'specialty';
    const FIELD_CREATED_AT  = 'created_at';
    const FIELD_UPDATED_AT  = 'updated_at';

    const RELATION_CLINICS = 'clinics';

    protected $table = self::TABLE;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function clinics(): HasMany
    {
        return $this->hasMany(HealthProfessionalClinic::class, HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID, self::FIELD_ID);
    }
}
