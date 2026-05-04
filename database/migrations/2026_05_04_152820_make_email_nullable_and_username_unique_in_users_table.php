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
        // Fill empty usernames before making unique
        \App\Models\User::whereNull('username')->orWhere('username', '')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $username = $user->email ? explode('@', $user->email)[0] : 'user_' . $user->id;
                // Ensure unique
                $originalUsername = $username;
                $counter = 1;
                while (\App\Models\User::where('username', $username)->exists()) {
                    $username = $originalUsername . $counter++;
                }
                $user->update(['username' => $username]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            if (!Schema::hasIndex('users', 'users_username_unique')) {
                $table->unique('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            if (Schema::hasIndex('users', 'users_username_unique')) {
                $table->dropUnique(['username']);
            }
        });
    }
};
