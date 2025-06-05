<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->boolean('is_admin')->default(false)->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('is_admin');
            $table->integer('games_won')->default(0)->after('last_login_at');
            $table->integer('games_played')->default(0)->after('games_won');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'avatar', 'is_admin', 'last_login_at', 'games_won', 'games_played']);
        });
    }
};
