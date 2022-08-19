<?php

namespace App\Http\Controllers;
use App\Models\Pelanggan;
use App\Models\Kas;
use App\Models\Notifikasi;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // createNotifikasi(1,'ADMIN','test','ini body','','','');
        // $produk=DB::table('produk');
        // $produk->join('order','order.produk_id','produk.id');
        // $produk->groupBy('produk.id');
        // $produk=$produk->get();
        $month=date('m',strtotime(now()));
        $year=date('Y',strtotime(now()));
        $kas=Kas::orderBy('created_at','desc')->first();
        $pelanggan=Pelanggan::whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
        $transaksi=Transaksi::whereMonth('updated_at', $month)->whereYear('tgl_trans', $year)->where('status_trans','3')->count();
        $pendapatan=Transaksi::whereMonth('updated_at', $month)->whereYear('tgl_trans', $year)->where('status_trans','3')->sum('total');
        $pengeluaran=Pengeluaran::whereMonth('tgl', $month)->whereYear('tgl', $year)->where('persetujuan','2')->sum('biaya');
        
        return view('home',compact('kas','pelanggan','transaksi','pendapatan','pengeluaran'));
    }
}
