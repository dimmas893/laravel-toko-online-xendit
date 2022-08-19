<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $galeri=Galeri::orderBy('jenis','asc')->orderBy('created_at','DESC')->get();
    return view('website/galeri',compact('galeri'));
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
            'file.*' => 'required|mimes:png,jpeg,jpg,JPEG,JPG|max:1024',
        ],[
            'max' => 'Maximal ukuran file 1024KB/1MB',
            'mimes' =>'Format Gambar di Izinkan png,jpeg,jpg',
            
        ]);
      
              if ($request->hasfile('file')) {
                  $images = $request->file('file');
      
                  foreach($images as $image) {
                      $fileName = time().'-'.$image->getClientOriginalName();
                      $image->move(public_path('galeriImage'), $fileName);
      
                      $galeri = new Galeri();
                $galeri->gambar = $fileName;
                $galeri->data_id='';
                $galeri->jenis='galeri';
                $galeri->deskripsi='';
                $galeri->save();
                  }
               }
        
        return back()->with('success', 'Upload Gambar Berhasil.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\galeri  $galeri
     * @return \Illuminate\Http\Response
     */
    public function show(galeri $galeri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\galeri  $galeri
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $galeri = Galeri::find($id);
        return response()->json($galeri);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\galeri  $galeri
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, galeri $galeri)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\galeri  $galeri
     * @return \Illuminate\Http\Response
     */
   

    public function destroy($id)
    {
      $query=Galeri::destroy($id);
            // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

  


    public function aksiGaleri(Request $request){
        $request->validate([
            'select'=>'required'
        ],[
            'required'=>'Tidak ada Gambar Dipilih...!!!'
        ]);
        if(isset($request->hapus)){
        DB::transaction(function () use ($request) {
            Galeri::whereIn('id',$request->select)->delete();
        });
        return back()->with('success', 'Hapus Gambar Berhasil.');
        }elseif(isset($request->homeSlider)){
            $jml=count($request->input('select'));
            $inDb=Galeri::whereJenis('homeSlider')->count();
            $total=$jml+$inDb;
            if($total<=5){
            foreach($request->input('select') as $select){
                $query=Galeri::find($select);
                // if(empty($query->data_id)){
                //     $data_id='';
                // }else{
                //     $data_id=$query->data_id;
                // }

                if($query->jenis=='homeSlider'){

                }else{
                    $galeri = $query->replicate()->fill([
                        'data_id'=>'',
                        'jenis' => 'homeSlider',
                        'gambar'=>$query->gambar,
                    ]);
                    $galeri->save();
                }  
            }
        }else{
            return back()->with('error', 'Maksimal Gambar Slider Home Hanya 5.');
        }
            
            return back()->with('success', 'Berhasil Ditambahkan Ke Slider Home.');
        }elseif(isset($request->produkSlider)){
            $jml=count($request->input('select'));
            $inDb=Galeri::whereJenis('produkSlider')->count();
            $total=$jml+$inDb;
            if($total<=5){
            foreach($request->input('select') as $select){
                $query=Galeri::find($select);
                // if(empty($query->data_id)){
                //     $data_id='';
                // }else{
                //     $data_id=$query->data_id;
                // }

                if($query->jenis=='produkSlider'){

                }else{
                    $galeri = $query->replicate()->fill([
                        'data_id'=>'',
                        'jenis' => 'produkSlider',
                        'gambar'=>$query->gambar,
                    ]);
                    $galeri->save();
                }  
            }
        }else{
            return back()->with('error', 'Maksimal Gambar Slider Produk Hanya 5.');
        }
            return back()->with('success', 'Berhasil Ditambahkan Ke Slider Produk');
        }
    }
}