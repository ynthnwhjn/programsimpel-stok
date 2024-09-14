<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class BrowseSearchController extends Controller
{
    public function gudang()
    {
        $items = DB::table('mgudang', 'a')
            ->where(function(Builder $q) {
                if(request()->filled('keyword')) {
                    $q->where('a.nama', 'LIKE', '%'. request('keyword') .'%');
                }
            })
            ->get()
            ->map(function($item, $key) {
                return [
                    'label' => $item->nama,
                    'value' => $item,
                ];
            });

        return response()->json([
            'items' => $items,
            '_all' => request()->all(),
        ]);
    }

    public function barang()
    {
        $items = DB::table('mbarang', 'a')
            ->where(function(Builder $q) {
                if(request()->filled('keyword')) {
                    $q->where('a.nama', 'LIKE', '%'. request('keyword') .'%');
                }
            })
            ->get()
            ->map(function($item, $key) {
                return [
                    'label' => $item->nama,
                    'value' => $item,
                ];
            });

        return response()->json([
            'items' => $items,
            '_all' => request()->all(),
        ]);
    }
}
