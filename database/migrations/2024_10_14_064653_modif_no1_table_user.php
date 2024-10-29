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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password');
            $table->string('token');
            $table->string('open_id');
            $table->tinyInteger('type');
            $table->string('access_token')->nullable();
            $table->string('phone')->nullable();
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('avatar');
            $table->dropColumn('token');
            $table->dropColumn('open_id');
            $table->dropColumn('type');
            $table->dropColumn('access_token');
            $table->dropColumn('phone');
            $table->dropSoftDeletes();
        });
    }
};
