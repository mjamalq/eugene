<?php

namespace App\Transformers;

use App\Models\HealthProfessional;
use App\Models\HealthProfessionalClinic;
use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TestsTransformer
{
    /**
     * @param HealthProfessionalsTransformer $healthProfessionalsTransformer
     */
    public function __construct(
        protected HealthProfessionalsTransformer $healthProfessionalsTransformer
    ){}

    /**
     * @param Collection $tests
     * @return array
     */
    public function transformAll(Collection $tests): array
    {
        return $tests->map(function (Test $test) {
            return $this->transform($test);
        })->toArray();
    }

    /**
     * @param Test $test
     * @return array
     */
    public function transform(Test $test): array
    {
        return [
            'id'                  => $test->{Test::FIELD_ID},
            'name'                => $test->{Test::FIELD_NAME},
            'description'         => $test->{Test::FIELD_DESCRIPTION},
            'health_professional' => $this->healthProfessionalsTransformer->transform($this->fetchHealthProfessional($test)),
            'created_at'          => $test->{Test::FIELD_CREATED_AT},
            'updated_at'          => $test->{Test::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @param Test $test
     * @return HealthProfessional|Model|null
     */
    protected function fetchHealthProfessional(Test $test): HealthProfessional|Model|null
    {
        /** @var HealthProfessionalClinic|null $healthProfessionalClinic */
        $healthProfessionalClinic = $test->{Test::RELATION_HEALTH_PROFESSIONAL_CLINICS};

        return $healthProfessionalClinic
            ? HealthProfessional::query()->find($healthProfessionalClinic->{HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID})
            : null;
    }
}
