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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('ticket_number')->nullable()->after('id')->unique();
        });

        Schema::table('website_requests', function (Blueprint $table) {
            $table->string('ticket_number')->nullable()->after('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('ticket_number');
        });

        Schema::table('website_requests', function (Blueprint $table) {
            $table->dropColumn('ticket_number');
        });
    }
};
