<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Stock;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $kategori=Kategori::orderBy('created_at','DESC')->get();
     if ($request->ajax()) {
        return Datatables::of($kategori)
        ->addIndexColumn()
        ->addColumn('select', function ($row) {
            return '<input type="checkbox" name="select[]" class="select text-center" value="'.$row->id.'" />';
        })
        ->addColumn('icon', function ($row) {
            return "<a href='/icon/".$row->icon."'><img width='50px' src='icon/".$row->icon."'/></a>";
        })
        ->addColumn('action', function ($row) {
            $btn="";
           if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){
            $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editKategori"> <i class="mdi mdi-square-edit-outline"></i></a>';

            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-status="trash" data-original-title="Delete" class="btn btn-danger btn-xs deleteKategori"> <i class="mdi mdi-delete"></i></a>';
            }

            return $btn;
        })
        ->rawColumns(['select','nama_kategori','icon','action'])
        ->make(true);
    }

    $kategori=Kategori::orderBy('nama_kategori','ASC')->get();
    return view('produk/kategori',compact('kategori','kategori'));
}


public function trashTable(Request $request)
    {
     $kategori=Kategori::onlyTrashed()->orderBy('created_at','DESC')->get();
     if ($request->ajax()) {
        return Datatables::of($kategori)
        ->addIndexColumn()
        ->addColumn('icon', function ($row) {
            return "<a href='/icon/".$row->icon."'><img width='50px' src='icon/".$row->icon."'/></a>";
        })
        ->addColumn('action', function ($row) {
            $btn="";
           if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){
            
        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Restore" class="btn btn-primary restoreKategori"> Kembalikan</a>';
        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-status="delete" data-original-title="Hapus" class="btn btn-danger deleteKategori"> Hapus</a>';
            }

            return $btn;
        })
        ->rawColumns(['nama_kategori','icon','action'])
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
        $cek=Kategori::find($request->id_kategori);
        if(empty($request->id_kategori)){
            if ($request->file){
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:200',
                ],[
                    'max'=>'Maksimal Kapasitas Icon 200KB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time().'.'.$request->file->extension();  
                $request->file->move(public_path('icon'), $filename);

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
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:200',
                ],[
                    'max'=>'Maksimal Kapasitas Icon 200KB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time().'.'.$request->file->extension();  
                $request->file->move(public_path('icon'), $filename);

            }else{
                $filename=$cek->icon;
            }

        }

        $request->validate([
            'nama_kategori'=>'required|unique:kategori,nama_kategori,'.$request->id_kategori,
        ],[
            'unique'=>'Kategori Sudah Ada...!!!',
            'required' =>'Silahkan Isi Kategori',
        ]
    );
        kategori::updateOrCreate([
            'id' => $request->id_kategori
        ],[
            'nama_kategori' => $request->nama_kategori,
            'slug' => strtolower(preg_replace("/[^a-zA-Z0-9]/", "-", $request->nama_kategori)),
            'icon' => $filename,
           
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
     * @param  \App\Models\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = kategori::find($id);
        return response()->json($kategori);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, kategori $kategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function trash($id)
    {
        // DB::transaction(function () use ($id) {
        // Kategori::find($id)->delete();
        // $produk=Produk::where('kategori_id',$id);
        // foreach($produk->get() as $p){
        //     Stock::where('produk_id',$p->id)->delete();
        // }
        // $produk->delete();
        // });

    
            $produk=Produk::where('kategori_id',$id)->count();
            if($produk>0){
                $response = [
                    'success' => false,
                    'message' => 'Masih ada produk terkait kategori ini.',
                ];
                
            }else{
                Kategori::find($id)->delete();
                $response = [
                    'success' => true,
                    'message' => 'Berhasil Dipindahkan Ke Tempat Sampah.',
                ];
                
            }
      
       
        return response()->json($response, 200);
    }

    public function delete($id){   
     $produk=Produk::where('kategori_id',$id)->count();
        if($produk>0){
            $response = [
                'success' => false,
                'message' => 'Masih ada produk terkait kategori ini.',
            ];
        }else{
            Kategori::where('id',$id)->withTrashed()->forceDelete();
            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
        }  
       
        return response()->json($response, 200);
    }

    public function restore($id)
    {
        DB::transaction(function () use ($id) {
        Kategori::withTrashed()->where('id',$id)->restore();
        $produk=Produk::withTrashed()->where('kategori_id',$id);
            foreach($produk->get() as $pro){
                Stock::withTrashed()->where('produk_id',$pro->id)->restore();
            }
        $produk->restore();    
        });
        $response = [
            'success' => true,
            'message' => 'Berhasil Dikembalikan.',
        ];
        return response()->json($response, 200);
    }


    public function bulkDelete(Request $request){
        DB::transaction(function () use ($request) {
            Kategori::whereIn('id',$request->id)->delete();
            $id=$request->id;
            $jml_pilih=count($id);
            
            for($x=0;$x<$jml_pilih;$x++){
                $produk=Produk::where('kategori_id',$id[$x]);
                foreach($produk->get() as $p){
                    Stock::where('produk_id',$p->id)->delete();
                }
                $produk->delete();
                   
            }
        });
                // return response
            $response = [
                'success' => true,
                'message' => 'Berhasil Dihapus.',
            ];
            return response()->json($response, 200);
    }
}