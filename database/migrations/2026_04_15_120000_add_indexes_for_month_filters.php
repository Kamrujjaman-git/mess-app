<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->index(['date', 'user_id'], 'meals_date_user_id_idx');
            $table->index(['user_id', 'date'], 'meals_user_id_date_idx');
        });

        Schema::table('market_expenses', function (Blueprint $table) {
            $table->index(['date', 'user_id'], 'market_expenses_date_user_id_idx');
            $table->index(['user_id', 'date'], 'market_expenses_user_id_date_idx');
        });

        Schema::table('advance_payments', function (Blueprint $table) {
            $table->index(['date', 'user_id'], 'advance_payments_date_user_id_idx');
            $table->index(['user_id', 'date'], 'advance_payments_user_id_date_idx');
        });

        Schema::table('house_rents', function (Blueprint $table) {
            $table->index(['month', 'user_id'], 'house_rents_month_user_id_idx');
        });

        Schema::table('maid_bills', function (Blueprint $table) {
            $table->index(['month', 'user_id'], 'maid_bills_month_user_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropIndex('meals_date_user_id_idx');
            $table->dropIndex('meals_user_id_date_idx');
        });

        Schema::table('market_expenses', function (Blueprint $table) {
            $table->dropIndex('market_expenses_date_user_id_idx');
            $table->dropIndex('market_expenses_user_id_date_idx');
        });

        Schema::table('advance_payments', function (Blueprint $table) {
            $table->dropIndex('advance_payments_date_user_id_idx');
            $table->dropIndex('advance_payments_user_id_date_idx');
        });

        Schema::table('house_rents', function (Blueprint $table) {
            $table->dropIndex('house_rents_month_user_id_idx');
        });

        Schema::table('maid_bills', function (Blueprint $table) {
            $table->dropIndex('maid_bills_month_user_id_idx');
        });
    }
};

