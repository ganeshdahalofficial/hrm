<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure tasks table has the correct structure
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                // 1. Rename 'name' to 'title' if 'name' exists and 'title' doesn't
                if (Schema::hasColumn('tasks', 'name') && !Schema::hasColumn('tasks', 'title')) {
                    $table->renameColumn('name', 'title');
                }
                
                // 2. Add 'title' column if it doesn't exist
                if (!Schema::hasColumn('tasks', 'title')) {
                    $table->string('title')->after('id');
                }
                
                // 3. Ensure 'assigned_to' has foreign key constraint
                if (Schema::hasColumn('tasks', 'assigned_to')) {
                    $sm = Schema::getConnection()->getDoctrineSchemaManager();
                    $tableDetails = $sm->listTableDetails('tasks');
                    $foreignKeys = $tableDetails->getForeignKeys();
                    
                    $hasForeignKey = false;
                    foreach ($foreignKeys as $foreignKey) {
                        if (in_array('assigned_to', $foreignKey->getLocalColumns())) {
                            $hasForeignKey = true;
                            break;
                        }
                    }
                    
                    if (!$hasForeignKey) {
                        $table->foreign('assigned_to')
                              ->references('id')
                              ->on('users')
                              ->onDelete('set null');
                    }
                } else {
                    // Add assigned_to column with foreign key
                    $table->foreignId('assigned_to')
                          ->nullable()
                          ->constrained('users')
                          ->onDelete('set null');
                }
                
                // 4. Ensure status column has correct enum values including 'unknown'
                if (Schema::hasColumn('tasks', 'status')) {
                    // We'll handle this with a separate migration if needed
                }
            });
        }
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop foreign key but keep the column
            $table->dropForeign(['assigned_to']);
            
            // Don't revert column name changes to avoid data loss
        });
    }
};