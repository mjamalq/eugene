<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const TABLE                        = 'health_professionals_clinics';
    const FIELD_HEALTH_PROFESSIONAL_ID = 'health_professional_id';
    const FIELD_CLINIC_ID              = 'clinic_id';
    const FIELD_ID                     = 'id';

    const HEALTH_PROFESSIONALS_TABLE = 'health_professionals';
    const CLINICS_TABLE              = 'clinics';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger(self::FIELD_HEALTH_PROFESSIONAL_ID)->nullable();
            $table->foreign(self::FIELD_HEALTH_PROFESSIONAL_ID)
                  ->references(self::FIELD_ID)
                  ->on(self::HEALTH_PROFESSIONALS_TABLE)
                  ->onDelete('cascade');

            $table->unsignedBigInteger(self::FIELD_CLINIC_ID)->nullable();
            $table->foreign(self::FIELD_CLINIC_ID)
                  ->references(self::FIELD_ID)
                  ->on(self::CLINICS_TABLE)
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
