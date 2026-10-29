<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'dipinjam', 'dikembalikan', 'terlambat', 'ditolak'])
                ->default('menunggu')->change();
            $table->foreignId('verified_by')->nullable()->after('status')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->string('rejection_note')->nullable()->after('verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn(['verified_at', 'rejection_note']);
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                ->default('dipinjam')->change();
        });
    }
};