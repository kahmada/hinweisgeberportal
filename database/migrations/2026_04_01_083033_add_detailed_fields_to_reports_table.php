<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('company')->nullable()->after('title');
            $table->string('violation_type')->nullable()->after('company');
            $table->date('incident_date')->nullable()->after('violation_type');
            $table->string('incident_location')->nullable()->after('incident_date');
            $table->text('involved_persons')->nullable()->after('incident_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['company', 'violation_type', 'incident_date', 'incident_location', 'involved_persons']);
        });
    }
};
