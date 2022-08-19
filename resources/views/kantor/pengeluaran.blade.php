@extends("layouts.app")

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Pengeluaran</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            @if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN')
                                <button type="button" id="createNewPengeluaran" class="btn btn-primary mb-2"
                                    data-bs-toggle="modal" data-bs-target="#success-header-modal"><i
                                        class="mdi mdi-plus-circle"></i>
                                    Tambah</button>
                            @endif
                        </div>
                        <!-- <div class="col-sm-8">
                                <div class="text-sm-end">
                                    <button type="button" class="btn btn-success mb-2 me-1"><i
                                            class="mdi mdi-cog-outline"></i></button>
                                    <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                    <button type="button" class="btn btn-light mb-2">Export</button>
                                </div>
                            </div> -->
                        <!-- end col-->
                    </div>
                    <form action="/print-laporan-pengeluaran" target="_blank" method="post">
                        @csrf

                        <h4>Filter</h4>
                        <div class="row">
                            <div class="col align-selft-start input-daterange">
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
                                    <label for="persetujuan">Status Verifikasi</label>
                                    <select name="persetujuan" id="persetujuan" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Menunggu Verifikasi</option>
                                        <option value="2">Disetujui</option>
                                        <option value="3">Ditolak</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="jenis">Jenis Kas</label>
                                    <select name="jenis" id="jenis" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="1">Kas Kecil</option>
                                        <option value="2">Kas Besar</option>
                                    </select>
                                </div>
                            </div>
                            

                            <div class="col-md-3">
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
                        <table class="table table-centered w-100 dt-responsive nowrap table-striped" id="datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pengeluaran</th>
                                    <th>Tanggal</th>
                                    <th>Kas</th>
                                    <th>Biaya</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th style="width: 85px;">Aksi</th>
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



    <!-- Success Header Modal -->

    <div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="success-header-modalLabel">Pengeluaran</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="pengeluaranForm" name="pengeluaranForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_pengeluaran" id="id_pengeluaran">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <label for="tgl" class="form-label">Tanggal Pengeluaran</label>
                                    <input class="form-control" id="tgl" type="date" name="tgl">
                                </div>
                                <div class="mb-2">
                                    <label for="jenis" class="control-label">Jenis Kas</label>
                                    <select name="jenis" id="jenis" class="form-control">
                                        <option value="">Pilih Jenis Kas</option>
                                        <option value="1">Kas Kecil</option>
                                        <option value="2">Kas Besar</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="judul" class="control-label">Nama Pengeluaran</label>

                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Nama Pengeluaran" required="">

                                </div>
                                <div class="mb-2">
                                    <label for="biaya" class="control-label">Biaya Dikeluarkan</label>

                                    <input type="text" class="form-control" id="biaya" name="biaya"
                                        placeholder="Rp. xxxxxxx" required="">

                                </div>
                                <div class="mb-2">
                                    <label for="deskripsi" class="control-label">Deskripsi</label>

                                    <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Modal -->
    <div class="modal fade" id="verifikasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h5 class="modal-title" id="staticBackdropLabel">Verifikasi Pengeluaran</h5>
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
                        <button type="button" class="btn btn-danger aksiBtn" data-id="2">Tolak</button>
                        <button type="button" class="btn btn-primary aksiBtn" data-id="1">Setuju</button>
                    </div> <!-- end modal footer -->
                </form>
            </div> <!-- end modal content-->
        </div> <!-- end modal dialog-->
    </div> <!-- end modal-->




    <script type="text/javascript">
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on("keyup", "#biaya", function() {
                // Format mata uang.
                $('#biaya').mask('0.000.000.000', {
                    reverse: true
                });
            })

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            load_data();

            function load_data(from_date = '', to_date = '', persetujuan = '', jenis = '') {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    retrieve: true,
                    paging: true,
                    destroy: true,
                    "lengthMenu": [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    "scrollX": false,
                    ajax: {
                        url: "{{ url('/pengeluaranTable') }}",
                        type: "POST",
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            persetujuan: persetujuan,
                            jenis_kas: jenis
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'judul',
                            name: 'judul'
                        },
                        {
                            data: 'tgl',
                            name: 'tgl'
                        },
                        {
                            data: 'jenis_kas',
                            name: 'jenis_kas'
                        },
                        {
                            data: 'biaya',
                            name: 'biaya'
                        },
                        {
                            data: 'deskripsi',
                            name: 'deskripsi'
                        },
                        {
                            data: 'persetujuan',
                            name: 'persetujuan'
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

            $('#filter').click(function() {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var persetujuan = $('#persetujuan').val();
                var jenis = $('#jenis').val();
               
                    $('#datatable').DataTable().destroy();
                    load_data(from_date, to_date, persetujuan, jenis);
                    $("#print").val('Print');
                    $("#export").val('Export');
                
            });

            $('#refresh').click(function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#persetujuan').val('');
                $('#jenis').val('');
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('body').on('click', '#btnVerifikasi', function() {
                id = $(this).data('id_pengeluaran');
                $('#verifikasiForm').trigger("reset");
                $('#verifikasiId').val(id);
                $('#verifikasi').modal('show');
            });

            $('.aksiBtn').click(function(e) {
                e.preventDefault();
                aksi = $(this).data('id');
                if (aksi == 1) {
                    url = "{{ url('/verifikasi-pengeluaran') }}" + '/' + aksi
                } else
                if (aksi == 2) {
                    url = "{{ url('/verifikasi-pengeluaran') }}" + '/' + aksi
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
                            swal("Pesan", data.message, "success");
                        } else {
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
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });



            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('#createNewPengeluaran').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_pengeluaran').val('');
                $('#pengeluaranForm').trigger("reset");
                $('#modelHeading').html("Tambah Pengeluaran ");
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');

                var form = $('#pengeluaranForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/pengeluaran/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#pengeluaranForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            $('#datatable').DataTable().destroy();
                            load_data();
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
                            $('#saveBtn').html('Simpan');
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
                            $('#saveBtn').html('Simpan');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });

            $('body').on('click', '.editPengeluaran', function() {

                var id_pengeluaran = $(this).data('id_pengeluaran');
                $.get("{{ url('/pengeluaran') }}" + '/' + id_pengeluaran + '/edit', function(data) {
                    $('#modelHeading').html("Ubah Pengeluaran ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $('#id_pengeluaran').val(data.id);
                    $('#tgl').val(data.tgl);
                    $('#jenis').val(data.jenis_kas);
                    $('#judul').val(data.judul);
                    $('#biaya').val(data.biaya);
                    $('#persetujuan').val(data.persetujuan);
                    $('#deskripsi').val(data.deskripsi);
                })

            });

            $('body').on('click', '.deletePengeluaran', function() {

                var id_pengeluaran = $(this).data("id_pengeluaran");
                swal({
                        title: "Yakin hapus data ini?",
                        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Hapus Data!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $("#canvasloading").show();
                            $("#loading").show();
                            $.ajax({
                                type: "DELETE",
                                url: "{{ url('/pengeluaran') }}" + '/' + id_pengeluaran,
                                success: function(data) {
                                    $('#datatable').DataTable().destroy();
                                    load_data();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Deleted!", "Data Berhasil Dihapus...!",
                                        "success");
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Pesan", data.message, "error");
                                }
                            });

                        } else {
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                        }
                    });
            });




        });
    </script>
@endsection
