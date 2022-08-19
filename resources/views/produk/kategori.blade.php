@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Kategori</h4>
        </div>
    </div>
</div>
<ul class="nav nav-tabs nav-bordered mb-3">
    <li class="nav-item">
        <a href="#dataKategori" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
            <i class="mdi mdi-home-variant d-md-none d-block"></i>
            <span class="d-none d-md-block">Kategori</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#dataTrash" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-account-circle d-md-none d-block"></i>
            <span class="d-none d-md-block">Sampah</span>
        </a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane show active" id="dataKategori">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        @if(auth()->user()->level=='STAFF' or auth()->user()->level=='ADMIN')
                        <div class="mb-2">
                            <button type="button" id="createNewKategori" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i>
                                Tambah</button>
                            <button type="button" name="bulk_delete" id="bulk_delete"
                                class="btn btn-danger float-end">Hapus
                                Pilihan</button>
                        </div>
                        @endif
                        <div class="table-responsive">
                            <table id="datatable" class="table table-centered w-100 dt-responsive nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th><input type="checkbox" id="master"></th>
                                        <th>Nama Kategori</th>
                                        <th>Icon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="dataTrash">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatableTrash"
                                class="table table-centered w-100 dt-responsive nowrap table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="all">Nama Kategori</th>
                                        <th>Icon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
    aria-labelledby="success-header-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title" id="success-header-modalLabel">Kategori</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="kategoriForm" name="kategoriForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_kategori" id="id_kategori">

                    <div class="mb-2 kategori-group">
                        <label for="nama_kategori" class="col-sm-3 control-label">Nama Kategori <span
                                style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori"
                            placeholder="Masukkan Nama Kategori" required="">

                    </div>

                    <div class="mb-2">
                        <label for="file" class="control-label">Pilih Icon</label>

                        <input type="file" class="form-control" id="file" name="file" required="">
                        <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .ico / .png / .jpg /
                                .jpeg</b> |
                            Maksimal Ukuran 200KB</small>
                        <div class="mb-2">
                            <img id="preview-image-before-upload" alt="Preview Image"
                                style="max-height: 100px; max-width:350px">
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



