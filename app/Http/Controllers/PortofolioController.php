<?php

namespace App\Http\Controllers;
use App\Models\Portofolio;
use App\Models\Kategori;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
class PortofolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $portofolio=Portofolio::orderBy('created_at','DESC')->get();
     if ($request->ajax()) {
        return Datatables::of($portofolio)
        ->addIndexColumn()
        ->addColumn('gambar', function ($row) {
            return "<a href='gambar/".$row->gambar_utama."'><img width='50px' src='gambar/".$row->gambar_utama."'/></a>";
        })
        ->addColumn('kategori', function ($row) {
            if(empty($row->kategori->nama_kategori)){
                return '';
            }else{
            return $row->kategori->nama_kategori;
            }
        })
        ->addColumn('action', function ($row) {
            $btn="";
           if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_portofolio="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editPortofolio"> <i class="mdi mdi-square-edit-outline"></i></a>';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_portofolio="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deletePortofolio"> <i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })
        ->rawColumns(['kategori','gambar','action'])
        ->make(true);
    }

    $kategori=Kategori::orderBy('nama_kategori','ASC')->get();
    return view('website/portofolio',compact('portofolio','kategori'));
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
        $cek=Portofolio::find($request->id_portofolio);
        if(empty($request->id_portofolio)){
            if ($request->file){
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:1024',
                ],[
                    'required' => 'Silahkan Upload Gambar',
                    'mimes' => 'Ekstensi Gambar Diizinkan .ico / .jpg / .jpeg / .png',
                    'max' => 'Maksimal Size Gambar 1024KB/1MB'
                ]);
                $filename = time().'.'.$request->file->extension();  
                $request->file->move(public_path('gambar'), $filename);

            }else{
                    // return response
                $response = [
                    'success' => false,
                    'message' => 'Silahkan Upload Gambar.',
                ];
                return response()->json($response, 500);
            }
        }else{
            if ($request->file){
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:1024',
                ],[
                    'required' => 'Silahkan Upload Gambar',
                    'mimes' => 'Ekstensi Gambar Diizinkan .ico / .jpg / .jpeg / .png',
                    'max' => 'Maksimal Size Gambar 1024KB/1MB'
                ]);
                $filename = time().'.'.$request->file->extension();  
                $request->file->move(public_path('gambar'), $filename);

            }else{
                $filename=$cek->gambar_utama;
            }

        }

        if(empty($request->id_portofolio)){
        $request->validate([
            'nama_produk'=>'required|unique:portofolio,nama_produk',
            'kategori'=>'required'
        ],[
            'required'=>'Pastikan Semua Data Sudah Diisi...!!!',
            'unique'=>'Portofolio Sudah Ada...!!!'
        ]
    );
}



        Portofolio::updateOrCreate([
            'id' => $request->id_portofolio
        ],[
            'kategori_id' => $request->kategori,
            'nama_produk' => $request->nama_produk,
            'slug' => str_replace(' ', '-', $request->nama_produk),
            'gambar_utama' => $filename,
           'deskripsi' => $request->deskripsi,
        ]);

        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\portofolio  $portofolio
     * @return \Illuminate\Http\Response
     */
    public function show(portofolio $portofolio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\portofolio  $portofolio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $portofolio = portofolio::find($id);
        return response()->json($portofolio);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\portofolio  $portofolio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, portofolio $portofolio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\portofolio  $portofolio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Portofolio::find($id)->delete();
        
            // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    
}

}