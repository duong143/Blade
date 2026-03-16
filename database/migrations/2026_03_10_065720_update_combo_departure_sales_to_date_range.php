<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('combo_departure_sales', 'start_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('departure_id');
            });
        }

        if (!Schema::hasColumn('combo_departure_sales', 'end_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->date('end_date')->nullable()->after('start_date');
            });
        }

        if (Schema::hasColumn('combo_departure_sales', 'active_date')) {
            DB::statement("
                UPDATE combo_departure_sales
                SET start_date = COALESCE(start_date, active_date),
                    end_date = COALESCE(end_date, active_date)
            ");
        }

        if (!$this->indexExists('combo_departure_sales', 'combo_departure_sales_departure_id_index')) {
            DB::statement("
                ALTER TABLE combo_departure_sales
                ADD INDEX combo_departure_sales_departure_id_index (departure_id)
            ");
        }

        if (
            !$this->indexExists('combo_departure_sales', 'combo_departure_sales_departure_start_end_unique')
            && Schema::hasColumn('combo_departure_sales', 'start_date')
            && Schema::hasColumn('combo_departure_sales', 'end_date')
        ) {
            DB::statement("
                ALTER TABLE combo_departure_sales
                ADD UNIQUE combo_departure_sales_departure_start_end_unique (departure_id, start_date, end_date)
            ");
        }

        if ($this->indexExists('combo_departure_sales', 'combo_departure_sales_departure_id_active_date_unique')) {
            DB::statement("
                ALTER TABLE combo_departure_sales
                DROP INDEX combo_departure_sales_departure_id_active_date_unique
            ");
        }

        if (Schema::hasColumn('combo_departure_sales', 'active_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->dropColumn('active_date');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('combo_departure_sales', 'active_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->date('active_date')->nullable()->after('departure_id');
            });
        }

        if (Schema::hasColumn('combo_departure_sales', 'start_date')) {
            DB::statement("
                UPDATE combo_departure_sales
                SET active_date = COALESCE(active_date, start_date)
            ");
        }

        if (
            !$this->indexExists('combo_departure_sales', 'combo_departure_sales_departure_id_active_date_unique')
            && Schema::hasColumn('combo_departure_sales', 'active_date')
        ) {
            DB::statement("
                ALTER TABLE combo_departure_sales
                ADD UNIQUE combo_departure_sales_departure_id_active_date_unique (departure_id, active_date)
            ");
        }

        if ($this->indexExists('combo_departure_sales', 'combo_departure_sales_departure_start_end_unique')) {
            DB::statement("
                ALTER TABLE combo_departure_sales
                DROP INDEX combo_departure_sales_departure_start_end_unique
            ");
        }

        if (Schema::hasColumn('combo_departure_sales', 'start_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->dropColumn('start_date');
            });
        }

        if (Schema::hasColumn('combo_departure_sales', 'end_date')) {
            Schema::table('combo_departure_sales', function (Blueprint $table) {
                $table->dropColumn('end_date');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select(
            "
            SELECT 1
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name = ?
              AND index_name = ?
            LIMIT 1
            ",
            [$database, $table, $indexName]
        );

        return !empty($result);
    }
};