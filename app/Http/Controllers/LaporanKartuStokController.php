<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LaporanKartuStokController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        // if(request()->ajax()) {
        //     $query = DB::table('rpstokbarang', 'a')
        //         ->leftJoin('tstokmutasih', 'tstokmutasih.id', '=', 'a.stokmutasih_id')
        //         ->selectRaw("
        //             a.barang_id,
        //             a.gudang_id,
        //             (
        //                 CASE
        //                     WHEN a.stokmutasih_id IS NOT NULL
        //                     THEN tstokmutasih.kode
        //                 END
        //             ) AS kode,
        //             a.tanggal,
        //             IF(a.jumlah > 0, a.jumlah, '') AS masuk,
        //             IF(a.jumlah < 0, ABS(a.jumlah), '') AS keluar,
        //             0 AS saldo,
        //             a.jumlah
        //         ")
        //         ->orderByRaw('
        //             a.tanggal
        //         ');

        //     return DataTables::of($query)->toJson();
        // }

        return view('laporan.kartu_stok');
    }

    public function datasource(Request $request)
    {
        $query = DB::table('rpstokbarang', 'a')
            // ->join('mbarang', 'mbarang.id', '=', 'a.barang_id')
            ->leftJoin('tstokmutasih', 'tstokmutasih.id', '=', 'a.stokmutasih_id')
            ->whereNull('a.deleted_at')
            ->where('a.barang_id', $request->input('barang_id'))
            ->where('a.gudang_id', $request->input('gudang_id'))
            ->selectRaw("
                a.barang_id,
                a.gudang_id,
                (
                    CASE
                        WHEN a.stokmutasih_id IS NOT NULL
                        THEN tstokmutasih.kode
                    END
                ) AS kode,
                a.tanggal,
                IF(a.jumlah > 0, a.jumlah, '') AS masuk,
                IF(a.jumlah < 0, ABS(a.jumlah), '') AS keluar,
                a.jumlah
            ")
            ->orderByRaw('
                a.tanggal, a.id
            ')
            ->get();

        $saldo = 0;
        $items = [];
        foreach ($query as $row) {
            if(floatval($row->masuk) > 0) {
                $row->masuk = floatval($row->masuk);
            }

            if(floatval($row->keluar) > 0) {
                $row->keluar = floatval($row->keluar);
            }

            $row->tanggal = Carbon::parse($row->tanggal)->toDateString();

            $saldo = $saldo + (floatval($row->masuk) - floatval($row->keluar));
            $row->saldo = $saldo;

            $items[] = $row;
        }

        return DataTables::of($items)
            ->toJson();
    }
}
