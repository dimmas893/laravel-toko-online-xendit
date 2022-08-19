<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stock = Stock::orderBy('created_at', 'DESC')->withTrashed(false)->get();
        if ($request->ajax()) {
            if ($stock) {
                return Datatables::of($stock)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($row) {
                        return $row->produk->nama_produk;
                    })
                    ->addColumn('jenis_jual', function ($row) {
                        $jenis = $row->produk->jenis_jual;
                        if ($jenis == 1) {
                            $jenis = '<span class="text-success">Online</span>';
                        } elseif ($jenis == 2) {
                            $jenis = '<span class="text-default">Offline</span>';
                        }
                        return $jenis;
                    })
                    ->addColumn('nama_kategori', function ($row) {
                        return $row->produk->kategori->nama_kategori;
                    })
                    ->addColumn('harga', function ($row) {
                        $harga = (float)$row->harga;
                        $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                        return $rupiah;
                    })
                    ->addColumn('harga_jual', function ($row) {
                        $harga = (float)$row->harga_jual;
                        $rupiah = "Rp " . number_format($harga, 0, ',', '.');
                        return $rupiah;
                    })
                    ->addColumn('status', function ($row) {
                        $status = '';
                        if ($row->status == 1) {
                            $status = '<span class="text-success">Tambah Produk</span>';
                        } elseif ($row->status == 2) {
                            $status = '<span class="text-primary">Tambah Stock</span>';
                        } elseif ($row->status == 3) {
                            $status = '<span class="text-info">Jual</span>';
                        } elseif ($row->status == 4) {
                            $status = '<span class="text-warning">Pengurangan</span>';
                        } elseif ($row->status == 5) {
                            $status = '<span class="text-danger">Rusak</span>';
                        }
                        return $status;
                    })
                    ->addColumn('tanggal', function ($row) {
                        return date('d-m-Y', strtotime($row->created_at));
                    })
                    // ->addColumn('action', function ($row) {
                    //     $btn="";
                    //    // if (session('level')==1){
                    //     $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editStock"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    //     $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_stock="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteStock"> <i class="mdi mdi-delete"></i></a>';
                    //     //}

                    //     return $btn;
                    // })
                    ->rawColumns(['jenis_jual', 'nama_kategori', 'tanggal', 'status', 'harga', 'harga_jual'])
                    ->make(true);
            } else {
                return [];
            }
        }
        return view('produk/stock', compact('stock'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'produkId' => 'required',
            'jumlahStock' => 'required|numeric',
            'statusStock' => 'required',
            'harga' => 'required',
        ], [
            'required' => 'Semua data harus di isi!',
            'numeric' => 'Jumlah harus angka!'
        ]);

        $result = DB::transaction(function () use ($request) {

            $cek = Produk::find($request->produkId);

            $stock = $cek->jumlah;
            if ($request->statusStock == '2') {
                $jumlah = $request->jumlahStock;
                $stock = $stock + $jumlah;
            } elseif ($request->statusStock == '4' or $request->statusStock == '5') {
                $jumlah = $request->jumlahStock;
                $stock = $stock - $jumlah;
            }

            $deskripsi = $request->deskripsi;


            $harga = str_replace('.', '', $request->harga);
            $harga_jual = str_replace('.', '', $request->harga_jual);


            $jenis_jual = $cek->jenis_jual;
            if ($jenis_jual == 1) {
                if (empty($request->harga_jual) or $harga_jual < $harga) {
                    $response = [
                        'success' => false,
                        'message' => 'Harga Jual harus lebih besar dari Harga Modal.',
                    ];
                    return $response;
                }
            }

            $cek->update([
                'jumlah' => $stock,
                'harga' => $harga,
                'harga_jual' => $harga_jual,
            ]);

            Stock::create([
                'produk_id' => $request->produkId,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'harga_jual' => $harga_jual,
                'status' => $request->statusStock,
                'deskripsi' => $deskripsi,
            ]);
            // return response
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return $response;
        });

        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock::find($id);
        return response()->json($stock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Stock::destroy($id);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }



    public function cariProduk($produk)
    {
        $produk = Produk::where('nama_produk', 'LIKE', '%' . $produk . '%');
        $jumlah = $produk->count();
        $hasil = $produk->get();
        echo ' <div class="dropdown-header noti-title">
        <h5 class="text-overflow mb-2">Ditemukan <span class="text-danger">' . $jumlah . '</span> hasil</h5>
        </div>';
        foreach ($hasil as $value) {
            echo ' <a href="javascript:void(0);" id="addStock" data-id="' . $value->id . '" class="dropdown-item notify-item">
            <div class="d-flex">
            <img class="d-flex me-2 rounded-circle" src="gambar/' . $value->gambar_utama . '" alt="' . $value->nama_produk . '" height="32">
            <div class="w-100">
            <h5 class="m-0 font-14">' . $value->nama_produk . '</h5>
            <span class="font-12 mb-0">' . $value->kategori->nama_kategori . '</span>
            </div>
            </div>
            </a>';
        }
        // return response()->json($produk, 200);   
    }

    public function getProduk($produk)
    {
        $produk = Produk::find($produk);
        return response()->json($produk, 200);
    }
}
