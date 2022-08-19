<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Stock;
use App\Models\Galeri;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use DataTables;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produk = Produk::query();
        if (!empty($request->jenis_jual)) {
            $produk->where('jenis_jual', $request->jenis_jual);
        }
        if (!empty($request->kategori)) {
            $produk->where('kategori_id', $request->kategori);
        }
        $produk = $produk->orderBy('created_at', 'DESC');
        $produk = $produk->get();
        if ($request->ajax()) {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="gambar/' . $row->gambar_utama . '"><img src="gambar/' . $row->gambar_utama . '" alt="Produk ' . $row->nama_produk . '" title="Produk ' . $row->nama_produk . '" class="rounded me-3" height="48" /></a>
            <p class="m-0 d-inline-block align-middle font-16">
            <a href="/produk-detail/' . $row->slug . '" class="text-body">' . $row->nama_produk . '</a>
            </p>';
                })

                ->addColumn('nama_kategori', function ($row) {
                    if (!empty($row->kategori->nama_kategori)) {
                        return $row->kategori->nama_kategori;
                    } else {
                        return '';
                    }
                })
                ->addColumn('harga', function ($row) {
                    $rupiah = "Rp " . number_format($row->harga, 0, ',', '.') . " / " . $row->satuan;
                    return $rupiah;
                })
                ->addColumn('harga_jual', function ($row) {
                    $rupiah = "Rp " . number_format($row->harga_jual, 0, ',', '.') . " / " . $row->satuan;
                    return $rupiah;
                })
                ->addColumn('qr', function ($row) {
                    return QrCode::size(60)->generate(url('produk') . '/' . $row->id);
                })
                ->addColumn('jenis_jual', function ($row) {
                    if ($row->jenis_jual == 1) {
                        return 'Online';
                    } elseif ($row->jenis_jual == 2) {
                        return 'Offline';
                    }
                })
                ->addColumn('action', function ($row) {
                    $starttem = '<div class="dropdown  mb-2">
            <a href="#" class="dropdown-toggle arrow-none btn btn-info btn-sm" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="uil uil-cog"></i> Aksi</a><div class="dropdown-menu dropdown-menu-end" style="">';
                    $btn = "";
                    if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Gambar Produk" class="dropdown-item gambarProduk"> <i class="mdi mdi-plus-circle"></i> Tambah Gambar</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Edit" class="dropdown-item editProduk"> <i class="mdi mdi-square-edit-outline"></i> Ubah</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-status="trash" data-original-title="Delete" class="dropdown-item deleteProduk"> <i class="mdi mdi-delete"></i> Hapus</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Copy" class="dropdown-item copyProduk"> <i class="mdi mdi-content-duplicate"></i> Copy</a>';
                    }

                    $endtem = ' </div></div>';



                    return $starttem . $btn . $endtem;
                })
                ->rawColumns(['kode_produk', 'nama_produk', 'nama_kategori', 'merek', 'satuan', 'harga', 'harga_jual', 'jumlah', 'jenis_jual', 'qr', 'action'])
                ->make(true);
        }

        $kategori = Kategori::orderBy('nama_kategori', 'ASC')->get();
        return view('produk/produk', compact('produk', 'kategori'));
    }


    public function trashTable(Request $request)
    {
        $produk = Produk::orderBy('deleted_at', 'DESC')->onlyTrashed()->get();
        if ($request->ajax()) {
            return Datatables::of($produk)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($row) {
                    return '<a href="gambar/' . $row->gambar_utama . '"><img src="gambar/' . $row->gambar_utama . '" alt="Produk ' . $row->nama_produk . '" title="Produk ' . $row->nama_produk . '" class="rounded me-3" height="48" /></a>
                            <p class="m-0 d-inline-block align-middle font-16">
                            <a href="/produk-detail/' . $row->slug . '" class="text-body">' . $row->nama_produk . '</a>
                            </p>';
                })

                ->addColumn('nama_kategori', function ($row) {
                    if (!empty($row->kategori->nama_kategori)) {
                        return $row->kategori->nama_kategori;
                    } else {
                        return '';
                    }
                })
                ->addColumn('jenis_jual', function ($row) {
                    if ($row->jenis_jual == 1) {
                        return 'Online';
                    } elseif ($row->jenis_jual == 2) {
                        return 'Offline';
                    }
                })
                ->addColumn('harga', function ($row) {
                    $rupiah = "Rp " . number_format($row->harga, 0, ',', '.') . " / " . $row->satuan;
                    return $rupiah;
                })
                ->addColumn('harga_jual', function ($row) {
                    $rupiah = "Rp " . number_format($row->harga_jual, 0, ',', '.') . " / " . $row->satuan;
                    return $rupiah;
                })
                ->addColumn('qr', function ($row) {
                    return QrCode::size(60)->generate(url('produk') . '/' . $row->id);
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-original-title="Restore" class="btn btn-primary restoreProduk"> Kembalikan</a>';
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_produk="' . $row->id . '" data-status="delete" data-original-title="Hapus" class="btn btn-danger deleteProduk"> Hapus</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['kode_produk', 'nama_produk', 'nama_kategori', 'merek', 'satuan', 'harga', 'harga_jual', 'jumlah', 'jenis_jual', 'qr', 'action'])
                ->make(true);
        }
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
        $result = DB::transaction(function () use ($request) {
            $cek = Produk::find($request->id_produk);

            if (empty($request->id_produk)) {
                $jumlah = $request->jumlah;

                $request->validate(
                    [
                        'kode_produk' => 'required|unique:produk,kode_produk',
                        'nama_produk' => 'required|unique:produk,nama_produk',
                        'slug' => 'required|unique:produk,slug',
                        'kategori' => 'required',
                        'satuan' => 'required',
                        'harga' => 'required',
                        'jumlah' => 'required|numeric',
                        'berat' => 'required|numeric',
                        'jenis_jual' => 'required',
                        'deskripsi' => 'required',
                    ],
                    [
                        'kode_produk.unique' => 'Kode Produk Sudah Ada...!!!',
                        'nama_produk.unique' => 'Nama Produk Sudah Ada...!!!',
                        'slug.unique' => 'Slug Produk Sudah Ada...!!!',
                        'jenis_jual.required' => 'Silahkan Pilih Jenis Jual...!!!',
                        'required' => 'Data tidak boleh kosong...!!!',
                        'berat.numeric' => 'Berat Produk harus angka',
                        'jumlah.numeric' => 'Jumlah Produk harus angka',
                    ]
                );

                if ($request->file) {
                    $request->validate([
                        'file' => 'required|file:png,jpeg,jpg|max:1024',
                    ], [
                        'file' => 'Format Gambar di Izinkan png,jpeg,jpg',
                        'max' => 'Maximal ukuran file 1024KB/1MB'
                    ]);
                    $filename = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('gambar'), $filename);
                } else {
                    // return response
                    $response = [
                        'success' => false,
                        'message' => 'Silahkan Upload Gambar.',
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $request->validate(
                    [
                        'kode_produk' => 'required|unique:produk,kode_produk,' . $request->id_produk,
                        'nama_produk' => 'required|unique:produk,nama_produk,' . $request->id_produk,
                        'slug' => 'required|unique:produk,slug,' . $request->id_produk,
                        'kategori' => 'required',
                        'satuan' => 'required',
                        'harga' => 'required',
                        'jumlah' => 'required|numeric',
                        'berat' => 'required|numeric',
                        'jenis_jual' => 'required',
                        'deskripsi' => 'required'
                    ],
                    [
                        'kode_produk.unique' => 'Kode Produk Sudah Ada...!!!',
                        'nama_produk.unique' => 'Nama Produk Sudah Ada...!!!',
                        'slug.unique' => 'Slug Produk Sudah Ada...!!!',
                        'jenis_jual.required' => 'Silahkan Pilih Jenis Jual...!!!',
                        'required' => 'Data tidak boleh kosong...!!!',
                        'berat.numeric' => 'Berat Produk harus angka',
                        'jumlah.numeric' => 'Jumlah Produk harus angka',
                    ]
                );
                $jumlah = $cek->jumlah;
                if ($request->file) {
                    $request->validate([
                        'file' => 'required|file:png,jpeg,jpg|max:1024',
                    ], [
                        'file' => 'Format Gambar di Izinkan png,jpeg,jpg',
                        'max' => 'Maximal ukuran file 1024KB'
                    ]);
                    $filename = time() . '.' . $request->file->extension();
                    $request->file->move(public_path('gambar'), $filename);
                } else {
                    $filename = $cek->gambar_utama;
                }
            }




            $harga = str_replace('.', '', $request->harga);
            $harga_jual = 0;

            if ($request->jenis_jual == 1) {
                $request->validate(
                    [
                        'harga_jual' => 'required',
                    ],
                    [
                        'required' => 'Silahkan Isi Harga Jual...!!!',
                    ]
                );

                $harga_jual = str_replace('.', '', $request->harga_jual);
                if ($harga_jual <= $harga) {
                    // dd($harga);
                    $response = [
                        'success' => false,
                        'message' => 'Harga Jual Harus Lebih Besar dari Harga Modal.',
                    ];
                    return $response;
                }
            }


            $slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->slug));
            $query = Produk::updateOrCreate([
                'id' => $request->id_produk
            ], [
                'kode_produk' => $request->kode_produk,
                'nama_produk' => $request->nama_produk,
                'slug' => $slug,
                'kategori_id' => $request->kategori,
                'merek' => $request->merek,
                'satuan' => $request->satuan,
                'harga' => $harga,
                'harga_jual' => $harga_jual,
                'jumlah' => $jumlah,
                'berat' => $request->berat,
                'jenis_jual' => $request->jenis_jual,
                'deskripsi' => $request->deskripsi,
                'gambar_utama' => $filename,
            ]);

            if (empty($request->id_produk)) {
                //Start History Stock
                $stock = Stock::create([
                    'produk_id' => $query->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'harga_jual' => $harga_jual,
                    'status' => 2,
                    'deskripsi' => '',
                ]);
                //End History Stock
            }

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
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Produk::where('slug', $id)->with('stock')->first();
        $stock = $produk->stock;
        $galeri = Galeri::where('data_id', $produk->id)->get();
        // dd($produk);
        if (auth()->user() <> null) {
            return view('produk/produkDetail', compact('produk', 'galeri', 'stock'));
        } else {
            $dilihat = $produk->dilihat + 1;
            $produk->dilihat = $dilihat;
            $produk->save();

            $produkPage = true;
            return view('website/produkDetail', compact('produk', 'galeri', 'produkPage'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produk = Produk::find($id);
        return response()->json($produk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        DB::transaction(function () use ($id) {
            Produk::withTrashed()->where('id', $id)->restore();
            Stock::withTrashed()->where('produk_id', $id)->restore();
        });
        $response = [
            'success' => true,
            'message' => 'Berhasil Dikembalikan.',
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function trash($id)
    {
        DB::transaction(function () use ($id) {
            Produk::find($id)->delete();
            Stock::where('produk_id', $id)->delete();
        });
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            Produk::where('id', $id)->withTrashed()->forceDelete();
            Stock::where('produk_id', $id)->withTrashed()->forceDelete();
        });
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    // public function updateStock(Request $request)
    // {
    //     $produk = Produk::find($request->produkId);
    //     $stock = $produk->jumlah;
    //     $tambah = $request->jumlahStock;
    //     $stockBaru = $stock + $tambah;
    //     $produk->update([
    //         'jumlah' => $stockBaru
    //     ]);
    //     if ($produk->id) {
    //         //Start History Stock
    //         $stock = Stock::create([
    //             'produk_id' => $produk->id,
    //             'jumlah' => $request->jumlahStock,
    //             'status' => 2,
    //             'deskripsi' => '',
    //         ]);
    //         //End History Stock
    //     }
    //     $response = [
    //         'success' => true,
    //         'message' => 'Berhasil Update Stock.',
    //     ];
    //     return response()->json($response, 200);
    // }

    public function copyProduk(Request $request)
    {
        DB::transaction(function () use ($request) {
            $cek = Produk::find($request->id);
            $new = $cek->replicate()->fill([
                'kode_produk' => $cek->kode_produk . '-copy',
                'nama_produk' => $cek->nama_produk . '-copy',
                'slug' => $cek->slug . '-copy',
                'jumlah' => 1,
            ]);
            $new->save();
            $id = $new->id;

            //Start History Stock
            $stock = Stock::create([
                'produk_id' => $id,
                'jumlah' => 1,
                'harga' => $cek->harga,
                'harga_jual' => $cek->harga_jual,
                'status' => 2,
                'deskripsi' => '',
            ]);
            //End History Stock

        });
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }

    public function produkPage(Request $request)
    {
        $produks = Produk::orderBy('created_at', 'DESC')->paginate(20);
        $kategori = Kategori::orderBy('nama_kategori', 'ASC')->get();
        $produkSlider = Galeri::where('jenis', 'produkSlider')->get();
        $page = 'produk-page';
        $produk = '';
        if ($request->ajax()) {
            foreach ($produks as $result) {
                $btn = '';
                if ($result->jenis_jual == 1) {
                    $btn = '<a 
                    href="/cart-add/' . $result->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                } elseif ($result->jenis_jual == 2) {
                    $btn = '<a 
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $result->kode_produk . '%20nama%20produk%20' . $result->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i> 
                    Pesan
                    Sekarang</a>';
                }
                $harga='';
                if($result->jenis_jual==1){
                    $harga="Rp " . number_format($result->harga_jual, 0, ',', '.');
                }
                $produk .= ' <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
                    <a style="text-decoration: none;" href="/produk-detail/' . $result->slug . '">


                        <img src="/gambar/' . $result->gambar_utama . '" class="img-fluid"
                            style="padding: 0; max-height:200px; min-height:200px; max-width:100%;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $result->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $result->kode_produk . '<br>
                    </small>
                    '.$harga.'<br>'. $btn . '
                </div>
            </div>';
            }
            return $produk;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'page'));
    }

    public function produkKategori(Request $request, $id)
    {

        $cek = Kategori::where('slug', $id)->first();
        $produks = Produk::where('kategori_id', $cek->id)->orderBy('created_at', 'DESC');
        $total = $produks->count();
        $produks = $produks->paginate(20);
        $kategori = Kategori::orderBy('nama_kategori', 'ASC')->get();
        $produkSlider = Galeri::where('jenis', 'produkSlider')->get();
        $page = 'produk-kategori/' . $id;
        $produk = '';
        if ($request->ajax()) {
            foreach ($produks as $result) {
                $btn = '';
                if ($result->jenis_jual == 1) {
                    $btn = '<a 
                    href="/cart-add/' . $result->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                } elseif ($result->jenis_jual == 2) {
                    $btn = '<a 
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $result->kode_produk . '%20nama%20produk%20' . $result->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i> 
                    Pesan
                    Sekarang</a>';
                }
                $harga='';
                if($result->jenis_jual==1){
                    $harga="Rp " . number_format($result->harga_jual, 0, ',', '.');
                }
                $produk .= ' <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
                    <a style="text-decoration: none;" href="/produk-detail/' . $result->slug . '">


                        <img src="/gambar/' . $result->gambar_utama . '" class="img-fluid"
                            style="padding: 0;  max-height:200px; min-height:200px; max-width:100%;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $result->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $result->kode_produk . '<br>
                    </small>
                    '.$harga.'<br>' . $btn . '
                </div>
            </div>';
            }
            return $produk;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'total', 'page'));
    }

    public function produkCari(Request $request)
    {
        $produks = Produk::where('nama_produk', 'LIKE', '%' . $request->id . '%');
        $total = $produks->count();
        $produks = $produks->paginate(20);
        $kategori = Kategori::orderBy('nama_kategori', 'ASC')->get();
        $produkSlider = Galeri::where('jenis', 'produkSlider')->get();
        $searchValue = $request->id;
        $page = 'produk-cari/' . $request->id;
        $produk = '';
        if ($request->ajax()) {
            foreach ($produks as $result) {
                $btn = '';
                if ($result->jenis_jual == 1) {
                    $btn = '<a 
                    href="/cart-add/' . $result->id . '"
                    class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                } elseif ($result->jenis_jual == 2) {
                    $btn = '<a 
                    href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $result->kode_produk . '%20nama%20produk%20' . $result->nama_produk . '"
                    class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i> 
                    Pesan
                    Sekarang</a>';
                }
                $harga='';
                if($result->jenis_jual==1){
                    $harga="Rp " . number_format($result->harga_jual, 0, ',', '.');
                }
                $produk .= ' <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 aos-init" data-aos="zoom-in"
                data-aos-delay="20">
                <div class="box" style="padding-top: 15px; padding-bottom: 15px;">
                    <a style="text-decoration: none;" href="/produk-detail/' . $result->slug . '">


                        <img src="/gambar/' . $result->gambar_utama . '" class="img-fluid"
                            style="padding: 0;  max-height:200px; min-height:200px; max-width:100%;" alt="" />
                        <h3
                            style="color: #2b2a2a; margin-bottom: 5px; font-size: 12px; font-weight:unset; margin-top:10px;">
                            ' . $result->nama_produk . '</h3>
                    </a>
                    <small style="color: #919191; margin-bottom: 5px; font-size: 12px; font-weight:unset">
                        Kode : ' . $result->kode_produk . '<br>
                    </small>
                    '.$harga.'<br>' . $btn . '
                        
                </div>
            </div>';
            }
            return $produk;
        }
        return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'total', 'page', 'searchValue'));
    }

    // public function produkTerlaris(Request $request)
    // {
    //     $produks = Produk::where('nama_produk', 'LIKE', '%' . $request->nama . '%')->where('jenis_jual', '1');
    //     $total = $produks->count();
    //     $produks = $produks->paginate(20);
    //     $kategori = Kategori::orderBy('nama_kategori', 'ASC')->get();
    //     $produkSlider = Galeri::where('jenis', 'produkSlider')->get();
    //     return view('website.produk-page', compact('produks', 'kategori', 'produkSlider', 'total'));
    // }




    public function getGambarProduk($id)
    {
        $galeri = Galeri::where('data_id', $id)->where('jenis', 'produk')->get();
        foreach ($galeri as $galeri) {
            echo '<div class="col-lg-4 col-md-4 col-4 mb-2">
            <a href="/galeriImage/' . $galeri->gambar . '" class="">
                <img class="img-fluid img-thumbnail" src="/galeriImage/' . $galeri->gambar . '" alt="">
            </a>
            <button data-id_produk="' . $galeri->data_id . '" data-id_gambar="' . $galeri->id . '" class="btn-danger mt-1 deleteGaleri"><i class="mdi mdi-delete"></i> Hapus</button>
        </div>';
        }
    }

    public function simpanGambar(Request $request)
    {


        $request->validate([
            'file.*' => 'required|mimes:png,jpeg,jpg,JPEG,JPG|max:1024',
        ], [
            'max' => 'Maximal ukuran file 1024KB/1MB',
            'mimes' => 'Format Gambar di Izinkan png,jpeg,jpg',

        ]);



        $cek = Galeri::where('data_id', $request->dataId)->count();
        $jml = count($request->file('file'));
        $total = $cek + $jml;
        if ($total <= 3) {

            if ($request->hasfile('file')) {

                $images = $request->file('file');

                foreach ($images as $image) {
                    $fileName = time() . '-' . $image->getClientOriginalName();
                    $image->move(public_path('galeriImage'), $fileName);

                    $galeri = new Galeri();
                    $galeri->gambar = $fileName;
                    $galeri->data_id = $request->dataId;
                    $galeri->jenis = 'produk';
                    $galeri->deskripsi = '';
                    $galeri->save();
                }
            }
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Maksimal Hanya 3 Gambar / Produk.',
            ];
            return response()->json($response, 200);
        }
    }
}
