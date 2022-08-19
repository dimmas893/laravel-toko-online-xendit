@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Manajemen Stock</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            @if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN')
                                <button type="button" id="btnStock" class="btn btn-primary mb-2" data-bs-toggle="modal"
                                    data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                    Stock</button>
                            @endif
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="state-saving-preview">
                            <table id="datatable" class="table dt-responsive nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Jenis Jual</th>
                                        <th>Jumlah</th>
                                        <th>Harga Modal</th>
                                        <th>Harga Jual</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <!-- <th>Aksi</th> -->
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="modalStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-filled bg-primary">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Manajemen Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding-bottom: 0">
                    <div class="dropdown">
                        <form>
                            <label for="top-search">Masukkan Nama Produk</label>
                            <input type="text" class="form-control" placeholder="Cari Produk" id="top-search">


                        </form>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown"
                            style="max-height: 300px; width: 100%; overflow: scroll;">

                            <div id="resultSearch" class="notification-list">


                            </div>
                        </div>
                    </div>
                </div>


                <form id="formUpdateStock">
                    @csrf
                    <input type="hidden" id="produkId" name="produkId">
                    <div class="modal-body">
                        <div class="mb-2">
                            <label for="statusStock">Status</label>
                            <select name="statusStock" class="form-control" id="statusStock">
                                <option value="">Pilih Status</option>
                                <option value="2">Tambah</option>
                                <option value="4">Pengurangan</option>
                                <option value="5">Rusak</option>
                            </select>
                            <small>
                                NB: Status<br>
                                <ul>
                                    <li>Tambah : Menambah Stock Produk</li>
                                    <li>Pengurangan : Mengurangi Stock Untuk Keperluan Tertentu</li>
                                    <li>Rusak : Mengurangi Stock Karna Rusak</li>
                                </ul>
                            </small>
                        </div>
                        <div class="mb-2">
                            <label for="jumlahStock">Jumlah</label>
                            <input type="number" class="form-control" name="jumlahStock" min="1" id="jumlahStock">
                        </div>
                        <div class="mb-2">
                            <label for="harga">Harga Modal</label>
                            <input type="text" class="form-control" name="harga" id="harga">
                        </div>

                        <div class="mb-2 edt2">
                            <label for="harga_jual">Harga Jual</label>
                            <input type="text" class="form-control" name="harga_jual" id="harga_jual">
                        </div>

                        <div class="mb-2">
                            <label for="deskripsi">Keterangan</label>
                            <textarea name="deskripsi" id="deskripsi" required class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalStock"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                        <a href="javascript:void(0);" id="saveBtn" class="btn btn-success">Simpan</a>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script type="text/javascript">
        $(document).ready(function() {
            //ajax setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // datatable
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                retrieve: true,
                paging: true,
                destroy: true,
                "scrollX": false,
                ajax: {
                    url: "{{ url('/stockTable') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'nama_produk',
                        name: 'nama_produk'
                    },
                    {
                        data: 'nama_kategori',
                        name: 'nama_kategori'
                    },
                    {
                        data: 'jenis_jual',
                        name: 'jenis_jual'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'harga',
                        name: 'harga'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     searchable: false
                    // },
                ]
            });

            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });

            $(".edt2").css("display", 'none');


            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');

                var form = $('#formUpdateStock')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: "{{ url('/stock/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#formUpdateStock').trigger("reset");
                            $('#modalStock').modal('hide');
                            $(".edt2").css("display", 'none');
                            table.draw();
                            $('#saveBtn').html('Simpan');
                            swal("Pesan", data.message, "success");
                        } else {
                            $('#saveBtn').html('Simpan');
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




            //Cari Produk
            $('body').on('click keyup', '#top-search', function() {
                $("#resultSearch").html('')
                var nama_produk = $("#top-search").val();
                $.get("{{ url('/cari-produk-stock') }}" + '/' + nama_produk, function(data) {
                    $("#resultSearch").html(data)
                })
            });

            $("body").on("click", "#btnStock", function() {
                $("#top-search").val('');
                $("#produkId").val('');
                $("#harga").val('');
                $("#harga_jual").val('');
                $("#jumlahStock").val('');
                $("#statusStock").val('');
                $("#deskripsi").val('');
                $(".edt2").css("display", 'none');
                $('#modalStock').modal('show');
            })

            $("body").on("click", "#addStock", function() {
                produkId = $(this).data('id');
                $("#produkId").val(produkId);
                $.get("{{ url('/get-produk-stock') }}" + '/' + produkId, function(data) {
                    $("#top-search").val(data.nama_produk);
                    $("#harga").val(data.harga);
                    $("#harga_jual").val(data.harga_jual);
                    if (data.jenis_jual == 1) {
                        $(".edt2").css("display", 'block');
                    } else {
                        $(".edt2").css("display", 'none');
                    }
                    swal("Pesan", "Data Dipilih", "success");
                })
            })

        });
    </script>
@endsection
