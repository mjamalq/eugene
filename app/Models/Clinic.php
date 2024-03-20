<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int    $id
 * @property string $name
 * @property string $address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read HealthProfessionalClinic $healthProfessionals
 */
class Clinic extends Model
{
    const TABLE             = 'clinics';
    const FIELD_ID          = 'id';
    const FIELD_NAME        = 'name';
    const FIELD_ADDRESS     = 'address';
    const FIELD_CREATED_AT  = 'created_at';
    const FIELD_UPDATED_AT  = 'updated_at';

    const RELATION_HEALTH_PROFESSIONALS = 'healthProfessionals';

    protected $table = self::TABLE;

    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function healthProfessionals(): HasMany
    {
        return $this->hasMany(HealthProfessionalClinic::class, HealthProfessionalClinic::FIELD_CLINIC_ID, self::FIELD_ID);
    }
}
