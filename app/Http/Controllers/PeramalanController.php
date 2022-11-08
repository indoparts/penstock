<?php

namespace App\Http\Controllers;

use App\Models\Peramalan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use App\Models\DataProduk;

class PeramalanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function perhitungan()
    {
        $produk = DataProduk::get();
        return view('peramalan.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $produk = DataProduk::get();
        $peramalan = array();
        $peramln = [];
        $x1 = [];
        $x2 = [];
        foreach ($produk as $key) {
            $q = DB::select("SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun,MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
            data_produk_id='" . $key['id'] . "' GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)");
            $a = 0.1;
            $x = 1 - $a;
            for ($i = 0; $i < count($q); $i++) {
                if ($i == 0) {
                    array_push($peramln, $q[$i]->jumlah);
                }
                array_push($x1, $a * $q[$i]->jumlah);
                array_push($x2, $x * $peramln[$i]);
                array_push($peramln, round($x1[$i] + $x2[$i]));
                array_push($peramalan, [
                    'nama_produk'=>$key->nama_produk,
                    'tahun'=>$q[$i]->tahun,
                    'penjualan'=>$q[$i]->jumlah,
                    'peramalan'=>count($peramln) > 0 ? $peramln[$i+1] : 0,
                ]);
            }
        }
        // dd($peramalan);

        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];
        $styleValue = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle("Peramalan");
        $sheet->setCellValue('A1', 'Nama Produk')->getStyle('A1')->applyFromArray($styleArray);
        $sheet->setCellValue('B1', 'Jumlah Penjualan')->getStyle('B1')->applyFromArray($styleArray);
        $sheet->setCellValue('C1', 'Jumlah Peramalan')->getStyle('C1')->applyFromArray($styleArray);
        $sheet->setCellValue('D1', 'Tahun')->getStyle('D1')->applyFromArray($styleArray);
        $i = 2;
        foreach ($peramalan as $k) {
            $sheet->setCellValue('A' . $i, $k['nama_produk'])->getStyle('A' . $i)->applyFromArray($styleValue);
            $sheet->setCellValue('B' . $i, $k['penjualan'])->getStyle('B' . $i)->applyFromArray($styleValue);
            $sheet->setCellValue('C' . $i, $k['peramalan'])->getStyle('C' . $i)->applyFromArray($styleValue);
            $sheet->setCellValue('D' . $i, $k['tahun'])->getStyle('D' . $i)->applyFromArray($styleValue);
            $i++;
        }

        $filename = 'perhitungan' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $file = public_path() . "/" . $filename;
        return response()->download($file, $filename)->deleteFileAfterSend(true);
    }
}
