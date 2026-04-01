<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('identity_revealed_at')->nullable()->after('status');
            $table->unsignedBigInteger('identity_revealed_by')->nullable()->after('identity_revealed_at');
            
            $table->foreign('identity_revealed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['identity_revealed_by']);
            $table->dropColumn(['identity_revealed_at', 'identity_revealed_by']);
        });
    }
};
