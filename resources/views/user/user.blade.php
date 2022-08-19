@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Pengguna</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title with-border">Pengguna</h4>
               
				@if(auth()->user()->level=='ADMIN')
				<button type="button" id="createNewPengguna" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i> Tambah</button>
 				@endif
                <div class="tab-content">
                    <div class="tab-pane show active" id="state-saving-preview">
                        <table id="datatable"
                            class="table activate-select dt-responsive nowrap table-striped">
                            <thead>
                                <tr>
                                    <td width="20px">No</td>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
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






<!-- Success Header Modal -->

<div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title" id="success-header-modalLabel">Pengguna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="penggunaForm" name="penggunaForm" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_pengguna" id="id_pengguna">
<div class="row">
    <div class="col-md-6">
                    <div class="mb-2">
                        <label for="nama_lengkap" class="control-label">Nama Lengkap <span
                                style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required="">

                    </div>
                    <div class="mb-2">
                        <label for="email" class="control-label">Alamat Email <span
                                style="color: red;">*</span></label>

                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="youremail@mail.com" required="">

                    </div>
                    <div class="mb-2">
                        <label for="level" class="control-label">Level Pengguna <span
                            style="color: red;">*</span></label>
                        <select name="level" class="form-control" id="level">
                            <option value="">Pilih Level</option>
                            <option value="ADMIN">Admin</option>
                            <option value="CEO">CEO</option>
                            <option value="STAFF">Pegawai</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="username" class="control-label">Username <span
                                style="color: red;">*</span></label>

                        <input type="text" class="form-control" id="username" name="username" required="">

                    </div>
    </div>
    <div class="col-md-6">
                    <div class="mb-2">
                        <label for="password" class="control-label">Password <span
                                style="color: red;">*</span></label>

                        <input type="password" name="password" id="password" class="form-control">

                    </div>
					<div class="mb-2">
                        <label for="repassword" class="control-label">Ulangi Password <span
                                style="color: red;">*</span></label>

                        <input type="password" name="repassword" id="repassword" class="form-control">

                    </div>
                    <div class="mb-2">
                        <label for="file" class="control-label">Upload Foto Profil </label>
                        <input type="file" id="file" name="file" class="form-control">
                        <div class="mb-2">
                            <img id="preview-image-before-upload" alt="Preview Image"
                                style="max-height: 100px; max-width:350px">
                        </div>
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
            url: "{{ url('/userTable') }}",
            type: "POST",

        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'level',
                name: 'level'
            },
            {
                data: 'foto',
                name: 'foto'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });

    $('#createNewPengguna').click(function() {
        $('#saveBtn').html("Simpan");
        $('#id_pengguna').val('');
        $('#penggunaForm').trigger("reset");
        $('#modelHeading').html("Tambah Pengguna ");
        $('#ajaxModel').modal('show');
    });

    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('Menyimpan..');

        var form = $('#penggunaForm')[0];
        var formData = new FormData(form);
        $.ajax({
            data: formData,
            url: "{{ url('/user/simpan')}}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.success == true) {
                    $('#penggunaForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    $('#saveBtn').html('Simpan');
                    swal("Pesan",data.message,"success");
                } else {
                    $('#saveBtn').html('Simpan');
                    swal("Pesan",data.message,"error");
                }
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    err = '';
                    $.each(res.errors, function(key, value) {
                        // err += value + ', ';
                        err=value;
                    });
                    $('#saveBtn').html('Simpan');
                    $("#canvasloading").hide();
                    $("#loading").hide();
                    swal("Pesan",err,"error");
                }
            }
        });
    });

    $('body').on('click', '.editPengguna', function() {

        var id_pengguna = $(this).data('id_pengguna');
        $.get("{{ url('/user') }}" + '/' + id_pengguna + '/edit', function(data) {
            $('#modelHeading').html("Ubah Pengguna ");
            $('#saveBtn').html('Perbaharui');
            $('#ajaxModel').modal('show');
            $('#id_pengguna').val(data.id);
            $('#nama_lengkap').val(data.name);
            $('#email').val(data.email);
            $('#username').val(data.username);
            $('#level').val(data.level);
        })

    });

    $('body').on('click', '.deletePengguna', function() {

        var id_pengguna = $(this).data("id_pengguna");
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
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('/user') }}" + '/' + id_pengguna,
                        success: function(data) {
                            table.draw();
                            swal("Deleted!", "Data Berhasil Dihapus...!", "success");
                        },
                        error: function(data) {
                            console.log('Error:', data);
                            swal("Pesan",data.message,"error");
                        }
                    });

                } else {
                    swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                }
            });
    });

});
</script>
@endsection