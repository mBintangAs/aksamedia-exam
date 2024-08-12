<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function nilaiRt()
    {
        try {
            //code...

            $results = DB::select(
            '
                SELECT
                    nama,
                    nisn,
                    GROUP_CONCAT(
                        CONCAT("{\"", nama_pelajaran, "\":", skor, "}")
                        SEPARATOR ","
                    ) as nilaiRT
                FROM
                    nilai
                WHERE
                    materi_uji_id = 7
                    AND nama_pelajaran != "Pelajaran Khusus"
                GROUP BY
                    nama, nisn
            '
        );
            $finalResults = array_map(function ($row) {
                return [
                    'nama' => $row->nama,
                    'nisn' => $row->nisn,
                    'nilaiRT' => json_decode('[' . $row->nilaiRT . ']', true),
                ];
            }, $results);

            return response()->json($finalResults);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
    public function nilaiSt()
    {
        try {
            //code...

            $results = DB::select(
            '
                SELECT
                    nama,
                    nisn,
                    CONCAT(
                        "[",
                        GROUP_CONCAT(
                            CONCAT(
                                "{",
                                "\"", nama_pelajaran, "\": ", -- Menambahkan tanda kutip di sekitar nama_pelajaran
                                CASE
                                    WHEN pelajaran_id = 44 THEN ROUND(skor * 41.67, 2)
                                    WHEN pelajaran_id = 45 THEN ROUND(skor * 29.67, 2)
                                    WHEN pelajaran_id = 46 THEN ROUND(skor * 100,2 )
                                    WHEN pelajaran_id = 47 THEN ROUND(skor * 23.81, 2)
                                    END,
                                "}"
                            )
                            SEPARATOR ","
                        ),
                        "]"
                    ) AS nilaiST,
                    SUM(
                        CASE
                            WHEN pelajaran_id = 44 THEN skor * 41.67
                            WHEN pelajaran_id = 45 THEN skor * 29.67
                            WHEN pelajaran_id = 46 THEN skor * 100
                            WHEN pelajaran_id = 47 THEN skor * 23.81
                        END
                    ) AS total
                    FROM
                        nilai
                    WHERE
                        materi_uji_id = 4
                    GROUP BY
                        nama, nisn
                    ORDER BY
                        total DESC
            ');
            $finalResults = array_map(function ($row) {
                return [
                    'nama' => $row->nama,
                    'nisn' => $row->nisn,
                    'listNilai' => json_decode($row->nilaiST, true),
                    'total' => $row->total,
                ];
            }, $results);

            return response()->json($finalResults);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
}
