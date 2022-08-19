<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use DataTables;

class AsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $aset = Aset::query();
        if (!empty($request->kondisi)) {
            $aset->where('kondisi', $request->kondisi);
        }
        $aset=$aset->orderBy('created_at', 'DESC');
        $aset = $aset->get();
        if ($request->ajax()) {
            return Datatables::of($aset)
                ->addIndexColumn()
                ->addColumn('gambar', function ($row) {
                    return "<a href='/aset/" . $row->gambar . "'><img width='80px' src='aset_img/" . $row->gambar . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_aset="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editAset"> <i class="mdi mdi-square-edit-outline"></i></a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_aset="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteAset"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['kode_aset', 'nama_aset', 'merek', 'satuan', 'jumlah', 'kondisi', 'gambar', 'deskripsi', 'action'])
                ->make(true);
        }

        return view('kantor/aset', compact('aset'));
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
        $cek = Aset::find($request->id_aset);
        if (empty($request->id_aset)) {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'mimes' => 'Ekstensi Gambar Diizinkan .ico / .png / .jpg / .jpeg',
                    'required' => 'Silahkan Upload Gambar',
                    'max' => 'Maksimal Size 1024KB/1MB'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('aset_img'), $filename);
            } else {
                // return response
                $response = [
                    'success' => false,
                    'message' => 'Silahkan Upload Gambar.',
                ];
                return response()->json($response, 200);
            }
        } else {
            if ($request->file) {
                $request->validate([
                    'file' => 'required|mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'mimes' => 'Ekstensi Gambar Diizinkan .ico / .png / .jpg / .jpeg',
                    'required' => 'Silahkan Upload Gambar',
                    'max' => 'Maksimal Size 1024KB/1MB'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('aset_img'), $filename);
            } else {
                $filename = $cek->gambar;
            }
        }

        $request->validate(
            [
                'kode_aset' => 'required|unique:aset,kode_aset,' . $request->id_aset,
                'jumlah' => 'required|numeric',
                'kondisi' => 'required',

            ],
            [
                'kode_aset.unique' => 'Kode Aset Sudah Ada...!!!',
                'required' => 'Data tidak boleh kosong...!!!'
            ]
        );

        Aset::updateOrCreate([
            'id' => $request->id_aset
        ], [
            'kode_aset' => $request->kode_aset,
            'nama_aset' => $request->nama_aset,
            'merek' => $request->merek,
            'satuan' => $request->satuan,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'deskripsi' => $request->deskripsi,
            'gambar' => $filename,
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
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function show(Aset $aset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $aset = Aset::find($id);
        return response()->json($aset);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aset $aset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aset  $aset
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Aset::destroy($id);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }
}
