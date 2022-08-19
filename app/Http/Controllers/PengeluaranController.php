<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Kas;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;

class PengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pengeluaran = Pengeluaran::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $pengeluaran->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->persetujuan)) {
            $pengeluaran->where('persetujuan', $request->persetujuan);
        }

        if (!empty($request->jenis_kas)) {
            $pengeluaran->where('jenis_kas', $request->jenis_kas);
        }
        $pengeluaran=$pengeluaran->orderBy('created_at', 'desc');
        $pengeluaran = $pengeluaran->get();
        if ($request->ajax()) {
            return Datatables::of($pengeluaran)
                ->addIndexColumn()
                ->addColumn('jenis_kas', function ($row) {
                    if ($row->jenis_kas == 1) {
                        $jenis = '<span class="text-success">Kas Kecil</span>';
                    } elseif ($row->jenis_kas == 2) {
                        $jenis = '<span class="text-primary">Kas Besar</span>';
                    }
                    return $jenis;
                })
                ->addColumn('tgl', function ($row) {
                    $tgl = date('Y-m-d', strtotime($row->tgl));
                    return tglIndo($tgl);
                })
                ->addColumn('biaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->biaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('persetujuan', function ($row) {
                    if ($row->persetujuan == 1) {
                        return '<span class="badge badge-info-lighten">Belum Verifikasi</span>';
                    } elseif ($row->persetujuan == 2) {
                        return '<span class="badge badge-success-lighten">Disetujui</span>';
                    } elseif ($row->persetujuan == 3) {
                        return '<span class="badge badge-danger-lighten">Ditolak</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    if (auth()->user()->level == 'CEO') {
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" class="btn btn-info" id="btnVerifikasi" data-id_pengeluaran="' . $row->id . '" >Verifikasi</a>';
                        }
                    } elseif (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN') {
                        if ($row->persetujuan == 1) {
                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengeluaran="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editPengeluaran"> <i class="mdi mdi-square-edit-outline"></i></a>';

                            $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_pengeluaran="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deletePengeluaran"> <i class="mdi mdi-delete"></i></a>';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['jenis_kas', 'tgl', 'judul', 'biaya', 'deskripsi', 'persetujuan', 'action'])
                ->make(true);
        }

        return view('kantor/pengeluaran', compact('pengeluaran'));
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
        $biaya = str_replace('.', '', $request->biaya);
        $request->validate(
            [
                'jenis' => 'required',
                'tgl' => 'required',
                'judul' => 'required',
                'biaya' => 'required',
            ],
            [
                'required' => 'Data tidak boleh kosong...!!!'
            ]
        );
        Pengeluaran::updateOrCreate([
            'id' => $request->id_pengeluaran
        ], [
            'jenis_kas' => $request->jenis,
            'tgl' => $request->tgl,
            'judul' => $request->judul,
            'biaya' => $biaya,
            'deskripsi' => $request->deskripsi,
            'persetujuan' => 1,
        ]);
        $cekUser = User::whereLevel('CEO')->get();
        foreach ($cekUser as $cekUser) {
            $judul = 'Pengeluaran Baru';
            $body = $request->judul . " Rp." . $request->biaya;
            $token_1 = $cekUser->userToken;
            $comment = new Notifikasi();
            $comment->toId = '';
            $comment->toLevel = $cekUser->level;
            $comment->title = $judul;
            $comment->body = $body;
            $comment->image = '';
            $comment->url = url('/pengeluaran');
            $comment->status = 0;
            if (!empty($cekUser->userToken)) {
                sendNotif($token_1, $comment);
            }
            $comment->save();
        }
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
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pengeluaran = Pengeluaran::find($id);
        return response()->json($pengeluaran);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengeluaran  $pengeluaran
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Pengeluaran::find($id)->delete();
        // return response
        $response = [
            'success' => true,
            'message' => 'Berhasil Dihapus.',
        ];
        return response()->json($response, 200);
    }

    public function verifikasiPengeluaran(Request $request)
    {
        if ($request->id == 1) {

            //Setuju
            $pengeluaran = Pengeluaran::find($request->verifikasiId);
            if ($pengeluaran->persetujuan == 2) {
                $response = [
                    'success' => true,
                    'message' => 'Sudah Diverifikasi.',
                ];
                return response()->json($response, 200);
            } else {
                $biaya = $pengeluaran->biaya;
                $cek = Kas::where('jenis', $pengeluaran->jenis_kas)->orderBy('created_at', 'desc')->first();
                if (!empty($cek->nominal)) {
                    $saldo = $cek->nominal;
                    $saldo = $saldo - $biaya;
                    if ($saldo >= 0) {
                        DB::transaction(function () use ($request, $pengeluaran, $biaya, $saldo) {
                            $pengeluaran->update([
                                'persetujuan' => 2,
                                'deskripsi' => $pengeluaran->deskripsi . '<br>' . auth()->user()->name . '<br>' . $request->deskripsi
                            ]);
                            Kas::Create([
                                'tgl' => $pengeluaran->tgl,
                                'jenis' => $pengeluaran->jenis_kas,
                                'debit' => $biaya,
                                'kredit' => 0,
                                'nominal' => $saldo,
                                'sumber' => 'Pengeluaran',
                                'deskripsi' => $request->deskripsi,
                            ]);
                        });
                        $cekUser = User::whereLevel('STAFF')->get();

                        foreach ($cekUser as $cekUser) {
                            $judul = 'Pengeluaran Disetujui';
                            $body = $pengeluaran->judul . " Rp." . $pengeluaran->biaya;
                            $token_1 = $cekUser->userToken;
                            $comment = new Notifikasi();
                            $comment->toId = '';
                            $comment->toLevel = $cekUser->level;
                            $comment->title = $judul;
                            $comment->body = $body;
                            $comment->image = '';
                            $comment->url = url('/pengeluaran');
                            $comment->status = 0;
                            if (!empty($cekUser->userToken)) {
                                sendNotif($token_1, $comment);
                            }
                            $comment->save();
                        }
                        $response = [
                            'success' => true,
                            'message' => 'Verifikasi Berhasil.',
                        ];
                        return response()->json($response, 200);
                    } else {
                        echo "Saldo Tidak Cukup";
                        $response = [
                            'success' => false,
                            'message' => 'Gagal, Saldo tidak mencukupi.',
                        ];
                        return response()->json($response, 200);
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal, Saldo tidak mencukupi.',
                    ];
                    return response()->json($response, 200);
                }
            }
        } elseif ($request->id == 2) {
            //Tolak
            $pengeluaran = Pengeluaran::find($request->verifikasiId);
            $pengeluaran->update([
                'persetujuan' => 3,
                'deskripsi' => $pengeluaran->deskripsi . '<br>' . auth()->user()->name . '<br>' . $request->deskripsi
            ]);
            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Pengeluaran Ditolak';
                $body = $pengeluaran->judul . " Rp." . $pengeluaran->biaya;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/pengeluaran');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }

            $response = [
                'success' => true,
                'message' => 'Telah Ditolak.',
            ];
            return response()->json($response, 200);
        }
    }

    public function apiDataPengeluaran($id)
    {
        $pengeluaran = Pengeluaran::orderBy('created_at', 'desc');
        $pengeluaran->where('persetujuan', $id);
        $pengeluaran = $pengeluaran->get();
        return response()
            ->json(['message' => 'Berhasil mengambil data pengeluaran', 'token_type' => 'Bearer', 'data' => $pengeluaran]);
    }

    public function apiVerifikasiPengeluaran(Request $request)
    {
        if ($request->persetujuan == 2) {

            //Setuju
            $pengeluaran = Pengeluaran::find($request->id);
            if ($pengeluaran->persetujuan == 2) {
                $response = [
                    'success' => true,
                    'message' => 'Sudah Diverifikasi.',
                ];
                return response()->json($response, 200);
            } else {
                $biaya = $pengeluaran->biaya;
                $cek = Kas::where('jenis', $pengeluaran->jenis_kas)->orderBy('created_at', 'desc')->first();
                if (!empty($cek->nominal)) {
                    $saldo = $cek->nominal;
                    $saldo = $saldo - $biaya;

                    if ($saldo >= 0) {
                        DB::transaction(function () use ($pengeluaran, $biaya, $saldo) {
                            $pengeluaran->update([
                                'persetujuan' => 2,
                                'deskripsi' => $pengeluaran->deskripsi . auth()->user()->name . '<br>Verifikasi Melalui Android'
                            ]);
                            Kas::Create([
                                'tgl' => $pengeluaran->tgl,
                                'jenis' => $pengeluaran->jenis_kas,
                                'debit' => $biaya,
                                'kredit' => 0,
                                'nominal' => $saldo,
                                'sumber' => 'Pengeluaran',
                                'deskripsi' => 'Verifikasi Melalui Android',
                            ]);
                        });
                        $cekUser = User::whereLevel('STAFF')->get();

                        foreach ($cekUser as $cekUser) {
                            $judul = 'Pengeluaran Disetujui';
                            $body = $pengeluaran->judul . " Rp." . $pengeluaran->biaya;
                            $token_1 = $cekUser->userToken;
                            $comment = new Notifikasi();
                            $comment->toId = '';
                            $comment->toLevel = $cekUser->level;
                            $comment->title = $judul;
                            $comment->body = $body;
                            $comment->image = '';
                            $comment->url = url('/pengeluaran');
                            $comment->status = 0;
                            if (!empty($cekUser->userToken)) {
                                sendNotif($token_1, $comment);
                            }
                            $comment->save();
                        }
                        $response = [
                            'success' => true,
                            'message' => 'Verifikasi Berhasil.',
                        ];
                        return response()->json($response, 200);
                    } else {
                        echo "Saldo Tidak Cukup";
                        $response = [
                            'success' => false,
                            'message' => 'Gagal, Saldo tidak mencukupi.',
                        ];
                        return response()->json($response, 200);
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Gagal, Saldo tidak mencukupi.',
                    ];
                    return response()->json($response, 200);
                }
            }
        } elseif ($request->persetujuan == 3) {
            //Tolak
            $pengeluaran = Pengeluaran::find($request->id);
            $pengeluaran->update([
                'persetujuan' => 3,
                'deskripsi' => $pengeluaran->deskripsi . auth()->user()->name . '<br>Verifikasi Melalui Android'
            ]);

            $cekUser = User::whereLevel('STAFF')->get();

            foreach ($cekUser as $cekUser) {
                $judul = 'Pengeluaran Ditolak';
                $body = $pengeluaran->judul . " Rp." . $pengeluaran->biaya;
                $token_1 = $cekUser->userToken;
                $comment = new Notifikasi();
                $comment->toId = '';
                $comment->toLevel = $cekUser->level;
                $comment->title = $judul;
                $comment->body = $body;
                $comment->image = '';
                $comment->url = url('/pengeluaran');
                $comment->status = 0;
                if (!empty($cekUser->userToken)) {
                    sendNotif($token_1, $comment);
                }
                $comment->save();
            }

            $response = [
                'success' => true,
                'message' => 'Telah Ditolak.',
            ];
            return response()->json($response, 200);
        }
    }

    public function apiSimpanPengeluaran(Request $request)
    {
        $biaya = str_replace('.', '', $request->biaya);
        $jenis = $request->jenis;
        if ($jenis == 'Kas Kecil') {
            $jenis = '1';
        } elseif ($jenis == 'Kas Besar') {
            $jenis = '2';
        }
        $query = Pengeluaran::updateOrCreate([
            'id' => $request->id
        ], [
            'jenis_kas' => $jenis,
            'tgl' => $request->tgl,
            'judul' => $request->judul,
            'biaya' => $biaya,
            'deskripsi' => $request->deskripsi,
            'persetujuan' => 1,
        ]);

        $cekUser = User::whereLevel('CEO')->get();

        foreach ($cekUser as $cekUser) {
            $judul = 'Pengeluaran Baru';
            $body = $request->judul . " Rp." . $request->biaya;
            $token_1 = $cekUser->userToken;
            $comment = new Notifikasi();
            $comment->toId = '';
            $comment->toLevel = $cekUser->level;
            $comment->title = $judul;
            $comment->body = $body;
            $comment->image = '';
            $comment->url = url('/pengeluaran');
            $comment->status = 0;
            if (!empty($cekUser->userToken)) {
                sendNotif($token_1, $comment);
            }
            $comment->save();
        }
        if ($query) {
            // return response
            $response = [
                'success' => true,
                'message' => 'Berhasil Disimpan.',
            ];
            return response()->json($response, 200);
        }
    }
}
