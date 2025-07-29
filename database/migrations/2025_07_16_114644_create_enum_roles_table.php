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
        if (!Schema::hasTable('enum_roles')) {
            Schema::create('enum_roles', function (Blueprint $table) {
                $table->id();
                $table->string('value')->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint dari tabel roles dulu
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                // Jika nama FK sesuai error: roles_enum_role_id_foreign
                $table->dropForeign(['enum_role_id']);
            });
        }
        // Baru drop table enum_roles
        Schema::dropIfExists('enum_roles');
    }
};
