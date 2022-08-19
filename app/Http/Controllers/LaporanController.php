<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Transaksi;
use App\Models\Kas;
use App\Models\Aset;
use Illuminate\Http\Request;
use DataTables;
use PDF;

class LaporanController extends Controller
{
    function view_laporan_transaksi(Request $request)
    {
        //
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('updated_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_trans)) {
            $transaksi->where('status_trans', $request->status_trans);
        }
        $transaksi = $transaksi->get();
        if ($request->ajax()) {
            return Datatables::of($transaksi)
                ->addIndexColumn()
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->pelanggan->nama_lengkap;
                })
                ->addColumn('perusahaan', function ($row) {
                    return $row->pelanggan->perusahaan;
                })
                ->addColumn('totalModal', function ($row) {
                    $rupiah = "Rp " . number_format($row->totalModal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('totalBiaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->totalBiaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('pph', function ($row) {
                    $rupiah = "Rp -" . number_format($row->pph, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('subtotal', function ($row) {
                    $rupiah = "Rp " . number_format($row->subtotal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('ppn', function ($row) {
                    $rupiah = "Rp " . number_format($row->ppn, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('total', function ($row) {
                    $rupiah = "Rp " . number_format($row->total, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $btn = "";
                    // if (session('level')==1){
                    // $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-xs editKategori"> <i class="mdi mdi-square-edit-outline"></i></a>';

                    // $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id_kategori="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteKategori"> <i class="mdi mdi-delete"></i></a>';
                    //}

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-transaksi');
    }

    public function print_laporan_transaksi(Request $request)
    {
        $transaksi = Transaksi::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $transaksi->whereBetween('updated_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->status_trans)) {
            $transaksi->where('status_trans', $request->status_trans);
        }
        $transaksi = $transaksi->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }
        if (!empty($request->status_trans)) {
            $status_trans = $request->status_trans;
        } else {
            $status_trans = 'Semua Transaksi';
        }

        if (isset($request->print)) {
            $export = '';
            $print = 'ya';
            $pdf = '';
        } elseif (isset($request->export)) {
            $export = 'ya';
            $print = '';
            $pdf = '';
        } elseif (isset($request->pdf)) {
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-transaksi', compact('transaksi', 'tanggal', 'status_trans', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-transaksi');
        }


        return view("laporan/print-laporan-transaksi", compact('transaksi', 'tanggal', 'status_trans', 'export', 'print'));
    }



    function view_laporan_pengeluaran(Request $request)
    {
        //
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

        $pengeluaran = $pengeluaran->get();
        if ($request->ajax()) {
            return Datatables::of($pengeluaran)
                ->addIndexColumn()
                ->addColumn('biaya', function ($row) {
                    $rupiah = "Rp " . number_format($row->biaya, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('persetujuan', function ($row) {
                    if ($row->persetujuan == 1) {
                        return 'Belum Verifikasi';
                    } elseif ($row->persetujuan == 2) {
                        return 'Disetujui';
                    } elseif ($row->persetujuan == 3) {
                        return 'Ditolak';
                    }
                })
                ->addColumn('jenis_kas', function ($row) {
                    if ($row->jenis_kas == 1) {
                        return 'Kas Kecil';
                    } elseif ($row->jenis_kas == 2) {
                        return 'Kas Besar';
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-pengeluaran');
    }

    public function print_laporan_pengeluaran(Request $request)
    {
        $pengeluaran = Pengeluaran::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $pengeluaran->whereBetween('tgl', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $pengeluaran->where('jenis_kas', $request->jenis);
        }

        if (!empty($request->persetujuan)) {
            $pengeluaran->where('persetujuan', $request->persetujuan);
        }
        $pengeluaran = $pengeluaran->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }
        if (!empty($request->persetujuan)) {
            if ($request->persetujuan == 1) {
                $persetujuan = 'Belum Verifikasi';
            } elseif ($request->persetujuan == 2) {
                $persetujuan = 'Disetujui';
            } elseif ($request->persetujuan == 3) {
                $persetujuan = 'Ditolak';
            }
        } else {
            $persetujuan = 'Semua Pengeluaran';
        }


        if (!empty($request->jenis)) {
            if ($request->jenis == 1) {
                $jenis = 'Kas Kecil';
            } elseif ($request->jenis == 2) {
                $jenis = 'Kas Besar';
            }
        }

        if (isset($request->print)) {
            $export = '';
            $print = 'ya';
            $pdf = '';
        } elseif (isset($request->export)) {
            $export = 'ya';
            $print = '';
            $pdf = '';
        } elseif (isset($request->pdf)) {
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-pengeluaran', compact('pengeluaran', 'tanggal', 'persetujuan', 'jenis', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pengeluaran');
        }


        return view("laporan/print-laporan-pengeluaran", compact('pengeluaran', 'tanggal', 'persetujuan', 'jenis', 'export', 'print'));
    }


    function view_laporan_kas(Request $request)
    {
        //
        $kas = Kas::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $kas->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $kas->where('jenis', $request->jenis);
        }

        $kas->orderBy('id', 'DESC');
        $kas = $kas->get();
        if ($request->ajax()) {
            return Datatables::of($kas)
                ->addIndexColumn()
                ->addColumn('jenis', function ($row) {
                    if ($row->jenis == 1) {
                        return 'Kas Kecil';
                    } elseif ($row->jenis == 2) {
                        return 'Kas Besar';
                    }
                })
                ->addColumn('kredit', function ($row) {
                    $rupiah = "Rp " . number_format($row->kredit, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('debit', function ($row) {
                    $rupiah = "Rp " . number_format($row->debit, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('nominal', function ($row) {
                    $rupiah = "Rp " . number_format($row->nominal, 0, ',', '.');
                    return $rupiah;
                })
                ->addColumn('tgl', function ($row) {
                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-kas');
    }

    public function print_laporan_kas(Request $request)
    {
        $kas = Kas::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $kas->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->jenis)) {
            $kas->where('jenis', $request->jenis);
        }

        $kas->orderBy('id', 'DESC');
        $kas = $kas->get();


        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }

        if (!empty($request->jenis)) {
            if ($request->jenis == 1) {
                $jenis = 'Kas Kecil';
            } elseif ($request->jenis == 2) {
                $jenis = 'Kas Besar';
            }
        } else {
            $jenis = 'Semua Kas';
        }

        if (isset($request->print)) {
            $export = '';
            $print = 'ya';
            $pdf = '';
        } elseif (isset($request->export)) {
            $export = 'ya';
            $print = '';
            $pdf = '';
        } elseif (isset($request->pdf)) {
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-kas', compact('kas', 'tanggal', 'jenis', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-kas');
        }

        return view("laporan/print-laporan-kas", compact('kas', 'tanggal', 'jenis', 'export', 'print'));
    }




    function view_laporan_aset(Request $request)
    {
        //
        $aset = Aset::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $aset->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        if (!empty($request->kondisi)) {
            $aset->where('kondisi', $request->kondisi);
        }
        $aset = $aset->get();
        if ($request->ajax()) {
            return Datatables::of($aset)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        //
        return view('laporan/view-laporan-aset');
    }

    public function print_laporan_aset(Request $request)
    {
        $aset = Aset::query();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $aset->whereBetween('created_at', array($request->from_date, $request->to_date));
        }

        $kondisi = $request->kondisi;
        if (!empty($kondisi)) {
            $aset->where('kondisi', $kondisi);
        } else {
            $kondisi = 'Semua Aset';
        }
        $aset = $aset->get();
        if (!empty($request->from_date) and !empty($request->to_date)) {
            $tanggal = $request->from_date . ' - ' . $request->to_date;
        } else {
            $tanggal = '';
        }


        if (isset($request->print)) {
            $export = '';
            $print = 'ya';
            $pdf = '';
        } elseif (isset($request->export)) {
            $export = 'ya';
            $print = '';
            $pdf = '';
        } elseif (isset($request->pdf)) {
            $print = '';
            $export = '';
            $pdf = PDF::loadview('laporan/print-laporan-aset', compact('aset', 'tanggal', 'kondisi', 'export', 'print'))->setPaper('a4', 'landscape');
            return $pdf->download('laporan-aset');
        }


        return view("laporan/print-laporan-aset", compact('aset', 'tanggal', 'kondisi', 'export', 'print'));
    }
}
