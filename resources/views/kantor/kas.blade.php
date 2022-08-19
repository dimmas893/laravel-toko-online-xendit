@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-12">
        <div class="page-title-box">

            <h4 class="page-title">Kas</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
            @if(auth()->user()->level=='CEO' or auth()->user()->level=='ADMIN')
                <button type="button" id="createNewKas" class="btn btn-primary mb-2" data-bs-toggle="modal"
                    data-bs-target="#success-header-modal"><i class="mdi mdi-plus-circle me-2"></i> Tambah</button>
                    @endif
                <div class="tab-content">
                    <div class="tab-pane show active" id="state-saving-preview">
                        <table id="datatable" class="table dt-responsive nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Kas</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Saldo</th>
                                    <th>Sumber</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
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

<div id="ajaxModel" class="modal fade"  data-bs-backdrop="static" data-bs-keyboard="false"  tabindex="-1" role="dialog" aria-labelledby="success-header-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-success">
                <h4 class="modal-title" id="success-header-modalLabel">Kas</h4>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form id="kasForm" name="kasForm" class="form-horizontal">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_kas" id="id_kas">
                    <p>Hanya untuk penambahan saldo.</p>
                    <div class="mb-2">
                        <label for="tgl" class="form-label">Tanggal</label>
                        <input class="form-control" id="tgl" type="date" name="tgl">
                    </div>
                    <div class="mb-2">
                        <label for="jenis" class="control-label">Jenis Kas <span
                                style="color: red;">*</span></label>
                        <select name="jenis" id="jenis" class="form-control">
                            <option value="">Pilih Jenis Kas</option>
                            <option value="1">Kas Kecil</option>
                            <option value="2">Kas Besar</option>
                        </select>    
                    </div>
                    <div class="mb-2">
                        <label for="nominal" class="control-label">Nominal Penambahan <span
                                style="color: red;">*</span></label>
                        <input type="text" class="form-control" id="nominal" name="nominal" required="">
                    </div>
                    <div class="mb-2">
                        <label for="deskripsi" class="col-sm-3 control-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id="deskripsi" cols="30" rows="3"></textarea>    
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
$(document).ready(function() {
    //ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on("keyup", "#nominal", function() {
        // Format mata uang.
        $('#nominal').mask('0.000.000.000', {
            reverse: true
        });
    })

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
            url: "{{ url('/kasTable') }}",
            type: "POST",

        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'jenis',
                name: 'jenis'
            },
            {
                data: 'debit',
                name: 'debit'
            },
            {
                data: 'kredit',
                name: 'kredit'
            },
            {
                data: 'nominal',
                name: 'nominal'
            },
            {
                data: 'sumber',
                name: 'sumber'
            },
            {
                data: 'deskripsi',
                name: 'deskripsi'
            },
            {
                data: 'tgl',
                name: 'tgl'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });



    $('#createNewKas').click(function() {
        $('#saveBtn').html("Simpan");
        $('#id_kas').val('');
        $('#kasForm').trigger("reset");
        $('#modelHeading').html("Tambah Kas ");
        $('#ajaxModel').modal('show');
    });

    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('Menyimpan..');

        var form = $('#kasForm')[0];
        var formData = new FormData(form);
       $.ajax({
            data: formData,
            url: "{{ url('/kas/simpan')}}",
            type: "POST",
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.success == true) {
          $('#kasForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    table.draw();
                    $('#saveBtn').html('Simpan');
                    swal("Pesan",data.message,"success");
                } else {
                    $('#saveBtn').html('Simpan');
                    swal("Pesan",data.message,"error");
                }
            },
            error : function (xhr) {
            var res = xhr.responseJSON;
            if ($.isEmptyObject(res) == false) {
                err='';
                $.each(res.errors, function (key, value) {
                    err+=value+', ';
                });
                $('#saveBtn').html('Simpan');
                swal("Pesan",err,"error");
            }
        }
        });
    });


  
});
</script>
@endsection