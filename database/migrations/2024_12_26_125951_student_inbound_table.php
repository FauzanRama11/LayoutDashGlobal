<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('v_t_student_inbound', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_date');
            $table->dateTime('updated_date');
            $table->string('unit_kerja', 255);
            $table->string('unit_kerja_text', 255);
            $table->string('nama', 255);
            $table->string('jenis_kelamin', 50);
            $table->date('tgl_lahir');
            $table->string('telp', 50);
            $table->string('email', 255);
            $table->string('jenjang', 50);
            $table->string('prodi_asal', 255);
            $table->string('fakultas_asal', 255);
            $table->string('tujuan_fakultas_unit', 255);
            $table->string('tujuan_fakultas_unit_text', 255);
            $table->string('tujuan_prodi', 255);
            $table->string('tujuan_prodi_text', 255);
            $table->string('jenjang_prodi', 50);
            $table->string('univ_asal', 255);
            $table->string('univ_asal_text', 255);
            $table->string('negara_asal', 255);
            $table->string('negara_asal_text', 255);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('tipe_text', 255);
            $table->string('durasi', 50);
            $table->string('program', 255);
            $table->string('program_text', 255);
            $table->boolean('is_degree');
            $table->string('jenis_kegiatan', 255);
            $table->string('jenis_kegiatan_text', 255);
            $table->string('via', 50);
            $table->string('sumber_data', 255);
            $table->string('sumber_data_text', 255);
            $table->string('kebangsaan', 255);
            $table->string('negara_tempat_tinggal', 255);
            $table->text('alamat_rumah');
            $table->text('foto');
            $table->string('passport', 255);
            $table->string('student_id', 255);
            $table->string('loa', 255);
            $table->string('src_id', 255);
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('v_t_student_inbound');
    }
};
