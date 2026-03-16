<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // ✅ ví dụ danh sách cột cần drop (bạn thay đúng tên cột đang drop trong file)
            $columns = [
                'roles',
                'role_id',
                'admin_id',
                'admin_name',
                'old_admin',
                // ... thêm các cột mà file của bạn đang drop ...
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nếu bạn muốn phục hồi các cột này khi rollback, bạn cần định nghĩa lại chúng ở đây.
            // Tuy nhiên, nếu bạn không cần phục hồi thì có thể để trống phần down() hoặc chỉ ghi chú.
        });
    }
};
