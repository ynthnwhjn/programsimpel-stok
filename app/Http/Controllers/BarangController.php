<?php

namespace App\Http\Controllers;

use App\Models\Mbarang;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            $items = Mbarang::query();

            return DataTables::of($items)
                ->filter(function($query) {
                    if(request()->filled('search.value')) {
                        return $query->where('nama', 'LIKE', '%'. request('search.value') .'%');
                    }

                    return $query;
                })
                ->toJson();
        }

        return view('barang.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('barang.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new Mbarang($request->all());
        $item->save();

        $redirect_to = route('barang.show', $item);

        return response()->json([
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
        $item = Mbarang::query()
            ->find($id);

        return view('barang.form', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Mbarang::query()
            ->find($id);

        return view('barang.form', compact('item'));
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
        $item = Mbarang::query()->find($id);
        $item->update($request->all());

        $redirect_to = route('barang.show', $item);

        return response()->json([
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
