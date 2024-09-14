<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LaporanStokMinimumController extends Controller
{
    public function index()
    {
        return view('laporan.stok_minimum');
    }

    public function datasource(Request $request)
    {
        $items = DB::table('rpstokbarang', 'a')
            ->join('mbarang', 'mbarang.id', '=', 'a.barang_id')
            ->whereNull('a.deleted_at')
            ->selectRaw("
                mbarang.nama AS barang,
                SUM(a.jumlah) AS stok,
                (
                    CASE
                        WHEN SUM(a.jumlah) < mbarang.stok_minimum
                        THEN 1
                    END
                ) AS is_warning_stok_minimum
            ")
            ->groupByRaw('
                a.barang_id,
                a.gudang_id
            ')
            ->orderByRaw('
                a.tanggal, a.id
            ')
            ->get();

        return DataTables::of($items)
            ->toJson();
    }
}
