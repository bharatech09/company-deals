<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private string $table = 'assignments';

    public function up(): void
    {
        // BIGINT UNSIGNED to allow very large positive amounts
        DB::statement("
            ALTER TABLE `{$this->table}`
            MODIFY `deal_price_amount` BIGINT UNSIGNED NULL
        ");
    }

    public function down(): void
    {
        // Revert to original INT(11)
        DB::statement("
            ALTER TABLE `{$this->table}`
            MODIFY `deal_price_amount` INT(11) NULL
        ");
    }
};
