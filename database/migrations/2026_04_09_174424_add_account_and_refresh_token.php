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
        // 1. Tạo bảng accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username', 255)->unique();
            $table->string('password', 255);
            $table->tinyInteger('role')->default(2); // 0=Admin, 1=Staff, 2=Customer
            $table->timestamps();
        });

        // 2. Tạo bảng refresh_tokens
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 255)->unique();
            $table->timestamp('expires_at');
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->string('replaced_by_token', 255)->nullable();
            $table->unsignedBigInteger('account_id');
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        // 3. Alter bảng staff: bỏ username/password/role, thêm account_id
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['username', 'password', 'role']);
            $table->unsignedBigInteger('account_id')->nullable()->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
        });

        // 4. Alter bảng customer: bỏ username/password, thêm account_id
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn(['username', 'password']);
            $table->unsignedBigInteger('account_id')->nullable()->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
        });

        // 5. Xóa bảng personal_access_tokens (Sanctum) vì dùng refresh_tokens thay thế
        Schema::dropIfExists('personal_access_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 4. Rollback customer
        Schema::table('customer', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
            $table->string('username', 255)->nullable();
            $table->string('password', 255)->nullable();
        });

        // 3. Rollback staff
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
            $table->string('username', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('role', 255)->nullable();
        });

        // 2. Xóa refresh_tokens
        Schema::dropIfExists('refresh_tokens');

        // 1. Xóa accounts
        Schema::dropIfExists('accounts');

    }
};
