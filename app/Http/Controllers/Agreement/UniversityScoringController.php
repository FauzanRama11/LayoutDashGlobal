<?php

namespace App\Http\Controllers\agreement;
use App\Http\Controllers\Controller;
use App\Models\GrieMoaAcademic;
use App\Models\GrieMoaAcademicFaculty;
use App\Models\GrieMoaAcademicPartner;
use App\Models\GrieMoaAcademicProdi;
use App\Models\GrieMoaAcademicScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UniversityScoringController extends Controller
{
    public function univ_score(){
        $query = DB::table('grie_moa_academic_partner AS p')
            ->join('grie_moa_academic AS a', 'a.id', '=', 'p.id_moa_academic')
            ->join('m_university AS u', 'u.id', '=', 'p.id_partner_university')
            ->selectRaw('
                u.name AS university_name,
                SUM(CASE WHEN a.jenis_naskah = "MoU" THEN 1 ELSE 0 END) AS total_mou,
                SUM(CASE WHEN a.jenis_naskah = "MoA" THEN 1 ELSE 0 END) AS total_moa,
                SUM(CASE WHEN a.jenis_naskah = "IA" THEN 1 ELSE 0 END) AS total_ia,
                CASE 
                    WHEN (SUM(CASE WHEN a.jenis_naskah = "MoU" THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN a.jenis_naskah = "MoA" THEN 1 ELSE 0 END) +
                        SUM(CASE WHEN a.jenis_naskah = "IA" THEN 1 ELSE 0 END)) > 1 THEN 1
                    ELSE 0
                END AS score,
                (
                    SELECT COUNT(pes_in.id)
                    FROM m_stu_in_peserta AS pes_in
                    LEFT JOIN m_stu_in_programs AS prog_in ON prog_in.id = pes_in.program_id
                    WHERE pes_in.is_approved = 1 
                    AND YEAR(prog_in.end_date) = YEAR(CURDATE())
                    AND pes_in.univ = u.id
                ) AS student_inbound,
                (
                    SELECT COUNT(pes_out.id)
                    FROM m_stu_out_peserta AS pes_out
                    LEFT JOIN m_stu_out_programs AS prog_out ON prog_out.id = pes_out.program_id
                    WHERE pes_out.is_approved = 1 
                    AND YEAR(prog_out.end_date) = YEAR(CURDATE())
                    AND pes_out.univ = u.id
                ) AS student_outbound,
                (
                    SELECT COUNT(sta_in.id)
                    FROM m_sta_in_peserta AS sta_in
                    LEFT JOIN m_sta_in_programs AS prog_sta ON prog_sta.id = sta_in.program_id
                    WHERE sta_in.is_approved = 1 
                    AND YEAR(prog_sta.end_date) = YEAR(CURDATE())
                    AND sta_in.univ = u.id
                ) AS staff_inbound,
                (
                    SELECT COUNT(sta_out.id)
                    FROM m_sta_out_peserta AS sta_out
                    LEFT JOIN m_sta_out_programs AS prog_sta_out ON prog_sta_out.id = sta_out.program_id
                    WHERE sta_out.is_approved = 1 
                    AND YEAR(prog_sta_out.end_date) = YEAR(CURDATE())
                    AND sta_out.univ = u.id
                ) AS staff_outbound,
                (
                    CASE 
                        WHEN (SUM(CASE WHEN a.jenis_naskah = "MoU" THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN a.jenis_naskah = "MoA" THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN a.jenis_naskah = "IA" THEN 1 ELSE 0 END)) > 1 THEN 1
                        ELSE 0
                    END + 
                    COALESCE((
                        SELECT COUNT(pes_in.id)
                        FROM m_stu_in_peserta AS pes_in
                        LEFT JOIN m_stu_in_programs AS prog_in ON prog_in.id = pes_in.program_id
                        WHERE pes_in.is_approved = 1 
                        AND YEAR(prog_in.end_date) = YEAR(CURDATE())
                        AND pes_in.univ = u.id
                    ), 0) + 
                    COALESCE((
                        SELECT COUNT(pes_out.id)
                        FROM m_stu_out_peserta AS pes_out
                        LEFT JOIN m_stu_out_programs AS prog_out ON prog_out.id = pes_out.program_id
                        WHERE pes_out.is_approved = 1 
                        AND YEAR(prog_out.end_date) = YEAR(CURDATE())
                        AND pes_out.univ = u.id
                    ), 0) + 
                    COALESCE((
                        SELECT COUNT(sta_in.id)
                        FROM m_sta_in_peserta AS sta_in
                        LEFT JOIN m_sta_in_programs AS prog_sta ON prog_sta.id = sta_in.program_id
                        WHERE sta_in.is_approved = 1 
                        AND YEAR(prog_sta.end_date) = YEAR(CURDATE())
                        AND sta_in.univ = u.id
                    ), 0) + 
                    COALESCE((
                        SELECT COUNT(sta_out.id)
                        FROM m_sta_out_peserta AS sta_out
                        LEFT JOIN m_sta_out_programs AS prog_sta_out ON prog_sta_out.id = sta_out.program_id
                        WHERE sta_out.is_approved = 1 
                        AND YEAR(prog_sta_out.end_date) = YEAR(CURDATE())
                        AND sta_out.univ = u.id
                    ), 0)
                ) AS kumulatif_score,
                CASE 
                    WHEN (CASE 
                        WHEN (SUM(CASE WHEN a.jenis_naskah = "MoU" THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN a.jenis_naskah = "MoA" THEN 1 ELSE 0 END) +
                            SUM(CASE WHEN a.jenis_naskah = "IA" THEN 1 ELSE 0 END)) > 1 THEN 1
                        ELSE 0
                    END + 
                    COALESCE((
                        SELECT COUNT(pes_in.id)
                        FROM m_stu_in_peserta AS pes_in
                        LEFT JOIN m_stu_in_programs AS prog_in ON prog_in.id = pes_in.program_id
                        WHERE pes_in.is_approved = 1 
                        AND YEAR(prog_in.end_date) = YEAR(CURDATE())
                        AND pes_in.univ = u.id
                    ), 0) + 
                    COALESCE((
                        SELECT COUNT(pes_out.id)
                        FROM m_stu_out_peserta AS pes_out
                        LEFT JOIN m_stu_out_programs AS prog_out ON prog_out.id = pes_out.program_id
                        WHERE pes_out.is_approved = 1 
                        AND YEAR(prog_out.end_date) = YEAR(CURDATE())
                        AND pes_out.univ = u.id
                    ), 0) + 
                    COALESCE((
                        SELECT COUNT(sta_in.id)
                        FROM m_sta_in_peserta AS sta_in
                        LEFT JOIN m_sta_in_programs AS prog_sta ON prog_sta.id = sta_in.program_id
                        WHERE sta_in.is_approved = 1 
                        AND YEAR(prog_sta.end_date) = YEAR(CURDATE())
                        AND sta_in.univ = u.id
                    ), 0) +
                    COALESCE((
                        SELECT COUNT(sta_out.id)
                        FROM m_sta_out_peserta AS sta_out
                        LEFT JOIN m_sta_out_programs AS prog_sta_out ON prog_sta_out.id = sta_out.program_id
                        WHERE sta_out.is_approved = 1 
                        AND YEAR(prog_sta_out.end_date) = YEAR(CURDATE())
                        AND sta_out.univ = u.id
                    ), 0)) = 0 THEN "sleeping"
                    ELSE "active"
                END AS status
            ')
            ->where(function ($query) {
                $query->whereRaw('DATEDIFF(a.mou_end_date, CURDATE()) > 1')
                    ->orWhere('a.mou_end_date', '=', '0000-00-00');
            })
            ->groupBy('u.name', 'u.id')
            ->orderByDesc('kumulatif_score')
            ->get();


        return view('agreement.university_score', compact('query'));
    }
}