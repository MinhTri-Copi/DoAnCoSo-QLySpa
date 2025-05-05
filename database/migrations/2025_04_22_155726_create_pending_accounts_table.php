<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('pending_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('Tendangnhap', 100);
            $table->string('Matkhau', 100);
            $table->integer('RoleID');
            $table->string('Hoten', 100);
            $table->string('SDT', 15)->nullable();
            $table->string('DiaChi', 200)->nullable();
            $table->string('Email', 100);
            $table->date('Ngaysinh')->nullable();
            $table->string('Gioitinh', 10)->nullable();
            $table->string('token', 100)->unique(); // Token để xác nhận
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pending_accounts');
    }
}