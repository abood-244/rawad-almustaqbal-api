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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('service_type');
            $table->foreignId('service_id')->nullable()->after('location')->constrained('services')->nullOnDelete();
            $table->index('status');
            $table->index('created_at');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('category');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['category']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->string('service_type')->after('location');
        });
    }
};
