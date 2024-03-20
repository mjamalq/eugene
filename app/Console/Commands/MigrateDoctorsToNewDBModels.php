<?php

namespace App\Console\Commands;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalClinic;
use App\Models\Test;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class MigrateDoctorsToNewDBModels extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'migrate-doctors-to-new-db-models';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Create HealthProfessionals, Clinics, and updates Tests models from the existing Doctors model.';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle(): int
    {
        $total = Doctor::all()->count();
        $this->info("Gathering data for $total doctor(s).");

        $progress = $this->getProgressBar($total);
        $progress->start();

        /** @var Collection $doctorsChunk */
        Doctor::query()->chunk(100, function ($doctorsChunk) use(&$progress) {
            /** @var Doctor $doctor */
            foreach ($doctorsChunk as $doctor) {
                $healthProfessionalClinic = $this->setUpHealthProfessionalsAndClinicsData($doctor);
                $this->updateTestsToConsolidatedReference($doctor, $healthProfessionalClinic);
            }

            $progress->advance(count($doctorsChunk));
        });

        $progress->finish();

        return 0;
    }

    /**
     * Handles migrating data into clinics, health professionals and their relationship models.
     *
     * @param Doctor $doctor
     * @return HealthProfessionalClinic
     */
    protected function setUpHealthProfessionalsAndClinicsData(Doctor $doctor): HealthProfessionalClinic
    {
        // since the current doctor model's timestamp is the only one which falls against a doctor and clinic so the new models will follow the same
        $timestamps = [
            Doctor::FIELD_CREATED_AT => $doctor->{Doctor::FIELD_CREATED_AT},
            Doctor::FIELD_UPDATED_AT => $doctor->{Doctor::FIELD_UPDATED_AT},
        ];

        /** @var HealthProfessional $healthProfessional */
        $healthProfessional = HealthProfessional::query()->updateOrCreate(
            [
                HealthProfessional::FIELD_NAME       => $doctor->{Doctor::FIELD_NAME},
                HealthProfessional::FIELD_SPECIALITY => $doctor->{Doctor::FIELD_SPECIALITY},
            ],
            [
                HealthProfessional::FIELD_NAME       => $doctor->{Doctor::FIELD_NAME},
                HealthProfessional::FIELD_SPECIALITY => $doctor->{Doctor::FIELD_SPECIALITY},
                ...$timestamps
            ]
        );

        /** @var Clinic $clinic */
        $clinic = Clinic::query()->updateOrCreate(
            [
                Clinic::FIELD_NAME    => $doctor->{Doctor::FIELD_CLINIC_NAME},
                Clinic::FIELD_ADDRESS => $doctor->{Doctor::FIELD_CLINIC_ADDRESS},
            ],
            [
                Clinic::FIELD_NAME    => $doctor->{Doctor::FIELD_CLINIC_NAME},
                Clinic::FIELD_ADDRESS => $doctor->{Doctor::FIELD_CLINIC_ADDRESS},
                ...$timestamps
            ]
        );

        // Establish health professionals and clinics associations/data
        /** @var HealthProfessionalClinic $healthProfessionalClinic */
        $healthProfessionalClinic = HealthProfessionalClinic::query()->create([
            HealthProfessionalClinic::FIELD_HEALTH_PROFESSIONAL_ID => $healthProfessional->{HealthProfessional::FIELD_ID},
            HealthProfessionalClinic::FIELD_CLINIC_ID              => $clinic->{Clinic::FIELD_ID},
            ...$timestamps
        ]);

        return $healthProfessionalClinic;
    }

    /**
     * Update the tests data to the new reference (carrying association of clinic and health professional in it)
     *
     * @param Doctor                   $doctor
     * @param HealthProfessionalClinic $healthProfessionalClinic
     * @return void
     */
    protected function updateTestsToConsolidatedReference(Doctor $doctor, HealthProfessionalClinic $healthProfessionalClinic): void
    {
        DB::table(Test::TABLE)
            ->where(Test::FIELD_REFERRING_DOCTOR_ID, $doctor->{Doctor::FIELD_ID})
            ->update([
                Test::FIELD_HEALTH_PROFESSIONAL_CLINIC_ID => $healthProfessionalClinic->{HealthProfessionalClinic::FIELD_ID}
            ]);
    }

    /**
     * Helper function for creating a progress bar.
     *
     * @param int $count
     * @return ProgressBar
     */
    protected function getProgressBar(int $count = 0): ProgressBar
    {
        return $this->output->createProgressBar($count);
    }
}

