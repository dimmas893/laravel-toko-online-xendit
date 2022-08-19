<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $menuTable = Menu::orderBy('created_at', 'DESC')->get();
        if ($request->ajax()) {
            return Datatables::of($menuTable)
                ->addIndexColumn()
                ->addColumn('select', function ($row) {
                    return '<input type="checkbox" name="select[]" class="select text-center" value="' . $row->id . '" />';
                })
                ->addColumn('icon', function ($row) {
                    return "<a href='/icon/" . $row->icon . "'><img width='50px' src='icon/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    //    if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_menu="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editMenu"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_menu="' . $row->id . '" data-status="trash" data-original-title="Delete" class="btn btn-danger btn-xs deleteMenu"> <i class="mdi mdi-delete"></i></a>';
                    // }

                    return $btn;
                })
                ->rawColumns(['select', 'icon', 'action'])
                ->make(true);
        }

        $menu = Menu::orderBy('title', 'ASC')->get();
        return view('admin.menu', compact('menuTable', 'menu'));
    }

    public function trashTable(Request $request)
    {
        $menu = Menu::onlyTrashed()
            ->orderBy('created_at', 'DESC')
            ->get();
        if ($request->ajax()) {
            return Datatables::of($menu)
                ->addIndexColumn()
                ->addColumn('icon', function ($row) {
                    return "<a href='/icon/" . $row->icon . "'><img width='50px' src='icon/" . $row->icon . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    //    if (auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN'){

                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_menu="' . $row->id . '" data-original-title="Restore" class="btn btn-primary restoreMenu"> Kembalikan</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_menu="' . $row->id . '" data-status="delete" data-original-title="Hapus" class="btn btn-danger deleteMenu"> Hapus</a>';
                    // }

                    return $btn;
                })
                ->rawColumns(['icon', 'action'])
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
        $cek = Menu::find($request->id_menu);
        $filename = '';
        if (empty($request->id_menu)) {
            if ($request->file) {
                $request->validate(
                    [
                        'file' => 'required|mimes:ico,png,jpg,jpeg|max:200',
                    ],
                    [
                        'max' => 'Maksimal Kapasitas Icon 200KB',
                        'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg',
                    ],
                );
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('icon'), $filename);
            }
            if (empty($filename)) {
                $filename = 'default.png';
            }
        } else {
            if ($request->file) {
                $request->validate(
                    [
                        'file' => 'required|mimes:ico,png,jpg,jpeg|max:200',
                    ],
                    [
                        'max' => 'Maksimal Kapasitas Icon 200KB',
                        'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg',
                    ],
                );
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('icon'), $filename);
            } else {
                $filename = $cek->icon;
            }
        }


        if (empty($request->id_menu)) {
            $request->validate(
                [
                    'title' => 'required|unique:menu,title',
                    'indek' => 'required|unique:menu,indek',
                ],
                [
                    'title.unique' => 'Nama Menu Sudah Ada...!!!',
                    'indek.unique' => 'Urutan Sudah Ada...!!!',
                    'title.required' => 'Silahkan Isi Nama Menu',
                    'indek.required' => 'Silahkan Pilih Urutan',
                ],
            );
        } else {
            $request->validate(
                [
                    'title' => 'required|unique:menu,title,' . $request->id_menu,
                    'indek' => 'required|unique:menu,indek,' . $request->id_menu,
                ],
                [
                    'title.unique' => 'Nama Menu Sudah Ada...!!!',
                    'indek.unique' => 'Urutan Sudah Digunakan...!!!',
                    'title.required' => 'Silahkan Isi Nama Menu',
                    'indek.required' => 'Silahkan Pilih Urutan',
                ],
            );
        }
        menu::updateOrCreate(
            [
                'id' => $request->id_menu,
            ],
            [
                'induk' => $request->induk,
                'title' => $request->title,
                'indek' => $request->indek,
                'url' => $request->url,
                'icon' => $filename,
            ],
        );

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
     * @param  \App\Models\menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menu = menu::find($id);
        return response()->json($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function trash($id)
    {
        // DB::transaction(function () use ($id) {
        Menu::find($id)->delete();

        // });
        // return response
        $response = [
            'success' => true,
            'message' => 'Data Move to Trash.',
        ];
        return response()->json($response, 200);
    }

    public function delete($id)
    {
        // DB::transaction(function () use ($id) {
        Menu::where('id', $id)
            ->withTrashed()
            ->forceDelete();
        // });
        // return response
        $response = [
            'success' => true,
            'message' => 'Data Deleted Success.',
        ];
        return response()->json($response, 200);
    }

    public function restore($id)
    {
        DB::transaction(function () use ($id) {
            Menu::withTrashed()
                ->where('id', $id)
                ->restore();
        });
        $response = [
            'success' => true,
            'message' => 'Data Restore Success.',
        ];
        return response()->json($response, 200);
    }

    public function bulkDelete(Request $request)
    {
        // DB::transaction(function () use ($request) {
        Menu::whereIn('id', $request->id)->delete();
        // });
        // return response
        $response = [
            'success' => true,
            'message' => 'All Data Selected Move to Trash.',
        ];
        return response()->json($response, 200);
    }
}
