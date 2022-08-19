<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pengguna = User::orderBy('created_at', 'DESC')->get();
        if ($request->ajax()) {
            return Datatables::of($pengguna)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    return "<a href='/userFoto/" . $row->foto . "'><img width='50px' src='userFoto/" . $row->foto . "'/></a>";
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'ADMIN') {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengguna="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editPengguna"> <i class="mdi mdi-square-edit-outline"></i></a>';

                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengguna="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deletePengguna"> <i class="mdi mdi-delete"></i></a>';
                    }

                    return $btn;
                })
                ->rawColumns(['name', 'email', 'level', 'foto', 'action'])
                ->make(true);
        }

        return view('user/user', compact('pengguna'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profil()
    {
        $user = User::find(auth()->user()->id);
        return view('user/profil', compact(['user']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        if (empty($request->id_pengguna)) {
            $request->validate([
                'nama_lengkap' => 'required',
                'username' => 'required|unique:users,username',
                'level' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'repassword' => 'required|same:password'
            ], [
                'required' => 'Silahkan lengkapi data',
                'email.unique' => 'Email sudah terdaftar',
                'username.unique' => 'Username sudah terdaftar',
                'same' => 'Kombinasi Password tidak valid',
            ]);
            if ($request->file) {
                $request->validate([
                    'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('userFoto'), $filename);
            } else {
                $filename = '';
            }
            $token = '';
            $password = Hash::make($request->password);
        } else {
            $user = User::find($request->id_pengguna);
            $request->validate([
                'nama_lengkap' => 'required|max:100',
                'level' => 'required',
                'email' => 'required|email|unique:users,email,' . $request->id_pengguna,
                'username' => 'required|max:100|unique:users,username,' . $request->id_pengguna,
            ], [
                'required' => 'Silahkan lengkapi data',
                'email.unique' => 'Email sudah terdaftar',
                'username.unique' => 'Username sudah terdaftar',
            ]);
            if ($request->file) {
                $request->validate([
                    'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
                ], [
                    'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                    'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
                ]);
                $filename = time() . '.' . $request->file->extension();
                $request->file->move(public_path('userFoto'), $filename);
            } else {
                $filename = $user->foto;
            }

            $token = '';
            if (!empty($request->password and !empty($request->repassword))) {
                $request->validate([
                    'password' => 'required',
                    'repassword' => 'required|same:password'
                ], [
                    'same' => 'Kombinasi Password tidak valid',
                ]);
                $password = Hash::make($request->password);
            } else {
                $password = $user->password;
            }
        }



        User::updateOrCreate([
            'id' => $request->id_pengguna
        ], [
            'name' => $request->nama_lengkap,
            'username' => $request->username,
            'level' => $request->level,
            'email' => $request->email,
            'foto' => $filename,
            'userToken' => $token,
            'password' => $password,
        ]);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Disimpan.',
        ];
        return response()->json($response, 200);
    }



    public function updateProfil(Request $request)
    {

        $request->validate([
            'id_pengguna' => 'required',
            'nama_lengkap' => 'required|max:100',
            'email' => 'required|email|unique:users,email,' . $request->id_pengguna,
            'username' => 'required|max:100|unique:users,username,' . $request->id_pengguna,
        ], [
            'required' => '',
            'email' => 'Alamat Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'username.unique' => 'Username sudah terdaftar',
            'username.max' => 'Maksimal Username 100 Karakter',
        ]);



        $profil = User::find($request->id_pengguna);
        $profil->name = $request->nama_lengkap;
        $profil->email = $request->email;
        $profil->username = $request->username;
        if (!empty($request->password and !empty($request->repassword))) {
            $request->validate([
                'password' => 'required',
                'repassword' => 'required|same:password'
            ], [
                'same' => 'Kombinasi Password tidak valid',
                'required' => 'Kombinasi Password tidak valid'
            ]);
            $profil->password = Hash::make($request->password);
        }
        if ($request->file) {
            $request->validate([
                'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
            ], [
                'max' => 'Maksimal Kapasitas Icon 1024KB/1MB',
                'mimes' => 'Ekstensi Icon Diizinkan .ico / .png / .jpg / .jpeg'
            ]);
            $filename = time() . '.' . $request->file->extension();
            $request->file->move(public_path('userFoto'), $filename);
            $profil->foto = $filename;
        }
        $profil->save();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = User::destroy($id);
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }
}
