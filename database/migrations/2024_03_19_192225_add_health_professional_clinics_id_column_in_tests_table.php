<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE                               = 'tests';
    const FIELD_ID                            = 'id';
    const FIELD_REFERRING_DOCTOR_ID           = 'referring_doctor_id';
    const FIELD_HEALTH_PROFESSIONAL_CLINIC_ID = 'health_professional_clinic_id';

    const HEALTH_PROFESSIONAL_CLINICS_TABLE = 'health_professionals_clinics';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table) {

            $table->unsignedBigInteger(self::FIELD_HEALTH_PROFESSIONAL_CLINIC_ID)
                  ->nullable()
                  ->after(self::FIELD_REFERRING_DOCTOR_ID);

            $table->foreign(self::FIELD_HEALTH_PROFESSIONAL_CLINIC_ID)
                  ->references(self::FIELD_ID)
                  ->on(self::HEALTH_PROFESSIONAL_CLINICS_TABLE)
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropColumn(self::FIELD_HEALTH_PROFESSIONAL_CLINIC_ID);
        });
    }
};
