<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;
use DataTables;
class KasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $kas=Kas::orderBy('created_at','DESC')->get();
     if ($request->ajax()) {
        return Datatables::of($kas)
        ->addIndexColumn()
        ->addColumn('jenis',function($row){
            if($row->jenis==1){
                $jenis='<span class="text-success">Kas Kecil</span>';
            }elseif($row->jenis==2){
                $jenis='<span class="text-primary">Kas Besar</span>';
            }
            return $jenis;
        })->addColumn('kredit',function($row){
            $rupiah = "Rp " . number_format($row->kredit,0,',','.');
            return $rupiah;
        })
        ->addColumn('debit',function($row){
            $rupiah = "Rp " . number_format($row->debit,0,',','.');
            return $rupiah;
        })
        ->addColumn('nominal',function($row){
            $rupiah = "Rp " . number_format($row->nominal,0,',','.');
            return $rupiah;
        })
        ->addColumn('tgl', function ($row) {
            $tgl=date('Y-m-d',strtotime($row->tgl));
            return tglIndo($tgl);
        })
        ->addColumn('action', function ($row) {
            $btn="";
           // if (session('level')==1){
            // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kas="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editKas"><i class="fa fa-pencil"></i> Ubah</a>';

            // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kas="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteKas"><i class="fa fa-trash"></i> Hapus</a>';
            //}

            return $btn;
        })
        ->rawColumns(['jenis','debit','kredit','nominal','sumber','deskripsi','tgl','action'])
        ->make(true);
    }

    return view('kantor/kas',compact('kas'));
}

    public function store(Request $request)
    {
        $request->validate([
            'jenis'=>'required',
            'nominal'=>'required'
        ],[
            'required'=>'Nominal tidak boleh kosong...!!!'
        ]);

        $nominal=0;
       $cek=Kas::where('jenis',$request->jenis)->orderBy('created_at','desc')->first();
       if(!empty($cek->nominal)){
       $saldo=$cek->nominal;
       $nominal=str_replace('.','',$request->nominal);
       $saldo=$saldo+$nominal;
        }else{
           $saldo=str_replace('.','',$request->nominal);
           $nominal=str_replace('.','',$request->nominal);
       }
        Kas::Create([
            'tgl' => $request->tgl,
            'jenis' => $request->jenis,
            'debit' => 0,
            'kredit' => $nominal,
            'nominal' => $saldo,
            'sumber' => 'Kas',
            'deskripsi' => $request->deskripsi,
        ]);

        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $del=Kas::destroy($id);
            // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function apiDataKas(){
        $kas=Kas::orderBy('created_at','DESC')->get();
        return response()
        ->json(['message' => 'Berhasil mengambil data Kas','token_type' => 'Bearer', 'data'=>$kas]);
    }


    public function apiStore(Request $request)
    {
        $jenis=$request->jenis;
      if($jenis=='Kas Kecil'){
          $jenis='1';
      }elseif($jenis=='Kas Besar'){
          $jenis='2';
      }
        $request->validate([
            'jenis'=>'required',
            'nominal'=>'required'
        ],[
            'required'=>'Nominal tidak boleh kosong...!!!'
        ]);

        $nominal=0;
       $cek=Kas::where('jenis',$jenis)->orderBy('created_at','desc')->first();
       if(!empty($cek->nominal)){
       $saldo=$cek->nominal;
       $nominal=str_replace('.','',$request->nominal);
       $saldo=$saldo+$nominal;
        }else{
           $saldo=str_replace('.','',$request->nominal);
           $nominal=str_replace('.','',$request->nominal);
       }
        Kas::Create([
            'tgl' => $request->tgl,
            'jenis' => $jenis,
            'debit' => 0,
            'kredit' => $nominal,
            'nominal' => $saldo,
            'sumber' => 'Kas',
            'deskripsi' => '',
        ]);

        $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
        return response()
        ->json($response,200);
    }


    public function apiTotalKas(){
        $kasBesar=Kas::where('jenis','1')->orderBy('created_at','desc')->first();
        $kasKecil=Kas::where('jenis','2')->orderBy('created_at','desc')->first();
        $data=['kasBesar'=>$kasBesar->nominal,'kasKecil'=>$kasKecil->nominal];
        $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
                'data'=>$data
            ];
        return response()
        ->json($response,200);
    }
}