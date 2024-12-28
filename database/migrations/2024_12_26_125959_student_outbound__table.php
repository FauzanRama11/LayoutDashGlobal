<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVTStudentOutboundTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('v_t_student_outbound', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_date');
            $table->dateTime('updated_date');
            $table->string('unit_kerja_text', 255);
            $table->string('nim', 50);
            $table->string('nama_mhs', 255);
            $table->string('jk', 50);
            $table->string('prodi_text', 255);
            $table->string('fakultas_text', 255);
            $table->string('no_telp', 50);
            $table->string('email', 255);
            $table->string('prodi_tujuan_text', 255);
            $table->string('univ_tujuan_text', 255);
            $table->string('negara_tujuan_text', 255);
            $table->string('jenjang', 50);
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('tipe_text', 255);
            $table->string('durasi', 50);
            $table->string('nama_program', 255);
            $table->string('jenis_program_text', 255);
            $table->string('sumber_data_text', 255);
            $table->boolean('verified');
            $table->string('verified_by', 255);
            $table->string('via', 50);
            $table->string('loa', 255);
            $table->string('src_id', 255);
            $table->string('fakultas_tujuan_text', 255);
            $table->text('passport_url');
            $table->text('student_id_url');
            $table->text('cv_url');
            $table->text('photo_url');
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_t_student_outbound');
    }
}
