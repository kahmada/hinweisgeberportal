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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('id');
            $table->boolean('is_anonymous')->default(false)->after('status');
            $table->string('anonymous_username')->unique()->nullable()->after('is_anonymous');
            $table->string('anonymous_password')->nullable()->after('anonymous_username');
            $table->string('anonymous_token', 64)->unique()->nullable()->after('anonymous_password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'is_anonymous', 'anonymous_username', 'anonymous_password', 'anonymous_token']);
        });
    }
};
