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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('client')->nullable();
            // planning, active, maintenance, paused, completed, archived
            $table->string('status')->default('active');

            // Live / code
            $table->string('production_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('tech_stack')->nullable();

            // Hosting
            $table->string('hosting_provider')->nullable();
            $table->string('server_info')->nullable(); // IP / server / panel
            $table->date('hosting_expires_at')->nullable();

            // Domain
            $table->string('domain')->nullable();
            $table->string('domain_registrar')->nullable();
            $table->date('domain_expires_at')->nullable();

            // Commercials / timeline
            $table->decimal('monthly_cost', 10, 2)->nullable();
            $table->string('currency', 8)->default('EUR');
            $table->date('start_date')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
