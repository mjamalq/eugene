<?php

namespace App\Transformers;

use App\Models\Clinic;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalClinic;
use App\Models\Test;
use Illuminate\Support\Collection;

class HealthProfessionalsTransformer
{
    /**
     * @param Collection $healthProfessionals
     * @return array
     */
    public function transformAll(Collection $healthProfessionals): array
    {
        return $healthProfessionals->map(function (HealthProfessional $healthProfessional) {
             return $this->transform($healthProfessional);
        })->toArray();
    }

    /**
     * @param HealthProfessional|null $healthProfessional
     * @return array
     */
    public function transform(?HealthProfessional $healthProfessional): array
    {
        if(!$healthProfessional) return [];

        return [
            'id'             => $healthProfessional->{HealthProfessional::FIELD_ID},
            'name'           => $healthProfessional->{HealthProfessional::FIELD_NAME},
            'specialty'      => $healthProfessional->{HealthProfessional::FIELD_SPECIALITY},
            'clinic_name'    => $this->fetchClinicsData($healthProfessional, Clinic::FIELD_NAME),
            'clinic_address' => $this->fetchClinicsData($healthProfessional, Clinic::FIELD_ADDRESS),
            'created_at'     => $healthProfessional->{HealthProfessional::FIELD_CREATED_AT},
            'updated_at'     => $healthProfessional->{HealthProfessional::FIELD_UPDATED_AT},
            'tests_count'    => $this->testsCount($healthProfessional),
            'duplication_found'    => $this->checkTheHealthProfessionalOrClinicHasDuplication($healthProfessional),
        ];
    }

    /**
     * @param HealthProfessional $healthProfessional
     * @param string             $attribute
     * @return string
     */
    protected function fetchClinicsData(HealthProfessional $healthProfessional, string $attribute): string
    {
        $clinics = $healthProfessional->clinics()->pluck(HealthProfessionalClinic::FIELD_CLINIC_ID);

        $response = Clinic::query()
            ->whereIn(Clinic::FIELD_ID, $clinics)
            ->whereNotNull($attribute)
            ->pluck($attribute)
            ->toArray();

        return !empty($response)
            ? implode(', ', $response)
            : '';
    }

    /**
     * @param HealthProfessional $healthProfessional
     * @return int
     */
    protected function testsCount(HealthProfessional $healthProfessional): int
    {
        return Test::query()
            ->WhereHas(Test::RELATION_HEALTH_PROFESSIONAL_CLINICS, function ($query) use ($healthProfessional) {
                $query->where(HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID, $healthProfessional->{HealthProfessional::FIELD_ID});
            })->count();
    }

    /**
     * @param HealthProfessional $healthProfessional
     * @return bool
     */
    protected function checkTheHealthProfessionalOrClinicHasDuplication(HealthProfessional $healthProfessional): bool
    {
        $duplication = HealthProfessionalClinic::query()
            ->where(HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID, $healthProfessional->{HealthProfessional::FIELD_ID})
            ->groupBy(HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID, HealthProfessionalClinic::FIELD_CLINIC_ID)
            ->get();

        return $duplication->count() > 1;
    }
}
