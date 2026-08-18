<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRegistrasiNullableColumns extends Migration
{
    public function up()
    {
        Schema::table('registrasi', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tgl_lahir')->nullable()->change();
            $table->string('npwp')->nullable()->change();
            $table->string('pekerjaan')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->string('nama_saudara')->nullable()->change();
            $table->string('no_telp_saudara')->nullable()->change();
            $table->unsignedBigInteger('id_freelance')->nullable()->change();
            $table->string('jenis_perumahan')->nullable()->change();
            $table->string('jenis_pembelian')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('registrasi', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tgl_lahir')->nullable(false)->change();
            $table->string('npwp')->nullable(false)->change();
            $table->string('pekerjaan')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->string('nama_saudara')->nullable(false)->change();
            $table->string('no_telp_saudara')->nullable(false)->change();
            $table->unsignedBigInteger('id_freelance')->nullable(false)->change();
            $table->string('jenis_perumahan')->nullable(false)->change();
            $table->string('jenis_pembelian')->nullable(false)->change();
        });
    }
}
