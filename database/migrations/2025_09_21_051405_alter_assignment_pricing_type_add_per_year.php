<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $table = 'assignments';

    public function up(): void
    {
        // If any unexpected values exist, null them to avoid enum error (optional safety)
        DB::statement("
            UPDATE `{$this->table}`
            SET `assignment_pricing_type` = NULL
            WHERE `assignment_pricing_type` NOT IN ('per month','per assignment')
        ");

        // Add 'per year' to enum
        DB::statement("
            ALTER TABLE `{$this->table}`
            MODIFY `assignment_pricing_type`
            ENUM('per month','per assignment','per year')
            NULL
            COLLATE utf8mb4_general_ci
        ");
    }

    public function down(): void
    {
        // Map 'per year' to a safe value before shrinking enum
        DB::statement("
            UPDATE `{$this->table}`
            SET `assignment_pricing_type` = 'per month'
            WHERE `assignment_pricing_type` = 'per year'
        ");

        // Revert enum
        DB::statement("
            ALTER TABLE `{$this->table}`
            MODIFY `assignment_pricing_type`
            ENUM('per month','per assignment')
            NULL
            COLLATE utf8mb4_general_ci
        ");
    }
};