<script type="text/javascript">
$(document).ready(function(e) {


    $('#file').change(function() {

        let reader = new FileReader();

        reader.onload = (e) => {

            $('#preview-image-before-upload').attr('src', e.target.result);
        }

        reader.readAsDataURL(this.files[0]);

    });

});


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
            url: "{{ url('/kategoriTable') }}",
            type: "POST",

        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'select',
                name: 'select',
                orderable: false,
                searchable: false
            },
            {
                data: 'nama_kategori',
                name: 'nama_kategori'
            },
            {
                data: 'icon',
                name: 'icon'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });


    // datatable
    var tableTrashed = $('#datatableTrash').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        retrieve: true,
        paging: true,
        destroy: true,
        "scrollX": false,
        ajax: {
            url: "{{ url('/kategoriTableTrash') }}",
            type: "POST",

        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'nama_kategori',
                name: 'nama_kategori'
            },
            {
                data: 'icon',
                name: 'icon'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });



    $('#master').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".select").prop('checked', true);
        } else {
            $(".select").prop('checked', false);
        }
    });




    $('#createNewKategori').click(function() {
        $('#saveBtn').html("Simpan");
        $('#id_kategori').val('');
        $('#kategoriForm').trigger("reset");
        $('#modelHeading').html("Tambah Kategori ");
        $('#ajaxModel').modal('show');
    });

    $('#saveBtn').click(function(e) {
        e.preventDefault();
        nama_kategori = $("#nama_kategori").val();
        id = $('#id_kategori').val();
        file = $("#file").val();
        if (nama_kategori == '') {
            Swal.fire({
                title: 'Error!',
                text: 'Nama Kategori Tidak Boleh Kosong!',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        } else if (id == '' && file == '') {
            Swal.fire({
                title: 'Error!',
                text: 'File Gambar Tidak Boleh Kosong!',
                icon: 'error',
                confirmButtonText: 'OK'
            })
        } else {


            $(this).html('Menyimpan..');

            var form = $('#kategoriForm')[0];
            var formData = new FormData(form);
            $("#canvasloading").show();
            $("#loading").show();
            $.ajax({
                data: formData,
                url: "{{ url('/kategori/simpan')}}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success == true) {

                        $('#kategoriForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data Berhasil Disimpan!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })
                    } else {
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                        err = '';
                        $.each(res.errors, function(key, value) {
                            err += value + ', ';
                        });
                        $('#saveBtn').html('Simpan');
                        $("#canvasloading").hide();
                        $("#loading").hide();
                        Swal.fire({
                            title: 'Error!',
                            text: err,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            });
        }
    });

    $('body').on('click', '.editKategori', function() {

        var id_kategori = $(this).data('id_kategori');
        $("#canvasloading").show();
        $("#loading").show();
        $.get("{{ url('/kategori') }}" + '/' + id_kategori + '/edit', function(data) {
            $("#canvasloading").hide();
            $("#loading").hide();
            $('#modelHeading').html("Ubah Kategori ");
            $('#saveBtn').html('Perbaharui');
            $('#ajaxModel').modal('show');
            $('#id_kategori').val(data.id);
            $('#nama_kategori').val(data.nama_kategori);
        })

    });

    $('body').on('click', '.deleteKategori', function() {
        var id_kategori = $(this).data("id_kategori");
        status = $(this).data("status");

        myurl = '';
        if (status == 'trash') {
            myurl = "{{ url('/kategori-trash') }}" + '/' + id_kategori
            msg =
                "Data Kategori yang dihapus akan dipindahkan ke Tempat Sampah!";
        } else if (status == 'delete') {
            myurl = "{{ url('/kategori-delete') }}" + '/' + id_kategori
            msg =
                "Data Kategori yang dihapus tidak dapat dikembalikan!";
        }
        swal({
                title: "Yakin hapus data ini?",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus Data!",
                cancelButtonText: "Batal!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        type: "get",
                        url: myurl,
                        success: function(data) {
                            if(data.success==true){
                            table.draw();
                            tableTrashed.draw();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                        }else{
                            table.draw();
                            tableTrashed.draw();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi Kesalahan',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    });

                } else {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    Swal.fire({
                        title: 'Info!',
                        text: 'Hapus Data Dibatalkan',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                }
            });
    });


    $('body').on('click', '.restoreKategori', function() {

        var id_kategori = $(this).data("id_kategori");
        swal({
                title: "Kembalikan Kategori ini?",
                text: "Data akan dikembalikan ke tabel Kategori!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Kembalikan!",
                cancelButtonText: "Batal!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $("#canvasloading").show();
                    $("#loading").show();
                    $.ajax({
                        type: "get",
                        url: "{{ url('/kategori-restore') }}" + '/' + id_kategori,
                        success: function(data) {
                            table.draw();
                            tableTrashed.draw();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Success!", "Data Berhasil Dikembalikan...!",
                                "success");
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            $("#canvasloading").hide();
                            $("#loading").hide();
                        }
                    });

                } else {
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    swal("Cancelled", "Kategori gagal Dikembalikan...! :)", "error");
                }
            });
    });

    $(document).on('click', '#bulk_delete', function() {
        var id = [];
        swal({
                title: "Yakin hapus data yang dipilih?",
                text: "Data yang dihapus akan dipindahkan ke Tempat Sampah!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.select:checked').each(function() {
                        id.push($(this).val());
                    });
                    if (id.length > 0) {
                        $.ajax({
                            url: "{{url('/kategori/bulk-delete')}}",
                            method: "get",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                table.draw();
                                tableTrashed.draw();
                                $("#canvasloading").hide();
                                $("#loading").hide();
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Data Berhasil Dihapus',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                })
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Silahkan Pilih Data Yang Akan Dihapus...!!!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            });
    });

    // $('.chosen-select').chosen({width: "100%"});
});
</script>
@endsection