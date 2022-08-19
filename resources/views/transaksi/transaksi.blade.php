@extends('layouts.app')

@section('content')
    <style>
        .table> :not(caption)>*>* {
            padding: 8px;
        }

        .table {
            min-width: 900px;
        }

    </style>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Transaksi</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <!-- <div class="row mb-2">
                                                                
                                                                <div class="col-sm-8">
                                                                    <div class="text-sm-start">
                                                                        <button type="button" class="btn btn-success mb-2 me-1"><i
                                                                                class="mdi mdi-cog-outline"></i></button>
                                                                        <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                                                        <button type="button" class="btn btn-light mb-2">Export</button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4">

                                                                </div>
                                                            </div> -->
                    <div class="col-sm-4">
                        @if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN')
                            <button type="button" id="createNewTrans" class="btn btn-primary mb-2" data-bs-toggle="modal"
                                data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                Transaksi Baru</button>
                        @endif
                    </div>
                    <form action="/print-laporan-transaksi" target="_blank" method="post">
                        @csrf

                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col-md-9 input-daterange">
                                <div class="row">
                                    <div class="col align-self-start">
                                        <label for="from_date">Dari Tanggal</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control"
                                            placeholder="From Date" readonly />
                                    </div>
                                    <div class="col align-self-end">
                                        <label for="to_date input-daterange">Sampai Tanggal</label>
                                        <input type="text" name="to_date" id="to_date" class="form-control"
                                            placeholder="To Date" readonly />
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="status_trans">Status Transaksi</label>
                                    <select name="status_trans" id="status_trans" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Menunggu Verifikasi</option>
                                        <option value="2">Proses</option>
                                        <option value="3">Selesai</option>
                                        <option value="4">Dibatalkan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col align-self-end">
                                <label for="">Aksi</label>
                                <div class="form-group">
                                    <a id="filter" class="btn btn-primary mb-2">Filter</a>
                                    <a id="refresh" class="btn btn-info mb-2">Refresh</a><br>
                                    <input type="submit" name="print" value="Print" class="btn btn-success">
                                    <input type="submit" name="export" value="Excel" class="btn btn-warning">
                                    <input type="submit" name="pdf" value="PDF" class="btn btn-danger">
                                </div>
                            </div>
                        </div>
                    </form>
                    <br />
                    <div class="table-responsive">
                        <table id="datatable" class="table table-centered w-100 dt-responsive nowrap table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Perusahaan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Deskripsi</th>
                                    <th>Qr Invoice</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->



    <!-- Modal -->
    <div class="modal fade" id="verifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="verifikasiForm">
                    @csrf
                    <input type="hidden" name="verifikasiId" id="verifikasiId">
                    <div class="modal-body">
                        <label for="deskripsi">Keterangan</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-danger aksiBtn" data-id="2">Batalkan Transaksi</button>
                        <button type="button" class="btn btn-primary aksiBtn" data-id="1">Transaksi Selesai</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->

    <div class="modal fade" id="pengiriman" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="pengirimanForm">
                    @csrf
                    <input type="hidden" name="transaksiId" id="transaksiId">
                    <div class="modal-body">
                        <label for="resi">No. Resi Pengiriman</label>
                        <input type="text" name="resi" class="form-control">
                        <label for="kurir">Jasa Pengiriman</label>
                        <input type="text" name="kurir" class="form-control">
                        <label for="biaya">Biaya Pengiriman</label>
                        <input type="text" name="biaya" id="biaya" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="savePengiriman">Kirim</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->


    {{-- <div id="modalProses" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-top">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="topModalLabel">Proses Transaksi</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Pelanggan telah dipilih, Klik Proses Transaksi untuk menyimpan!
                    <input type="hidden" id="pelangganId" name="pelangganId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="saveBtn2" class="btn btn-primary">Proses Transaksi</button>
                </div>
            </div>
        </div>
    </div> --}}




    <!-- Modal -->
    <div class="modal fade" id="verifikasiCeo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <form id="verifikasiFormCeo">
                    @csrf
                    <input type="hidden" name="verifikasiIdCeo" id="verifikasiIdCeo">
                    <div class="modal-body">
                        <label for="deskripsi">Keterangan</label>
                        <textarea name="deskripsiCeo" id="deskripsiCeo" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-danger aksiBtn" data-id="2">Batalkan Transaksi</button>
                        <button type="button" class="btn btn-primary aksiBtnCeo" data-id="3">Proses Transaksi</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->



    <!-- Modal -->
    <div class="modal fade" id="newTrans" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 90%; max-width: 100%;">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Transaksi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div> <!-- end modal header -->
                <div class="card">
                    <div class="card-body">

                        <div id="progressbarwizard">

                            <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                <li class="nav-item">
                                    <a href="#Transaksi" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 pt-2 pb-2">
                                        <i class="mdi mdi-account-circle me-1"></i>
                                        <span class="d-none d-sm-inline">Transaksi</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#Pelanggan" data-bs-toggle="tab" data-toggle="tab"
                                        class="nav-link rounded-0 pt-2 pb-2">
                                        <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                        <span class="d-none d-sm-inline">Pelanggan</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content b-0 mb-0">

                                <div id="bar" class="progress mb-3" style="height: 7px;">
                                    <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success">
                                    </div>
                                </div>

                                <div class="tab-pane" id="Transaksi">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="app-search dropdown mb-2">
                                                        <form>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    placeholder="Cari Produk" id="top-search">
                                                                <span class="mdi mdi-magnify search-icon"></span>

                                                            </div>
                                                        </form>
                                                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg"
                                                            id="search-dropdown"
                                                            style="max-height: 300px; width: 100%; overflow: scroll;">

                                                            <div id="resultSearch" class="notification-list">


                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">

                                                            <h4>Daftar Order</h4>
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered" id="tabelCart">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Kode</th>
                                                                            <th>Nama Produk</th>
                                                                            <th>Harga Modal</th>
                                                                            <th>Biaya Jasa / Satuan</th>
                                                                            <th>Harga Jual</th>
                                                                            <th>Jumlah</th>
                                                                            <th>Total</th>
                                                                            <th style="width: 50px;">Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </div> <!-- end table-responsive-->


                                                        </div>
                                                        <!-- end col -->

                                                    </div> <!-- end row -->
                                                </div> <!-- end col -->

                                                <div class="col-lg-8">
                                                    <div class="mb-2">
                                                        <label for="tgl">Tanggal Transaksi</label>
                                                        <input class="form-control" id="tgl" type="date" name="tgl">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="deskripsi">Catatan</label>
                                                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                                                    </div>
                                                    <div class="text-sm-start">

                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="border p-1 mt-2 mt-lg-0 rounded">
                                                        <h4 class="mb-2">Order Summary</h4>
                                                        <h5>Sub Total : <span class="subTotalCart float-end"></span>
                                                        </h5>
                                                        <h5>PPH : <input type="checkbox" name="pph" id="pph">
                                                            2%<span class="pph float-end"></span>
                                                        </h5>
                                                        <h5>PPN : <input type="checkbox" name="ppn" id="ppn">
                                                            10%<span class="ppn float-end"></span>
                                                        </h5>
                                                        <h4>Grand Total : <span class="grandTotal float-end"></span>
                                                        </h4>
                                                        <h4>Jumlah Diterima : <span class="terimaTotal float-end"></span>
                                                        </h4>
                                                    </div>




                                                </div> <!-- end col -->

                                            </div> <!-- end row-->
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>


                                <div class="tab-pane" id="Pelanggan">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="border mt-lg-0 rounded">
                                                        <h4 class="mt-2 p-1">Daftar Pelanggan</h4>
                                                        <div class="app-search dropdown mb-2 p-2"
                                                            style="min-height: 400px;">
                                                            <form>
                                                                <div class="input-group">
                                                                    <input type="text" name="inputCari"
                                                                        class="form-control" placeholder="Cari Pelanggan"
                                                                        id="cariPelanggan">
                                                                    <span class="mdi mdi-magnify search-icon"></span>

                                                                </div>
                                                            </form>
                                                            <div id="listPelanggan" style="overflow: scroll">

                                                            </div>
                                                        </div>
                                                    </div>

                                                </div> <!-- end col -->
                                                <div class="col-lg-8">
                                                    <h4 class="mt-2">Pelanggan</h4>

                                                    <!-- <p class="text-muted mb-4">Fill the form below in order to
                                                                            send you the order's invoice.</p> -->

                                                    <form>
                                                        <input type="hidden" id="pelangganId" name="pelangganId">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-first-name"
                                                                        class="form-label">Nama
                                                                        Depan <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="nama_depan"
                                                                        placeholder="Enter your first name"
                                                                        id="nama_depan" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-last-name"
                                                                        class="form-label">Nama
                                                                        Belakang <span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="nama_belakang"
                                                                        placeholder="Enter your last name"
                                                                        id="nama_belakang" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label for="perusahaan" class="form-label">Nama
                                                                        Perusahaan</label>
                                                                    <input class="form-control" type="text"
                                                                        name="perusahaan"
                                                                        placeholder="Enter your Company Name"
                                                                        id="perusahaan" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-email-address"
                                                                        class="form-label">Alamat
                                                                        Email
                                                                        <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="email" id="email"
                                                                        name="email" placeholder="Enter your email"
                                                                        id="billing-email-address" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="billing-phone" class="form-label">No.
                                                                        HP Aktif <span
                                                                            class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text" name="telpon"
                                                                        placeholder="08xxxxxxxxxx" id="telpon" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h4 class="mt-2">Alamat Pengiriman</h4>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="provinsi" class="form-label">Provinsi
                                                                        <span class="text-danger">*</span></label>
                                                                    <select name="provinsi" id="provinsi"
                                                                        class="form-control provinsi-tujuan">

                                                                        <option value="">Pilih Provinsi</option>
                                                                        @foreach ($provinces as $prov)
                                                                            <option value="{{ $prov->id }}">
                                                                                {{ $prov->name }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="kota" class="form-label">Kota /
                                                                        Kabupaten <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="kabupaten" id="kabupaten"
                                                                        class="form-control kota-tujuan">
                                                                        <option value="">Pilih Kota/Kabupaten
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="kecamatan" class="form-label">Kecamatan
                                                                        <span class="text-danger">*</span></label>
                                                                    <select name="kecamatan" id="kecamatan"
                                                                        class="form-control kecamatan-tujuan">

                                                                        <option value="">Pilih Kecamatan</option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                        </div> <!-- end row -->
                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="mb-3">
                                                                    <label for="billing-zip-postal"
                                                                        class="form-label">Kode
                                                                        Pos <span class="text-danger">*</span></label>
                                                                    <input class="form-control" type="text"
                                                                        name="kode_pos" placeholder="Enter your zip code"
                                                                        id="kode_pos" />
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label for="billing-address"
                                                                        class="form-label">Alamat <span
                                                                            class="text-danger">*</span></label>
                                                                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end row -->


                                                        <div class="row mt-4">

                                                            <div class="col-sm-12">
                                                                <div class="d-grid">
                                                                    <button id="saveBtn" class="btn btn-primary btn-lg">
                                                                        <i class="mdi mdi-cash-multiple me-1"></i>
                                                                        Proses Sekarang
                                                                    </button>
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->
                                                    </form>
                                                </div>


                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>

                                <ul class="list-inline mb-0 wizard mt-2">
                                    <li class="previous list-inline-item">
                                        <a href="javascript:void(0);" class="btn btn-info pre">Kembali</a>
                                    </li>
                                    <li class="next list-inline-item float-end">
                                        <a href="javascript:void(0);" class="btn btn-info next">Selanjutnya</a>
                                    </li>
                                </ul>

                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->


    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            $(".pre").css("display", "none");
            $(".next").css("display", "block");
            $("body").on("click", ".next", function() {
                $(this).css('display', 'none');
                $(".pre").css("display", "block");
            })
            $("body").on("click", ".pre", function() {
                $(this).css('display', 'none');
                $(".next").css("display", "block");
            })

            $('body').on("keyup", "#cartBiaya", function() {
                // Format mata uang.
                $('#cartBiaya').mask('0.000.000.000', {
                    reverse: true
                });

            })

            $('body').on("keyup", "#cartHargaJual", function() {
                // Format mata uang.
                $('#cartHargaJual').mask('0.000.000.000', {
                    reverse: true
                });
            })




            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            load_data();

            function load_data(from_date = '', to_date = '', status_trans = '') {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    retrieve: true,
                    paging: true,
                    destroy: true,
                    "scrollX": false,
                    ajax: {
                        url: "{{ url('/transaksiTable') }}",
                        type: "POST",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            status_trans: status_trans,
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'kode_trans',
                            name: 'kode_trans'
                        },
                        {
                            data: 'tgl_trans',
                            name: 'tgl_trans'
                        },
                        {
                            data: 'nama_pelanggan',
                            name: 'nama_pelanggan'
                        },
                        {
                            data: 'perusahaan',
                            name: 'perusahaan'
                        },
                        {
                            data: 'total',
                            name: 'total'
                        },
                        {
                            data: 'status_trans',
                            name: 'status_trans'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi'
                        },
                        {
                            data: 'qr',
                            name: 'qr'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },

                    ]
                });
                table.draw();
            }


            $('#createNewTrans').click(function() {
                // CKEDITOR.instances['deskripsi'].setData('');
                $('#newTrans').modal('show');
            });

            $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var status_trans = $('#status_trans').val();

                $('#datatable').DataTable().destroy();
                load_data(from_date, to_date, status_trans);
                $("#print").val('Print');
                $("#export").val('Export');

            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#status_trans').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });


            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });


            $('body').on('click', '#btnVerifikasi', function() {
                id = $(this).data('id_transaksi');
                $('#verifikasiForm').trigger("reset");
                $('#verifikasiId').val(id);
                $('#verifikasi').modal('show');
            });

            $('.aksiBtn').click(function(e) {
                e.preventDefault();
                $("#canvasloading").show();
                $("#loading").show();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
                }
                var form = $('#verifikasiForm')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == true) {
                            $('#verifikasiForm').trigger("reset");
                            $('#verifikasi').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(data) {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        console.log('Error:', data);
                        swal("Pesan", data.message, "error");
                    }
                });
            });



            $(document).on("click", "#saveBtn,#saveBtn2", function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                $("#canvasloading").show();
                $("#loading").show();
                idPelanggan = $("#pelangganId").val();
                // kode_trans = $("#kode_trans").val();
                tgl = $("#tgl").val();
                deskripsi = $("#deskripsi").val();

                if (idPelanggan != '') {
                    data = {
                        // kode_trans: kode_trans,
                        tgl: tgl,
                        deskripsi: deskripsi,
                        idPelanggan: idPelanggan
                    }
                } else {
                    nama_depan = $("#nama_depan").val();
                    nama_belakang = $("#nama_belakang").val();
                    perusahaan = $("#perusahaan").val();
                    email = $("#email").val();
                    telpon = $("#telpon").val();
                    provinsi = $("#provinsi").val();
                    kabupaten = $("#kabupaten").val();
                    kecamatan = $("#kecamatan").val();
                    desa = $("#desa").val();
                    kode_pos = $("#kode_pos").val();
                    alamat = $("#alamat").val();
                    data = {
                        tgl: tgl,
                        deskripsi: deskripsi,
                        nama_depan: nama_depan,
                        nama_belakang: nama_belakang,
                        perusahaan: perusahaan,
                        email: email,
                        telpon: telpon,
                        provinsi: provinsi,
                        kabupaten: kabupaten,
                        kecamatan: kecamatan,
                        desa: desa,
                        kode_pos: kode_pos,
                        alamat: alamat,
                    }
                }
                $.ajax({
                    data: data,
                    url: "{{ url('/transaksi/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        if (data.success == true) {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            // swal("Pesan",data.message,"success",
                            //     function() {
                            //         window.location.href = "/transaksi";
                            //     }
                            // );
                            swal({
                                    title: "Berhasil!",
                                    text: "",
                                    type: "success"
                                },
                                function() {
                                    window.location.href = "/transaksi";
                                }
                            );

                        } else {
                            $('#saveBtn').html('Proses Sekarang');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        if ($.isEmptyObject(res) == false) {
                            err = '';
                            $.each(res.errors, function(key, value) {
                                // err += value + ', ';
                                err = value;
                            });
                            $('#saveBtn').html('Proses Sekarang');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });



            //Cari Produk
            $('body').on('click', '#top-search', function() {
                $("#resultSearch").html('')
                var nama_produk = $("#top-search").val();
                $.get("{{ url('/cari') }}" + '/' + nama_produk, function(data) {
                    $("#resultSearch").html(data)
                })
            });

            $('body').on('keyup', '#top-search', function() {

                var nama_produk = $("#top-search").val();
                $.get("{{ url('/cari') }}" + '/' + nama_produk, function(data) {
                    $("#resultSearch").html(data)

                })

            });


            //show cart first
            getCartTable();

            //get cart table
            function getCartTable() {
                $.get("{{ url('/getcarttable') }}", function(data) {
                    $("#tabelCart tbody").html(data)

                })
            }
            //add to cart
            $('body').on('click', '#addToCart', function() {
                $("#ppn").prop("checked", false);
                $("#pph").prop("checked", false);
                $(".ppn").html('');
                $(".pph").html('');
                var id = $(this).data('id');
                $.get("{{ url('/addcart') }}" + '/' + id, function(data) {
                    if (data.success) {
                        swal("Pesan", data.message, "success");
                    } else {
                        swal("Pesan", data.message, "error");
                    }
                    getCartTable();
                })
            });


            $("body").on("change keyrelease", "#cartHargaJual", function() {
                $("#ppn").prop("checked", false);
                $("#pph").prop("checked", false);
                $(".ppn").html('');
                $(".pph").html('');
                $("#canvasloading").show();
                $("#loading").show();
                id = $(this).data('id');
                harga_jual = $(this).val();

                $.ajax({
                    type: "post",
                    url: "{{ url('/cart-update-harga-jual') }}",
                    data: {
                        id: id,
                        harga_jual: harga_jual
                    },
                    dataType: 'json',
                    success: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        // swal("Pesan",data.message,"success");
                    },
                    error: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        console.log('Error:', data);
                        swal("Pesan", "Pastikan Semua Data Sudah Benar", "error");
                    }
                });

            });

            $("body").on("change keyrelease", "#cartBiaya", function() {
                $("#ppn").prop("checked", false);
                $("#pph").prop("checked", false);
                $(".ppn").html('');
                $(".pph").html('');
                $("#canvasloading").show();
                $("#loading").show();
                id = $(this).data('id');
                biaya = $(this).val();
                modal = $(this).data('modal');

                $.ajax({
                    type: "post",
                    url: "{{ url('/cart-update-biaya') }}",
                    data: {
                        id: id,
                        biaya: biaya,
                        modal: modal
                    },
                    dataType: 'json',
                    success: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        // swal("Pesan",data.message,"success");
                    },
                    error: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        console.log('Error:', data);
                        swal("Pesan", data.message, "error");
                    }
                });

            });

            //update jumlah
            $("body").on("change keyrelease focusout", "#cartJumlah", function() {
                $("#ppn").prop("checked", false);
                $("#pph").prop("checked", false);
                $(".ppn").html('');
                $(".pph").html('');
                $("#canvasloading").show();
                $("#loading").show();
                id = $(this).data('id');
                jumlah = $(this).val();

                if (jumlah < 1) {
                    $("#cartJumlah").val('1')
                    $("#canvasloading").hide();
                    $("#loading").hide();
                } else {
                    $.ajax({
                        type: "post",
                        url: "{{ url('/cartupdate') }}",
                        data: {
                            id: id,
                            jumlah: jumlah
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.success) {
                                getCartTable()
                                $("#canvasloading").hide();
                                $("#loading").hide();
                            } else {
                                $("#cartJumlah").val('1');
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                swal("Pesan", data.message, "error");
                            }
                        },
                        error: function(data) {
                            getCartTable()
                            $("#canvasloading").hide();
                            $("#loading").hide();

                            console.log('Error:', data);
                            swal("Pesan", data.message, "error");
                        }
                    });
                }
            });

            $("#ppn").change(function() {
                if (this.checked) {
                    $.get("{{ url('/dengan-ppn') }}", function(data) {
                        getCartTable();
                    })
                } else {
                    $.get("{{ url('/hapus-ppn') }}", function(data) {
                        getCartTable();
                    })
                }
            })

            $("#pph").change(function() {
                if (this.checked) {
                    $.get("{{ url('/dengan-pph') }}", function(data) {
                        getCartTable();
                    })
                } else {
                    $.get("{{ url('/hapus-pph') }}", function(data) {
                        getCartTable();
                    })
                }
            })





            //remove cart
            $("body").on("click", "#cartRemove", function() {
                $("#ppn").prop("checked", false);
                $("#pph").prop("checked", false);
                $(".ppn").html('');
                $(".pph").html('');
                $("#canvasloading").show();
                $("#loading").show();
                id_produk = $(this).data('id')
                $.ajax({
                    type: "post",
                    url: "{{ url('/cartremove') }}" + '/' + id_produk,
                    success: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        swal("Pesan", data.message, "success");
                    },
                    error: function(data) {
                        getCartTable()
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        console.log('Error:', data);
                        swal("Pesan", data.message, "error");
                    }
                });
            })


            // $('.provinsi-tujuan').select2();
            // $('.kota-tujuan').select2();
            // $('.kecamatan-tujuan').select2();


            // $("#canvasloading").show();
            // $("#loading").show();
            // $.ajax({
            //     type: 'get',
            //     url: '{{ url('ongkir/get-all-provinsi') }}',
            //     success: function(data) {
            //         $("select[name=provinsi]").html(data);

            //         $("#canvasloading").hide();
            // $("#loading").hide();
            //     }
            // })


            //ajax select kota tujuan
            $('select[name="provinsi"]').on('change', function() {
                let provindeId = $(this).val();
                if (provindeId) {


                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kota') }}' + '/' + provindeId,
                        success: function(data) {
                            $("select[name=kabupaten]").html(data);


                            $("#canvasloading").hide();
                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="kabupaten"]').append(
                        '<option value="">-- Pilih Kota / Kabupaten --</option>');
                }
            });

            //ajax select kecamatan tujuan
            $('select[name="kabupaten"]').on('change', function() {
                let cityId = $(this).val();
                if (cityId) {


                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kecamatan') }}' + '/' + cityId,
                        success: function(data) {
                            $("select[name=kecamatan]").html(data);


                            $("#canvasloading").hide();
                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="kecamatan"]').append(
                        '<option value="">-- Pilih Kecamatan --</option>');
                }
            });



            //list Pelanggan
            function listPelanggan() {
                $.get("{{ url('/list-pelanggan') }}", function(data) {
                    $("#listPelanggan").html(data)
                })
            }

            listPelanggan();

            $('body').on('keyup release', '#cariPelanggan', function() {
                $("#listPelanggan").html('')
                var pelanggan = $("#cariPelanggan").val();
                $.get("{{ url('/cari-pelanggan') }}" + '/' + pelanggan, function(data) {
                    $("#listPelanggan").html(data)
                })
            });

            // $(document).on("click", "#idPelanggan", function() {
            //     id = $(this).data('id');

            // });



            $("body").on("focus", ".input", function() {
                val = $(this).val();
                if (val == '0') {
                    $(this).val('');
                    $(this).focus();
                }
            })

            $('body').on('click', '#btnVerifikasiCeo', function() {
                id = $(this).data('id_transaksi');
                $('#verifikasiFormCeo').trigger("reset");
                $('#verifikasiIdCeo').val(id);
                $('#verifikasiCeo').modal('show');
            });

            $('.aksiBtnCeo').click(function(e) {
                e.preventDefault();

                $("#canvasloading").show();
                $("#loading").show();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
                } else
                if (aksi == 3) {
                    url = "{{ url('/verifikasi-transaksi') }}" + '/' + aksi
                }
                var form = $('#verifikasiFormCeo')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == true) {
                            $('#verifikasiFormCeo').trigger("reset");
                            $('#verifikasiCeo').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(data) {
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        console.log('Error:', data);
                        swal("Pesan", data.message, "error");
                    }
                });
            });

            $('body').on('click', '#idPelanggan', function() {
                $('#pelangganId').val('');
                id = $(this).data('id');
                $('#pelangganId').val(id);
                swal("Pesan",
                    "Pelanggan telah dipilih, Silahkan Klik PROSES SEKARANG untuk menyimpan transaksi",
                    "success");
                // $('#modalProses').modal('show');
            });

            $('body').on('click', '.pengiriman', function() {
                id = $(this).data('id');
                status = $(this).data('status');
                if (status == 2) {
                    $("#biaya").val(0);
                    $("#biaya").prop('readonly', true);
                } else {
                    $("#biaya").val('');
                    $("#biaya").removeProp('readonly');
                }
                $('#transaksiId').val(id);
                $('#pengiriman').modal('show');
            });


            $('#savePengiriman').click(function(e) {
                e.preventDefault();
                $(this).html('Mengirim..');
                var form = $('#pengirimanForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/transaksi/simpan-pengiriman') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#pengirimanForm').trigger("reset");
                            $('#pengiriman').modal('hide');
                            load_data();
                            $('#savePengiriman').html('Kirim');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
                            $('#savePengiriman').html('Kirim');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        if ($.isEmptyObject(res) == false) {
                            err = '';
                            $.each(res.errors, function(key, value) {
                                // err += value + ', ';
                                err = value;
                            });
                            $('#savePengiriman').html('Kirim');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });

            $('body').on('click', '.create-invoice', function() {
                var id = $(this).data("id");
                var shipping = $(this).data("shipping");

                myurl = "{{ url('/transaksi/create-invoice') }}" + '/' + id
                    
                if (shipping == '1') {
                    msg =
                        "Resi belum di Input, Apakah ingin membuat tagihan tanpa biaya pengiriman?";
                } else if (shipping == '2') {
                    msg =
                        "Apakah ingin membuat Tagihan?";
                }
                swal({
                        title: "Buat Tagihan",
                        text: msg,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Buat Tagihan!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $("#canvasloading").show();
                            $("#loading").show();
                            
                            window.location.href=myurl;
                        } else {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Info!',
                                text: 'Buat Tagihan Dibatalkan',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                        }
                    });
            });

        });
    </script>
@endsection
