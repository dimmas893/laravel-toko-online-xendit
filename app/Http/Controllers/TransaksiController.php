<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\Kas;
use App\Models\Stock;
use App\Models\Shipping;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Notifikasi;
use App\Models\Village;
use App\Models\Xeninvoice;
use App\Models\DistrictOngkir;
use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Xendit\Xendit;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('updated_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_trans)) {
            $transaksi->where('status_trans', $request->status_trans);
        }
        $transaksi = $transaksi->with('shipping')->with('xeninvoice')->orderBy('created_at', 'DESC');
        $transaksi = $transaksi->get();
        if ($request->ajax()) {
            return Datatables::of($transaksi)
                ->addIndexColumn()
                ->addColumn('kode_trans', function ($row) {
                    if (!empty($row->xeninvoice->xen_id)) {
                        $kode_trans = '<a href="/transaksi/cek/' . $row->kode_trans . '">' . $row->kode_trans . '</a>';
                    } else {
                        $kode_trans = $row->kode_trans;
                    }

                    return $kode_trans;
                })
                ->addColumn('tgl_trans', function ($row) {
                    $tgl = date('d-m-Y', strtotime($row->tgl_trans));
                    return $tgl;
                })
                ->addColumn('nama_pelanggan', function ($row) {
                    $nama_pelanggan = $row->pelanggan->nama_depan . ' ' . $row->pelanggan->nama_belakang;
                    return $nama_pelanggan;
                })
                ->addColumn('perusahaan', function ($row) {
                    $perusahaan = $row->pelanggan->perusahaan;
                    return $perusahaan;
                })
                ->addColumn('subtotal', function ($row) {
                    $rupiah = "Rp " . number_format($row->subtotal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('pph', function ($row) {
                    $rupiah = "Rp " . number_format($row->pph, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('ppn', function ($row) {
                    $rupiah = "Rp " . number_format($row->ppn, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('total', function ($row) {
                    // if ($row->shipping) {
                    //     $biaya = $row->shipping->biaya;
                    // } else {
                    //     $biaya = 0;
                    // }
                    // $total = $row->total + $biaya;
                    $rupiah = "Rp " . number_format($row->total, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('status_trans', function ($row) {
                    if ($row->status_trans == '1') {
                        if ($row->jenis_trans == 1) {
                            return '<span class="badge badge-warning-lighten">Menunggu Pembayaran</span>';
                        } else {
                            return '<span class="badge badge-warning-lighten">Menunggu Verifikasi</span>';
                        }
                    } elseif ($row->status_trans == '2') {
                        return '<span class="badge badge-info-lighten">Proses</span>';
                    } elseif ($row->status_trans == '3') {
                        return '<span class="badge badge-success-lighten">Selesai</span>';
                    } elseif ($row->status_trans == '4') {
                        return '<span class="badge badge-danger-lighten">Dibatalkan</span>';
                    } elseif ($row->status_trans == '5') {
                        return '<span class="badge badge-success-lighten">Dibayar</span>';
                    } elseif ($row->status_trans == '6') {
                        return '<span class="badge badge-primary-lighten">Dikirim</span>';
                    }
                })->addColumn('qr', function ($row) {
                    if ($row->xeninvoice and $row->status_trans<>3 and  $row->status_trans<>4 and  $row->status_trans<>5) {
                        return QrCode::size(80)->generate(url('invoice') . '/' . $row->id);
                    } else {
                        return 'Tidak Tersedia';
                    }
                })->addColumn('deskripsi', function ($row) {
                    return $row->deskripsi;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn2 = '';
                    if (auth()->user()->level == 'CEO') {
                        if ($row->status_trans == '1') {
                            // $btn = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Invoice</a></li>';
                            $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnVerifikasiCeo" data-id_transaksi="' . $row->id . '" >Verifikasi</a></li>';
                        } elseif ($row->status_trans == 6) {
                            // $btn2 = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Invoice</a></li>
                            //         <li>';
                        }
                    } elseif (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        if ($row->status_trans == 1) {
                        }

                        if ($row->status_trans == 2) {
                            $status=1;
                            if($row->xeninvoice){
                                $status=2;
                                //jika sudah buat invoice
                            }else{
                                $status=1;
                                $shipping=1;
                                if($row->shipping){
                                    $shipping=2;
                                }else{
                                    $shipping=1;
                                }
                                $btn = $btn . ' <li><a class="dropdown-item create-invoice" data-shipping="'.$shipping.'" data-id="'.$row->id.'" href="javascript:void(0)">Buat Tagihan</a></li>';    
                            }
                            $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item pengiriman" data-status="'.$status.'" data-id="' . $row->id . '" >Input Resi</a></li>';
                        }

                        if ($row->status_trans == 3) {
                            // $btn2 = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Buat Tagihan</a></li>';
                            // $btn2 = ' <li><a target="_blank" class="dropdown-item" href="/transaksi/create-invoice/' . $row->id . '">Invoice</a></li>
                            //         <li><a target="_blank" class="dropdown-item" href="/tanda-terima/' . $row->id . '">Tanda Terima</a></li>';
                        }

                        if ($row->status_trans == 4) {
                        }

                        if ($row->status_trans == 5) {
                            $status=2;
                            $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item pengiriman" data-status="'.$status.'" data-id="' . $row->id . '" >Input Resi</a></li>';
                        }

                        if ($row->status_trans == 6) {
                            $text='Buat Tagihan';
                            if($row->xeninvoice){
                                $text='Lihat Tagihan';
                                //jika sudah buat invoice
                            }else{
                                $text='Buat Tagihan';
                                $shipping=1;
                                if($row->shipping){
                                    $shipping=2;
                                }else{
                                    $shipping=1;
                                }
                                $btn = $btn . ' <li><a class="dropdown-item create-invoice" data-shipping="'.$shipping.'" data-id="'.$row->id.'" href="javascript:void(0)">'.$text.'</a></li>';    
                            }
                            
                            // $btn2 = '<li><a target="_blank" class="dropdown-item" href="/tanda-terima/' . $row->id . '">Tanda Terima</a></li>';
                        }
                        if($row->status_trans<>3 and $row->status_trans<>4){
                        $btn = $btn . ' <li><a href="javascript:void(0)" class="dropdown-item" id="btnVerifikasi" data-id_transaksi="' . $row->id . '" >Verifikasi</a></li>';
                        }
                    }


                    if ($btn <> '' or $btn2 <> '') {
                        $btn = '<div class="btn-group dropstart">
                                 <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu">
                            ' . $btn . '
                            <li><hr class="dropdown-divider"></li>
                            ' . $btn2 . '
                            </ul>
                            </div>';
                    }




                    return $btn;
                })
                ->rawColumns(['kode_trans', 'tgl_trans', 'nama_pelanggan', 'perusahaan', 'subtotal', 'pph', 'ppn', 'total', 'status_trans', 'deskripsi', 'action'])
                ->make(true);
        }
        $provinces = ProvinceOngkir::all();
        $kode_trans = createCode(2);
        return view('transaksi/transaksi', compact('transaksi', 'provinces', 'kode_trans'));
    }

    public function getKabupaten(Request $request)
    {
        $kabupaten = DB::table('regencies')->select('id', 'name')->where('province_id', $request->id)->get();
        return response()->json($kabupaten);
    }
    public function getKecamatan(Request $request)
    {
        $kecamatan = DB::table('districts')->select('id', 'name')->where('regency_id', $request->id)->get();
        return response()->json($kecamatan);
    }
    public function getDesa(Request $request)
    {
        $desa = DB::table('villages')->select('id', 'name')->where('district_id', $request->id)->get();
        return response()->json($desa);
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
        if (session('cart')) {
            DB::transaction(function () use ($request) {
                if (!empty($request->idPelanggan)) {
                    $pelanggan_id = $request->idPelanggan;
                } else {
                    $request->validate([
                        'tgl' => 'required',
                        'nama_depan' => 'required',
                        'nama_belakang' => 'required',
                        'email' => 'required|email',
                        'telpon' => 'required',
                        'provinsi' => 'required',
                        'kabupaten' => 'required',
                        'kecamatan' => 'required',
                        'kode_pos' => 'required',
                        'alamat' => 'required',
                    ], [
                        'required' => 'Silahkan Lengkapi Data!',
                        'email.email' => 'Email tidak valid!',
                        'tgl.required' => 'Kamu belum memilih Tanggal Transaksi!',
                    ]);
                    $pelanggan = Pelanggan::create([
                        'nama_depan' => $request->nama_depan,
                        'nama_belakang' => $request->nama_belakang,
                        'perusahaan' => $request->perusahaan,
                        'email' => $request->email,
                        'telpon' => $request->telpon,
                        'hp' => $request->telpon,
                        'provinsi_id' => $request->provinsi,
                        'kota_id' => $request->kabupaten,
                        'kecamatan_id' => $request->kecamatan,
                        'pos' => $request->kode_pos,
                        'alamat' => $request->alamat,
                        'logo' => 'customer.png',
                        'status_data' => 1,
                    ]);
                    $pelanggan_id = $pelanggan->id;
                }
                $kode_trans = createCode(2);
                $totalModal = 0;
                $subTotal = 0;
                $total = 0;
                $butuhVerifikasi = website()->trx_verifikasi;
                if ($butuhVerifikasi == 1) {
                    $status_trans = 1;
                    $status_data = 1;
                } else {
                    $status_trans = 2;
                    $status_data = 2;
                }
                $totalBiaya = 0;
                foreach (session('cart') as $item) {
                    $id = $item['id'];
                    $quantity = $item['quantity'];
                    $biaya = $item['biaya'];
                    $harga_jual = $item['harga_jual'];
                    $total = ($item['harga_jual']) * $item['quantity'];
                    $modal = $item['price'] * $item['quantity'];
                    $totalModal += $modal;
                    $jumlahBiaya = $item['biaya'] * $item['quantity'];
                    $totalBiaya += $jumlahBiaya;

                    $subTotal += $total;
                    $order = Order::create([
                        'produk_id' => $id,
                        'trans_kode' => $kode_trans,
                        'biaya' => $biaya,
                        'jumlah' => $quantity,
                        'harga_jual' => $harga_jual,
                        'total' => $total,
                        'status_data' => $status_data,
                    ]);
                    if ($order->id) {
                        if ($status_trans == 3) {
                            $produk = Produk::find($id);
                            $stok = $produk->jumlah;
                            $sisa = $stok - $quantity;
                            $produk->update([
                                'jumlah' => $sisa
                            ]);
                        }
                        // $stock = Stock::create([
                        //     'produk_id' => $id,
                        //     'jumlah' => $quantity,
                        //     'harga' => $modal,
                        //     'harga_jual' => $harga_jual,
                        //     'status' => 3,
                        //     'ref_id' => $kode_trans,
                        //     'deskripsi' => '',
                        //     'status_data' => $status_data,
                        // ]);
                    }
                }

                if (session()->has('ppn')) {
                    $ppn = ($subTotal / 100) * website()->trx_ppn;
                } else {
                    $ppn = 0;
                }
                if (session()->has('pph')) {
                    $pph = ($totalBiaya / 100) * website()->trx_pph;
                } else {
                    $pph = 0;
                }

                $grandTotal = ($subTotal + $ppn) - $pph;


                Transaksi::create([
                    'kode_trans' => $kode_trans,
                    'tgl_trans' => $request->tgl,
                    'pelanggan_id' => $pelanggan_id,
                    'totalModal' => $totalModal,
                    'totalBiaya' => $totalBiaya,
                    'subTotal' => $subTotal,
                    'ppn' => $ppn,
                    'pph' => $pph,
                    'total' => $grandTotal,
                    'deskripsi' => $request->deskripsi,
                    'status_trans' => $status_trans,
                    'jenis_trans' => 2
                ]);

                if ($status_trans == 1) {
                    $cekUser = User::whereLevel('CEO')->get();

                    foreach ($cekUser as $cekUser) {
                        $judul = 'Transaksi Baru';
                        $body = $kode_trans . " Rp." . $grandTotal;
                        $token_1 = $cekUser->userToken;
                        $comment = new Notifikasi();
                        $comment->toId = '';
                        $comment->toLevel = $cekUser->level;
                        $comment->title = $judul;
                        $comment->body = $body;
                        $comment->image = '';
                        $comment->url = url('/transaksi');
                        $comment->status = 0;
                        if (!empty($cekUser->userToken)) {
                            sendNotif($token_1, $comment);
                        }
                        $comment->save();
                    }
                }

                session()->forget('cart');
                session()->forget('ppn');
                session()->forget('pph');
            });
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Transaksi Tidak Ditemukan.',
            ];

            return response()->json($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaksi = Transaksi::find($id);
        return response()->json($transaksi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Transaksi::destroy($id);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function getProduk($produk)
    {
        $produk = Produk::where('jenis_jual', 1)->where('nama_produk', 'LIKE', '%' . $produk . '%');
        $jumlah = $produk->count();
        $hasil = $produk->get();
        echo ' <div class="dropdown-header noti-title">
        <h5 class="text-overflow mb-2">Ditemukan <span class="text-danger">' . $jumlah . '</span> hasil</h5>
        </div>';
        foreach ($hasil as $value) {
            echo ' <a href="javascript:void(0);" id="addToCart" data-id="' . $value->id . '" class="dropdown-item">
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


    public function addToCart($id)
    {
        $produk = Produk::find($id);
        if ($produk->jumlah < 1) {
            $response = [
                'success' => false,
                'message' => 'Stock tidak Cukup...!!!',
            ];
            return response()->json($response, 200);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    "id" => $produk->id,
                    "name" => $produk->nama_produk,
                    "quantity" => 1,
                    "price" => $produk->harga,
                    "biaya" => 0,
                    "harga_jual" => 0,
                    "image" => $produk->gambar_utama
                ];
            }
            session()->put('cart', $cart);

            $this->removePpn();
            $this->removePph();

            $response = [
                'success' => true,
                'message' => 'Berhasil Ditambahkan.',
            ];
            return response()->json($response, 200);
        }
    }

    public function getCartTable()
    {
        return view('transaksi/tabelCart');
    }

    public function enablePpn()
    {
        session()->put('ppn', 'true');
        $response = [
            'success' => true,
            'message' => 'PPN ADD',
        ];
        return response()->json($response, 200);
    }

    public function removePpn()
    {
        session()->forget('ppn');
        $response = [
            'success' => true,
            'message' => 'PPN Remove.',
        ];
        return response()->json($response, 200);
    }

    public function enablePph()
    {
        session()->put('pph', 'true');
        $response = [
            'success' => true,
            'message' => 'PPH ADD',
        ];
        return response()->json($response, 200);
    }

    public function removePph()
    {
        session()->forget('pph');
        $response = [
            'success' => true,
            'message' => 'PPH Remove.',
        ];
        return response()->json($response, 200);
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->jumlah) {
            $produk = Produk::find($request->id);
            if ($produk->jumlah < $request->jumlah) {
                $response = [
                    'success' => false,
                    'message' => 'Stock tidak Cukup, Sisa Stock ' . $produk->jumlah,
                ];
                return response()->json($response, 200);
            } else {
                $cart = session()->get('cart');

                $cart[$request->id]["quantity"] = $request->jumlah;

                session()->put('cart', $cart);
                $response = [
                    'success' => true,
                    'message' => 'Berhasil Diperbaharui.',
                ];
                return response()->json($response, 200);
            }
        }
    }


    public function updateCartBiaya(Request $request)
    {
        if ($request->id && $request->modal) {

            $cart = session()->get('cart');
            $biaya = str_replace('.', '', $request->biaya);
            $modal = $request->modal;
            $cart[$request->id]["biaya"] = $biaya;
            $persen = (($modal + $biaya) / 100) * website()->trx_markup;
            $hargaJual = $modal + $biaya + $persen;
            $cart[$request->id]["harga_jual"] = $hargaJual;
            session()->put('cart', $cart);
            $this->removePpn();
            $this->removePph();
            $response = [
                'success' => true,
                'message' => 'Berhasil Diperbaharui.',
            ];
            return response()->json($response, 200);
        }
    }

    public function updateCartHargaJual(Request $request)
    {
        if ($request->id && $request->harga_jual) {

            $cart = session()->get('cart');
            $harga = str_replace('.', '', $request->harga_jual);
            $cart[$request->id]["harga_jual"] = $harga;
            session()->put('cart', $cart);
            $this->removePpn();
            $this->removePph();
            $response = [
                'success' => true,
                'message' => 'Berhasil Diperbaharui.',
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Gagal Diperbaharui.',
        ];
        return response()->json($response, 200);
    }

    public function removeCart(Request $request)
    {

        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
            return response()->json($response, 200);
        }

        // \Cart::remove($request->id);


    }




    public function verifikasiTrans(Request $request)
    {
        if ($request->id == 1) {
            DB::transaction(function () use ($request) {
                //Selesai
                $transaksi = Transaksi::find($request->verifikasiId);
                $transaksi->update([
                    'status_trans' => '3',
                    'deskripsi' => $transaksi->deskripsi . '<br>' . auth()->user()->name . '<br>' . $request->deskripsi . '<br>',
                ]);
                $total = $transaksi->total;
                $cek = Kas::where('jenis', '2')->orderBy('created_at', 'desc')->first();
                if (!empty($cek->nominal)) {
                    $saldo = $cek->nominal;
                    $saldo = $saldo + $total;
                    Kas::Create([
                        'tgl' => date('Y-m-d'),
                        'jenis' => '2',
                        'debit' => 0,
                        'kredit' => $total,
                        'nominal' => $saldo,
                        'sumber' => 'Transaksi',
                        'deskripsi' => 'Transaksi Selesai',
                    ]);

                    $cekUser = User::whereLevel('CEO')->get();

                    foreach ($cekUser as $cekUser) {
                        $judul = 'Transaksi Selesai';
                        $body = $transaksi->kode_trans . " Rp." . $total;
                        $token_1 = $cekUser->userToken;
                        $comment = new Notifikasi();
                        $comment->toId = '';
                        $comment->toLevel = $cekUser->level;
                        $comment->title = $judul;
                        $comment->body = $body;
                        $comment->image = '';
                        $comment->url = url('/transaksi');
                        $comment->status = 0;
                        if (!empty($cekUser->userToken)) {
                            sendNotif($token_1, $comment);
                        }
                        $comment->save();
                    }

                    $response = [
                        'success' => true,
                        'message' => 'Transaksi Selesai.',
                    ];
                    return response()->json($response, 200);
                } else {
                    $saldo = 0;
                    $saldo = $saldo + $total;
                    Kas::Create([

                        'tgl' => date('Y-m-d'),
                        'jenis' => '2',
                        'debit' => 0,
                        'kredit' => $total,
                        'nominal' => $saldo,
                        'sumber' => 'Transaksi',
                        'deskripsi' => 'Transaksi Selesai',
                    ]);

                    $cekUser = User::whereLevel('CEO')->get();

                    foreach ($cekUser as $cekUser) {
                        $judul = 'Transaksi Selesai';
                        $body = $transaksi->kode_trans . " Rp." . $total;
                        $token_1 = $cekUser->userToken;
                        $comment = new Notifikasi();
                        $comment->toId = '';
                        $comment->toLevel = $cekUser->level;
                        $comment->title = $judul;
                        $comment->body = $body;
                        $comment->image = '';
                        $comment->url = url('/transaksi');
                        $comment->status = 0;
                        if (!empty($cekUser->userToken)) {
                            sendNotif($token_1, $comment);
                        }
                        $comment->save();
                    }


                    $response = [
                        'success' => true,
                        'message' => 'Transaksi Selesai.',
                    ];
                    return response()->json($response, 200);
                }
            });
            $response = [
                'success' => true,
                'message' => 'Transaksi Selesai.',
            ];
            return response()->json($response, 200);
        } elseif ($request->id == 2) {
            //Tolak
            $transaksi = Transaksi::find($request->verifikasiId);
            $transaksi->update([
                'status_trans' => '4',
                'deskripsi' => $transaksi->deskripsi . '<br>' . auth()->user()->name . '<br>' . $request->deskripsi . '<br>',
            ]);

            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi Ditolak / Dibatalkan';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }

            $cekUser = User::whereLevel('CEO')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi Ditolak / Dibatalkan';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }


            $response = [
                'success' => true,
                'message' => 'Telah Dibatalkan.',
            ];
            return response()->json($response, 200);
        } elseif ($request->id == 3) {
            //Proses CEO
            $transaksi = Transaksi::find($request->verifikasiIdCeo);
            $transaksi->update([
                'status_trans' => '2',
                'deskripsi' => $transaksi->deskripsi . '<br>' . auth()->user()->name . '<br>' . $request->deskripsiCeo . '<br>',
            ]);

            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi telah Diproses';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }


            $response = [
                'success' => true,
                'message' => 'Transaksi Proses.',
            ];
            return response()->json($response, 200);
        }
    }

    public function invoice(Request $request)
    {
        $transaksi = Transaksi::find($request->id);
        if ($transaksi->status_trans > 1) {
            $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
            $provinsi = Province::find($pelanggan->provinsi_id);
            $kabupaten = Regency::find($pelanggan->kota_id);
            $kecamatan = District::find($pelanggan->kecamatan_id);
            $desa = Village::find($pelanggan->desa_id);
            $order = Order::where('trans_kode', $transaksi->kode_trans)->get();
            return view('transaksi.invoice', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'pelanggan'));
        } else {
            return 'Transaksi belum diproses';
        }
    }

    public function getInvoice(Request $request)
    {
        $transaksi = Transaksi::where('kode_trans', $request->id)->with('xeninvoice')->first();
        if (!$transaksi) {
            return "Transaksi Tidak Ditemukan";
        }
        if ($transaksi->jenis_trans == 2 and $transaksi->status_trans == 1 and website()->trx_verifikasi == 1) {
            return 'Transaksi Menunggu Diproses';
        }
        $xen = array();
        if ($transaksi->xeninvoice) {
            $xen = new XenditController();
            $xen = $xen->getInvoice($transaksi->xeninvoice->xen_id);
        }
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $provinsi = ProvinceOngkir::find($pelanggan->provinsi_id);
        $kabupaten = RegencyOngkir::find($pelanggan->kota_id);
        $kecamatan = DistrictOngkir::find($pelanggan->kecamatan_id);
        $order = Order::where('trans_kode', $transaksi->kode_trans)->get();
        $shipping = Shipping::where('transaksi_id', $transaksi->id)->first();
        return view('transaksi.getInvoice', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'pelanggan', 'xen', 'shipping'));
    }


    public function vCekTransaksi()
    {
        return view('website.cekTransaksi');
    }


    public function invoicePrint(Request $request)
    {
        $transaksi = Transaksi::find($request->id);
        if ($transaksi->status_trans > 1) {
            $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
            $provinsi = Province::find($pelanggan->provinsi_id);
            $kabupaten = Regency::find($pelanggan->kota_id);
            $kecamatan = District::find($pelanggan->kecamatan_id);
            $desa = Village::find($pelanggan->desa_id);
            $order = Order::where('trans_kode', $transaksi->kode_trans)->get();
            return view('transaksi.invoicePrint', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'pelanggan'));
        } else {
            return 'Transaksi belum diproses';
        }
    }

    public function tandaTerima(Request $request)
    {
        $transaksi = Transaksi::find($request->id);
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $provinsi = Province::find($pelanggan->provinsi_id);
        $kabupaten = Regency::find($pelanggan->kota_id);
        $kecamatan = District::find($pelanggan->kecamatan_id);
        $desa = Village::find($pelanggan->desa_id);
        $order = Order::where('trans_kode', $transaksi->kode_trans)->get();
        return view('transaksi.tandaTerima', compact('transaksi', 'order', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'pelanggan'));
    }

    public function listPelanggan()
    {
        $pelanggan = Pelanggan::orderBy('nama_depan', 'asc')->get();
        foreach ($pelanggan as $value) {
            echo ' <a href="javascript:void(0);" id="idPelanggan" data-id="' . $value->id . '" class="dropdown-item notify-item">
            <div class="d-flex mt-2">
            <img class="d-flex me-2 rounded-circle" src="icon/customer.png" alt="' . $value->nama_depan . ' ' . $value->nama_belakang . '" height="32">
            
            <div class="w-100">
            <h5 class="m-0 font-14">' . $value->nama_depan . ' ' . $value->nama_belakang . '</h5>
            <span class="font-12 mb-0">' . $value->perusahaan . '</span>
            </div>
            </div>
            </a>';
        }
        // return response()->json($produk, 200);   
    }

    public function cariPelanggan(Request $request)
    {
        $pelanggan = Pelanggan::where('nama_depan', 'LIKE', '%' . $request->name . '%')->orWhere('nama_belakang', 'LIKE', '%' . $request->name . '%')->orWhere('perusahaan', 'LIKE', '%' . $request->name . '%')->get();
        foreach ($pelanggan as $value) {
            echo ' <a href="javascript:void(0);" id="idPelanggan" data-id="' . $value->id . '" class="dropdown-item notify-item">
            <div class="d-flex mt-2">
            <img class="d-flex me-2 rounded-circle" src="icon/customer.png" alt="' . $value->nama_depan . ' ' . $value->nama_belakang . '" height="32">
            <div class="w-100">
            <h5 class="m-0 font-14">' . $value->nama_depan . ' ' . $value->nama_belakang . '</h5>
            <span class="font-12 mb-0">' . $value->perusahaan . '</span>
            </div>
            </div>
            </a>';
        }
        // return response()->json($produk, 200);   
    }

    public function apiDataTransaksi($id)
    {
        $transaksi = Transaksi::orderBy('created_at', 'desc');
        $transaksi->with('pelanggan');
        $transaksi->where('status_trans', $id);
        // $transaksi->select('transaksi.*','pelanggan.nama_lengkap');
        $transaksi = $transaksi->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data transaksi', 'token_type' => 'Bearer', 'data' => $transaksi]);
    }

    public function apiVerifikasiTransaksi(Request $request)
    {
        if ($request->status_trans == 3) {
            DB::transaction(function () use ($request) {
                //Selesai
                $transaksi = Transaksi::find($request->id);
                $transaksi->update([
                    'status_trans' => '3',
                    'deskripsi' => '',
                ]);
                $total = $transaksi->total;
                $cek = Kas::where('jenis', '2')->orderBy('created_at', 'desc')->first();
                if (!empty($cek->nominal)) {
                    $saldo = $cek->nominal;
                    $saldo = $saldo + $total;
                    Kas::Create([
                        'tgl' => date('Y-m-d'),
                        'jenis' => '2',
                        'debit' => 0,
                        'kredit' => $total,
                        'nominal' => $saldo,
                        'sumber' => 'Transaksi',
                        'deskripsi' => 'Transaksi Selesai',
                    ]);

                    $cekUser = User::whereLevel('CEO')->get();

                    foreach ($cekUser as $cekUser) {
                        $judul = 'Transaksi Selesai';
                        $body = $transaksi->kode_trans . " Rp." . $total;
                        $token_1 = $cekUser->userToken;
                        $comment = new Notifikasi();
                        $comment->toId = '';
                        $comment->toLevel = $cekUser->level;
                        $comment->title = $judul;
                        $comment->body = $body;
                        $comment->image = '';
                        $comment->url = url('/transaksi');
                        $comment->status = 0;
                        if (!empty($cekUser->userToken)) {
                            sendNotif($token_1, $comment);
                        }
                        $comment->save();
                    }


                    $response = [
                        'success' => true,
                        'message' => 'Transaksi Selesai.',
                    ];
                    return response()->json($response, 200);
                } else {
                    $saldo = 0;
                    $saldo = $saldo + $total;
                    Kas::Create([
                        'tgl' => date('Y-m-d'),
                        'jenis' => '2',
                        'debit' => 0,
                        'kredit' => $total,
                        'nominal' => $saldo,
                        'sumber' => 'Transaksi',
                        'deskripsi' => 'Transaksi Selesai',
                    ]);

                    $cekUser = User::whereLevel('CEO')->get();

                    foreach ($cekUser as $cekUser) {
                        $judul = 'Transaksi Selesai';
                        $body = $transaksi->kode_trans . " Rp." . $total;
                        $token_1 = $cekUser->userToken;
                        $comment = new Notifikasi();
                        $comment->toId = '';
                        $comment->toLevel = $cekUser->level;
                        $comment->title = $judul;
                        $comment->body = $body;
                        $comment->image = '';
                        $comment->url = url('/transaksi');
                        $comment->status = 0;
                        if (!empty($cekUser->userToken)) {
                            sendNotif($token_1, $comment);
                        }
                        $comment->save();
                    }



                    $response = [
                        'success' => true,
                        'message' => 'Transaksi Selesai.',
                    ];
                    return response()->json($response, 200);
                }
            });
            // $response = [
            //     'success' => true,
            //     'message' => 'Transaksi Selesai.',
            // ];
            // return response()->json($response, 200);
        } elseif ($request->status_trans == 4) {
            //Tolak
            $transaksi = Transaksi::find($request->id);
            $transaksi->update([
                'status_trans' => '4',
                'deskripsi' => '',
            ]);

            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi Ditolak / Dibatalkan';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }

            $cekUser = User::whereLevel('CEO')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi Ditolak / Dibatalkan';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }


            $response = [
                'success' => true,
                'message' => 'Telah Dibatalkan.',
            ];
            return response()->json($response, 200);
        } elseif ($request->status_trans == 2) {
            //Proses CEO
            $transaksi = Transaksi::find($request->id);
            $transaksi->update([
                'status_trans' => '2',
                'deskripsi' => '',
            ]);

            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi telah Diproses';
                $body = $transaksi->kode_trans;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }


            $response = [
                'success' => true,
                'message' => 'Transaksi Proses.',
            ];
            return response()->json($response, 200);
        }
    }


    public function confirs($kode_trans)
    {
        $cekInvoice = Xeninvoice::where('xen_external_id', $kode_trans)->first();
        if (!$cekInvoice) {
            $pesan = "Transaksi tidak ditemukan";
            return view('website.prosesPage', compact('pesan'));
        }
        $getInvoice = new XenditController();
        $getInvoice = $getInvoice->getInvoice($cekInvoice->xen_id);
        if ($getInvoice['status'] == 'PAID') {
            //
        } elseif ($getInvoice['status'] == 'EXPIRED') {
            $pesan = "Transaksi sudah kedaluwarsa";
            return view('website.prosesPage', compact('pesan'));
        } elseif ($getInvoice['status'] == 'PENDING') {
            $pesan = "Silahkan lakukan pembayaran pada link dibawah ini : <br><h3 class='text-center'>" . $getInvoice['invoice_url'] . "</h3>";
            return view('website.prosesPage', compact('pesan'));
        } else {
            $pesan = "Status transaksi tidak ditemukan, Silahkan hubungi customer service";
            return view('website.prosesPage', compact('pesan'));
        }
       
       
        $result = DB::transaction(function () use ($kode_trans) {
            //Dibayar
            $transaksi = Transaksi::where('kode_trans', $kode_trans)->first();
            $transaksi->update([
                'status_trans' => '5',
            ]);

            $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
            $pelanggan->status_data = 2;
            $pelanggan->save();

            $order = Order::where('trans_kode', $transaksi->kode_trans);
            foreach ($order->get() as $u) {
                $stock = Stock::where('produk_id', $u->produk_id)->update(['status_data' => 2]);
            }
            $order->update(['status_data' => 2]);

            $total = $transaksi->total;
            $cek = Kas::where('jenis', '2')->orderBy('created_at', 'desc')->first();
            if (!empty($cek->nominal)) {
                $saldo = $cek->nominal;
                $saldo = $saldo + $total;
            } else {
                $saldo = 0;
                $saldo = $saldo + $total;
            }
            Kas::Create([
                'tgl' => date('Y-m-d'),
                'jenis' => '2',
                'debit' => 0,
                'kredit' => $total,
                'nominal' => $saldo,
                'sumber' => 'Transaksi Online',
                'deskripsi' => 'Transaksi Dibayar',
            ]);
            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Transaksi Dibayar';
                $body = $transaksi->kode_trans . " Rp." . $total;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/transaksi');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }

            return $kode_trans;
        });
        if (!empty($result)) {
            return view('website.prosesPage', compact('result'));
        } else {
            $pesan = "Transaksi tidak dapat dikonfirmasi, Silahkan hubungi customer service";
            return view('website.prosesPage', compact('pesan'));
        }
    }

    public function failed($kode_trans)
    {
        $cek = Transaksi::where('kode_trans', $kode_trans)->first();
        if ($cek->status_trans == 4) {
            echo "Transaksi Dibatalkan";
            return;
        }
        DB::transaction(function () use ($kode_trans) {
            //Dibayar
            $transaksi = Transaksi::where('kode_trans', $kode_trans)->first();
            $transaksi->update([
                'status_trans' => '4',
            ]);
            return true;
        });

        echo "Transaksi Dibatalkan";
        return;
    }

    public function savePengiriman(Request $request)
    {
        $request->validate([
            'transaksiId' => 'required',
            'resi' => 'required',
            'kurir' => 'required',
            'biaya' => 'required',
        ]);

        $result = DB::transaction(function () use ($request) {
            $transaksi = Transaksi::find($request->transaksiId);
            if ($transaksi->status_trans == 6) {
                return true;
            }
            $shipping = Shipping::create([
                'transaksi_id' => $request->transaksiId,
                'resi' => $request->resi,
                'kurir' => $request->kurir,
                'biaya' => $request->biaya,
            ]);

            if ($shipping) {
                $total = $transaksi->total + $request->biaya;
                $transaksi->total = $total;
                $transaksi->status_trans = 6;
                $transaksi->save();
                return true;
            } else {
                return false;
            }
        });
        if ($result == true) {
            $response = [
                'success' => true,
                'message' => 'Input Resi Pengiriman Berhasil',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => false,
                'message' => 'Input Resi Pengiriman Gagal',
            ];
            return response()->json($response, 200);
        }
    }


    public function createInvoiceOffline($id_trans)
    {
        $transaksi = Transaksi::find($id_trans);
        if ($transaksi) {
            $xen = Xeninvoice::where('transaksi_id', $transaksi->id)->first();
            if ($xen) {
                return redirect($xen->xen_invoice_url);
            }
            $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
            // dd($pelanggan);
            $shipping = Shipping::where('transaksi_id', $transaksi->id)->first();
            $order = Order::where('trans_kode', $transaksi->kode_trans)->with('produk')->get();
        } else {
            $response = [
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ];
            return response()->json($response, 200);
        }

        $result = DB::transaction(function () use ($pelanggan, $shipping, $order, $transaksi) {
            $ppn = $transaksi->ppn;
            $pph = $transaksi->pph;
            $total = $transaksi->total;
            // $total = $transaksi->total + $shipping->biaya;


            $invoice = $transaksi->kode_trans;
            $trans = [
                'invoice' => $invoice,
                'amount' => $total,
                'deskription' => 'Tagihan Pembelian Produk di ' . config('app.name'),
                'duration' => website()->trx_duration_offline,
            ];

            $provinsi = ProvinceOngkir::find($pelanggan->provinsi_id);
            $provinsi = $provinsi->name;
            $kota = RegencyOngkir::find($pelanggan->kota_id);
            $kota = $kota->name;
            $kecamatan = DistrictOngkir::find($pelanggan->kecamatan_id);
            $kecamatan = $kecamatan->name;
            $hp = gantiformat($pelanggan->hp);
            $customer = [
                'given_names' => $pelanggan->nama_depan,
                'surname' => $pelanggan->nama_belakang,
                'email' => $pelanggan->email,
                'mobile_number' => $hp,
                'address' => [
                    [
                        'city' => $kota,
                        'country' => 'Indonesia',
                        'postal_code' => $pelanggan->pos,
                        'state' => $provinsi,
                        'street_line1' => $pelanggan->alamat,
                        'street_line2' => $kecamatan,
                    ]
                ]
            ];

            $ongkir = 0;
            if ($shipping) {
                $ongkir = $shipping->biaya;
            }
            $successUrl = config('app.url') . '/transaksi/confirs/' . $invoice;
            $failurUrl = config('app.url') . '/transaksi/failed/' . $invoice;
            // dd($order);
            $data = array();
            foreach ($order as $index => $i) {
                $data[] = array(
                    'name' => $i->produk->nama_produk,
                    'quantity' => $i->jumlah,
                    'price' => $i->harga_jual,
                );
            }

            $item = $data;


            $xendit = new XenditController();
            $proses = $xendit->createInvoice($trans, $customer, $item, $ongkir, $ppn, $pph, $successUrl, $failurUrl);
            if ($proses['status'] == 'PENDING') {
                $xenvoice = Xeninvoice::create([
                    'transaksi_id' => $transaksi->id,
                    'xen_id' => $proses['id'],
                    'xen_user_id' => $proses['user_id'],
                    'xen_external_id' => $proses['external_id'],
                    'xen_status' => $proses['status'],
                    'xen_invoice_url' => $proses['invoice_url'],
                    'xen_expiry_date' => $proses['expiry_date'],
                ]);

                return $proses['invoice_url'];
            } else {
                return false;
            }
        });
        if ($result == false) {
            return redirect('/transaksi/failed');
        } else {
            return redirect($result);
        }
    }
}
