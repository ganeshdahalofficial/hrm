<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['present', 'absent', 'late', 'completed'])->default('present');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->decimal('worked_hours', 5, 2)->default(0);
            $table->timestamps();
            
            // Add index for better performance
            $table->index(['user_id', 'check_in']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};