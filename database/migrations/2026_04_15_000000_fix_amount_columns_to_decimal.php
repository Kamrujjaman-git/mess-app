<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (App::environment('testing') || DB::getDriverName() === 'sqlite') {
            // SQLite doesn't support MODIFY; test DB already uses migrations' decimal types.
            return;
        }

        // Force exact money storage: DECIMAL(?,2) (avoid FLOAT/DOUBLE drift)
        if (Schema::hasTable('market_expenses')) {
            DB::statement('ALTER TABLE `market_expenses` MODIFY `amount` DECIMAL(10,2) NOT NULL');
        }

        if (Schema::hasTable('advance_payments')) {
            DB::statement('ALTER TABLE `advance_payments` MODIFY `amount` DECIMAL(10,2) NOT NULL');
        }

        if (Schema::hasTable('house_rents')) {
            DB::statement('ALTER TABLE `house_rents` MODIFY `amount` DECIMAL(10,2) NOT NULL');
        }

        if (Schema::hasTable('maid_bills')) {
            DB::statement('ALTER TABLE `maid_bills` MODIFY `amount` DECIMAL(10,2) NOT NULL');
        }
    }

    public function down(): void
    {
        // Intentionally no-op: we don't want to revert to imprecise types.
    }
};

