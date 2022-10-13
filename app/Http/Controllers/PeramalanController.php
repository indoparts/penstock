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
        $peramalan = array(
            'penjualan' => [],
            'peramalan' => [],
        );
        foreach ($produk as $key) {
            $q = DB::select("SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun,MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
            data_produk_id='" . $key['id'] . "' GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)");
            $q_array = array();
            foreach ($q as $k) {
                array_push($q_array, $k->jumlah);
            }
            // Langkah ke 1
            $a = 2 / (count($q_array) + 1); //menentukan koefisien α, dengan rumus α =(2/n+1)
            //Langkah ke 2
            $sumarr = array_sum($q_array); //hitung total keseluruhan permintaan
            $f1 = $sumarr / count($q_array);
            //Langkah ke 3 yakni menghitung nilai peramalan di keseluruhan periode
            for ($i = 0; $i < count($q_array); $i++) {
                ${'f' . ($i + 2)} = ${'f' . ($i + 1)} + $a * ($q_array[$i] - ${'f' . ($i + 1)});
                array_push($peramalan['penjualan'], [
                    'produk' => $key['nama_produk'],
                    'penjualan' => round(${'f' . ($i + 1)})
                ]);
            }
            array_push($peramalan['peramalan'], [
                'produk' => $key['nama_produk'],
                'pembelian' => ceil(round(${'f' . (count($q_array) + 1)}))
            ]);
        }
        return view('peramalan.index', compact('peramalan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $produk = DataProduk::get();
        $peramalan = array(
            'penjualan' => [],
            'peramalan' => [],
        );
        foreach ($produk as $key) {
            $q = DB::select("SELECT SUM(lembar) AS jumlah, YEAR(tggl_transaksi) as tahun,MONTH(tggl_transaksi) AS bulan FROM data_penjualans WHERE 
            data_produk_id='" . $key['id'] . "' GROUP BY YEAR(tggl_transaksi),MONTH(tggl_transaksi)");
            $q_array = array();
            foreach ($q as $k) {
                array_push($q_array, $k->jumlah);
            }
            // Langkah ke 1
            $a = 2 / (count($q_array) + 1); //menentukan koefisien α, dengan rumus α =(2/n+1)
            //Langkah ke 2
            $sumarr = array_sum($q_array); //hitung total keseluruhan permintaan
            $f1 = $sumarr / count($q_array);
            //Langkah ke 3 yakni menghitung nilai peramalan di keseluruhan periode
            for ($i = 0; $i < count($q_array); $i++) {
                ${'f' . ($i + 2)} = ${'f' . ($i + 1)} + $a * ($q_array[$i] - ${'f' . ($i + 1)});
                array_push($peramalan['penjualan'], [
                    'produk' => $key['nama_produk'],
                    'penjualan' => round(${'f' . ($i + 1)})
                ]);
            }
            array_push($peramalan['peramalan'], [
                'produk' => $key['nama_produk'],
                'pembelian' => ceil(round(${'f' . (count($q_array) + 1)}))
            ]);
        }

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
        $sheet = $spreadsheet->getActiveSheet()->setTitle("Penjualan");
        $sheet->setCellValue('A1', 'Nama Produk')->getStyle('A1')->applyFromArray($styleArray);
        $sheet->setCellValue('B1', 'Jumlah Penjualan')->getStyle('B1')->applyFromArray($styleArray);
        $i = 2;
        foreach ($peramalan['penjualan'] as $k) {
            $sheet->setCellValue('A' . $i, $k['produk'])->getStyle('A' . $i)->applyFromArray($styleValue);
            $sheet->setCellValue('B' . $i, $k['penjualan'])->getStyle('B' . $i)->applyFromArray($styleValue);
            $i++;
        }

        $newsheet = $spreadsheet->createSheet()->setTitle("Peramalan");
        $newsheet->setCellValue('A1', 'Nama Produk')->getStyle('A1')->applyFromArray($styleArray);
        $newsheet->setCellValue('B1', 'Jumlah Persediaan')->getStyle('B1')->applyFromArray($styleArray);
        $x = 2;
        foreach ($peramalan['peramalan'] as $k) {
            $newsheet->setCellValue('A' . $x, $k['produk'])->getStyle('A' . $x)->applyFromArray($styleValue);
            $newsheet->setCellValue('B' . $x, $k['pembelian'])->getStyle('B' . $x)->applyFromArray($styleValue);
            $x++;
        }

        $filename = 'perhitungan' . date('YmdHis') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
        $file = public_path() . "/" . $filename;
        return response()->download($file, $filename)->deleteFileAfterSend(true);
    }
}
