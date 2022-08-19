<?php

namespace App\Http\Controllers;
use App\Models\Testimoni;
use App\Models\Kategori;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
class TestimoniController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $testimoni=Testimoni::orderBy('created_at','DESC')->get();
     if ($request->ajax()) {
        return Datatables::of($testimoni)
        ->addIndexColumn()
        ->addColumn('gambar', function ($row) {
            return "<a href='/testimoniimg/".$row->gambar_utama."'><img width='50px' src='/testimoniimg/".$row->gambar_utama."'/></a>";
        })
        ->addColumn('action', function ($row) {
            $btn="";
           if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_testimoni="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editTestimoni"> <i class="mdi mdi-square-edit-outline"></i></a>';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_testimoni="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteTestimoni"> <i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })
        ->rawColumns(['gambar','action'])
        ->make(true);
    }

    return view('website/testimoni',compact('testimoni'));
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
        $cek=Testimoni::find($request->id_testimoni);
        if(empty($request->id_testimoni)){
            if ($request->file){
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:1024',
                ],[
                    'required' => 'Silahkan Upload Gambar',
                    'mimes' => 'Ekstensi Gambar Diizinkan .ico / .jpg / .jpeg / .png',
                    'max' => 'Maksimal Size Gambar 1024KB/1MB'
                ]);
                $filename = time().'.'.$request->file->extension();  
                $request->file->move(public_path('testimoniimg'), $filename);

            }else{
                    // return response
                $response = [
                    'success' => false,
                    'message' => 'Silahkan Upload Gambar.',
                ];
                return response()->json($response, 200);
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
                $request->file->move(public_path('testimoniimg'), $filename);

            }else{
                $filename=$cek->gambar_utama;
            }

        }

        if(empty($request->id_testimoni)){
        $request->validate([
            'dari'=>'required|unique:testimoni,dari',
        ],[
            'required'=>'Pastikan Semua Data Sudah Diisi...!!!',
            'unique'=>'Testimoni Sudah Ada...!!!'
        ]
    );
}



        Testimoni::updateOrCreate([
            'id' => $request->id_testimoni
        ],[
            'dari' => $request->dari,
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
     * @param  \App\Models\testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function show(testimoni $testimoni)
    {
        $testimoni=Testimoni::orderBy('created_at','DESC')->paginate(20);
        return view('website.testimoni-page',compact('testimoni'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $testimoni = testimoni::find($id);
        return response()->json($testimoni);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, testimoni $testimoni)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\testimoni  $testimoni
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Testimoni::find($id)->delete();
        
            // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    
}

}