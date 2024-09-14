<?php

namespace App\Http\Controllers;

use App\Models\Rpstokbarang;
use App\Models\Tstokmutasid;
use App\Models\Tstokmutasih;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenerimaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $items = Tstokmutasih::query()
                ->where('jenis', 'penerimaan barang')
                ->with([
                    'gudangTujuan'
                ])
                ->orderByRaw('
                    DATE(tanggal) DESC,
                    kode DESC
                ');

            return DataTables::of($items)->make();
        }

        return view('penerimaan_barang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('penerimaan_barang.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tanggal = Carbon::parse($request->input('tanggal'))->toDateString();
        $prefix = 'BTB/' . Carbon::parse($request->input('tanggal'))->format('y/m/');
        $kode = Tstokmutasih::generateKode($prefix);

        $request->merge([
            'jenis' => 'Penerimaan Barang',
            'kode' => $kode,
            'tanggal' => $tanggal,
        ]);

        $item = new Tstokmutasih($request->all());
        $item->save();

        foreach ($request->input('penerimaan_barang_detail', []) as $row) {
            $penerimaanBarangDetail = new Tstokmutasid($row);
            $item->stokMutasiDetail()->save($penerimaanBarangDetail);

            $stok = new Rpstokbarang([
                'stokmutasih_id' => $item->id,
                'stokmutasid_id' => $penerimaanBarangDetail->id,
                'barang_id' => $row['barang_id'],
                'gudang_id' => $request->input('gudangtujuan_id'),
                'jumlah' => $row['jumlah'],
                'tanggal' => $item->tanggal,
            ]);
            $stok->save();
        }

        $redirect_to = route('penerimaan_barang.show', $item);

        return response()->json([
            '_all' => $request->all(),
            'redirect_to' => $redirect_to,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Tstokmutasih::query()
            ->with([
                'gudangTujuan',
                'stokMutasiDetail.barang',
            ])
            ->find($id);

        return view('penerimaan_barang.form', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Tstokmutasih::query()
            ->with([
                'gudangTujuan',
                'stokMutasiDetail.barang',
            ])
            ->find($id);

        return view('penerimaan_barang.form', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = Tstokmutasih::query()->find($id);
        $item->update($request->all());

        Tstokmutasid::query()
            ->where('stokmutasih_id', $id)
            ->delete();

        Rpstokbarang::query()
            ->where('stokmutasih_id', $id)
            ->delete();

        foreach ($request->input('penerimaan_barang_detail', []) as $row) {
            $penerimaanBarangDetail = new Tstokmutasid($row);
            $item->stokMutasiDetail()->save($penerimaanBarangDetail);

            $stok = new Rpstokbarang([
                'stokmutasih_id' => $item->id,
                'stokmutasid_id' => $penerimaanBarangDetail->id,
                'barang_id' => $row['barang_id'],
                'gudang_id' => $request->input('gudangtujuan_id'),
                'jumlah' => $row['jumlah'],
                'tanggal' => $item->tanggal,
            ]);
            $stok->save();
        }

        $redirect_to = route('penerimaan_barang.show', $item);

        return response()->json([
            '_all' => $request->all(),
            'redirect_to' => $redirect_to,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
