@extends("layouts.app")

@section('content')
    <script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Produk</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <ul class="nav nav-tabs nav-bordered mb-3">
        <li class="nav-item">
            <a href="#dataProduk" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Produk</span>
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
        <div class="tab-pane show active" id="dataProduk">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-2">
                                <div class="col-sm-4">
                                    @if (auth()->user()->level == 'STAFF' or auth()->user()->level == 'ADMIN')
                                        <button type="button" id="createNewProduk" class="btn btn-primary mb-2"
                                            data-bs-toggle="modal" data-bs-target="#success-header-modal"><i
                                                class="mdi mdi-plus-circle me-2"></i>
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
                            <form action="/print-laporan-produk" id="form-filter-produk" target="_blank" method="post">
                                @csrf
                                <h4>Filter</h4>
                                <div class="row">

                                    <div class="col-md-9">
                                        <label for="filter-kategori">Kategori</label>
                                        <select name="filter-kategori" id="filter-kategori" class="form-control select2kat">
                                            <option value="">Semua</option>
                                            @foreach ($kategori as $kat)
                                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        <label for="filter-jenis-jual">Jenis Jual</label>
                                        <select name="filter-jenis-jual" id="filter-jenis-jual"
                                            class="form-control select2jenis">
                                            <option value="">Semua</option>
                                            <option value="1">Online</option>
                                            <option value="2">Offline</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Aksi</label>
                                        <div class="form-group">
                                            <a id="filter" class="btn btn-primary mb-2">Filter</a>
                                            <a id="refresh" class="btn btn-info mb-2">Refresh</a><br>
                                            {{-- <input type="submit" name="print" value="Print" class="btn btn-success">
                                            <input type="submit" name="export" value="Excel" class="btn btn-warning">
                                            <input type="submit" name="pdf" value="PDF" class="btn btn-danger"> --}}
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
                                            <th style="width: 85px;">Aksi</th>
                                            <th>SKU</th>
                                            <th class="all">Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Merek</th>
                                            <th>Harga Modal</th>
                                            <th>Harga Jual</th>
                                            <th>Stock</th>
                                            <th>Jenis Jual</th>
                                            <th>Qr Code</th>
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
        </div>


        <div class="tab-pane" id="dataTrash">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-centered w-100 dt-responsive nowrap table-striped"
                                    id="datatableTrash">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 85px;">Aksi</th>
                                            <th>SKU</th>
                                            <th class="all">Nama Produk</th>
                                            <th>Kategori</th>
                                            <th>Merek</th>
                                            <th>Harga Modal</th>
                                            <th>Harga Jual</th>
                                            <th>Stock</th>
                                            <th>Jenis Jual</th>
                                            <th>Qr Code</th>
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
        </div>
    </div>


    <!-- Success Header Modal -->

    <div id="ajaxModel" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="success-header-modalLabel">Produk</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="produkForm" name="produkForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id_produk" id="id_produk">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2 produk-group">
                                    <label for="kode_produk" class="control-label">Kode Produk  <span
                                        style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="kode_produk" name="kode_produk"
                                        required="">

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="nama_kategori" class="control-label">Kategori  <span
                                        style="color: red;">*</span></label>

                                    <!-- Single Select -->
                                    <select id="kategori" name="kategori" class="form-control select2"
                                        data-toggle="select2">
                                        <option value="">Select</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                        @endforeach
                                        </optgroup>
                                    </select>

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="nama_produk" class="control-label">Nama Produk  <span
                                        style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                        placeholder="Nama Produk" required="">

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="slug" class="control-label">Slug Produk  <span
                                        style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="slug" name="slug"
                                        placeholder="Slug Produk" readonly required="">

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="merek" class="control-label">Merek</label>

                                    <input type="text" class="form-control" id="merek" name="merek"
                                        placeholder="Merek Produk" required="">

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="satuan" class="control-label">Satuan  <span
                                        style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="satuan" name="satuan"
                                        placeholder="Masukkan Satuan Produk" required="">

                                </div>


                            </div>
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="jenis_jual" class="control-label">Jenis Jual  <span
                                        style="color: red;">*</span></label>
                                    <select name="jenis_jual" class="form-control" required id="jenis_jual">
                                        <option value="">Pilih</option>
                                        <option value="1">Online</option>
                                        <option value="2">Offline</option>
                                        {{-- <option value="3">Online & Offline</option> --}}
                                    </select>
                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="harga" class="control-label">Harga Modal / Satuan  <span
                                        style="color: red;">*</span></label>

                                    <input type="text" class="form-control" id="harga" name="harga"
                                        placeholder="Rp. xxxxxxx" required="">

                                </div>
                                <div class="mb-2 produk-group inputHargaJual">
                                    <label for="harga_jual" class="control-label">Harga Jual / Satuan</label>

                                    <input type="text" class="form-control" id="harga_jual" name="harga_jual"
                                        placeholder="Rp. xxxxxxx" required="">

                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="jumlah" class="control-label">Jumlah Stock  <span
                                        style="color: red;">*</span></label>

                                    <input type="number" class="form-control mb-2" id="jumlah" name="jumlah" min="1"
                                        required="">
                                    <!-- <a href="javascript:void(0);" id="btnModalStock" class="btn btn-info btn-xs">Tambah Stock</a> -->
                                </div>
                                <div class="mb-2 produk-group">
                                    <label for="berat" class="control-label">Berat (Gram)  <span
                                        style="color: red;">*</span></label>

                                    <input type="number" class="form-control mb-2" id="berat" name="berat" min="1"
                                        required="">
                                    <!-- <a href="javascript:void(0);" id="btnModalStock" class="btn btn-info btn-xs">Tambah Stock</a> -->
                                </div>

                               

                                <div class="mb-2 produk-group">
                                    <label for="file" class="control-label">Pilih Gambar Produk<span class="edt1" style="color: red;">*</span></label>
                                    <input type="file" class="form-control" id="file" name="file">
                                    <small class="edt2" style="display: none;">Kosongkan jika ingin menggunakan
                                        Gambar
                                        sebelumnya</small>

                                    <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .jpg / .jpeg
                                            /
                                            PNG</b>
                                        | Maksimal Ukuran 1MB</small>
                                    <div class="mb-2">
                                        <img id="preview-image-before-upload" alt="Preview Image"
                                            style="max-height: 100px;">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-2 produk-group">
                                    <label for="deskripsi" class="control-label">Deskripsi  <span
                                        style="color: red;">*</span></label>

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
    <div class="modal fade" id="modalStock" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-filled bg-primary">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Tambah Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formUpdateStock">
                    @csrf
                    <input type="hidden" id="produkId" name="produkId">
                    <div class="modal-body">
                        <label for="jumlahStock">Jumlah</label>
                        <input type="number" class="form-control" name="jumlahStock" id="jumlahStock">
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-danger" data-bs-target="#modalStock"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</a>
                        <a href="javascript:void(0);" id="btnUpdateStock" class="btn btn-outline-light">Update Stock</a>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal -->
    <div class="modal fade" id="gambarModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Gambar Produk Multi Upload</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="gambarForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="dataId" name="dataId">
                        <div class="form-group">
                            <small>Hanya diperbolehkan dengan extensi : <b style="color: darkred;"> .jpg / .jpeg
                                    /
                                    PNG</b>
                                | Maksimal Ukuran 1MB / 1 Gambar Produk</small>
                            <input type="file" id="file" name="file[]" multiple class="form-control" required
                                accept="image/*">
                            <code>Setiap Produk Hanya Boleh 3 Gambar Tambahan</code>
                            @if ($errors->has('files'))
                                @foreach ($errors->get('files') as $error)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $error }}</strong>
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group float-end">
                            <button type="button" class="btn btn-success mt-2" id="saveGambar">Upload</button>
                            <button type="reset" data-bs-target="#gambarModal" data-bs-toggle="modal"
                                data-bs-dismiss="modal" class="btn btn-danger mt-2">Batal</button>
                        </div>
                    </form>

                    <h3 style="margin-top:70px;">Gambar Produk</h3>
                    <div class="row text-center text-lg-start list-gambar-produk">
                    </div>
                </div>

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

            CKEDITOR.replace('deskripsi');


            $('body').on("keyup", "#harga", function() {
                // Format mata uang.
                $('#harga').mask('0.000.000.000', {
                    reverse: true
                });
            })

            $('body').on("keyup", "#harga_jual", function() {
                // Format mata uang.
                $('#harga_jual').mask('0.000.000.000', {
                    reverse: true
                });
            })


            load_data();

            function load_data(jenis_jual = '', kategori = '') {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    retrieve: true,
                    paging: true,
                    destroy: true,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],
                    scrollX: false,
                    ajax: {
                        url: "{{ url('/produkTable') }}",
                        type: "POST",
                        data: {
                            jenis_jual: jenis_jual,
                            kategori: kategori,
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        }, {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode_produk',
                            name: 'kode_produk'
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
                            data: 'merek',
                            name: 'merek'
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
                            data: 'jumlah',
                            name: 'jumlah'
                        },
                        {
                            data: 'jenis_jual',
                            name: 'jenis_jual'
                        },
                        {
                            data: 'qr',
                            name: 'qr'
                        },

                    ]
                });
                table.draw();
            }



            $("body").on("change","#jenis_jual",function(){
                let jenis=$(this).val();
                if(jenis==1){
                    $('.inputHargaJual').css('display','block');
                }else{
                    $('.inputHargaJual').css('display','none');
                }
            })

            $('#filter').click(function() {
                var kategori = $('#filter-kategori').val();
                var jenis_jual = $('#filter-jenis-jual').val();

                $('#datatable').DataTable().destroy();
                load_data(jenis_jual, kategori);
                $("#print").val('Print');
                $("#export").val('Export');

            });

            

            $('#refresh').click(function() {
                $('#form-filter-produk').trigger("reset");
                $("#filter-kategori").select2("trigger", "select", {
                    data: {
                        id: ''
                    }
                });
                $('#datatable').DataTable().destroy();
                load_data();
            });

            $('.select2').select2({
                dropdownParent: $('#ajaxModel')
            });

            $('.select2kat').select2();

            $("body").on("keyup release focusout change", "#nama_produk", function() {
                nama_produk = $("#nama_produk").val();
                nama_produk = nama_produk.replace(/ /g, '-');
                $("#slug").val(nama_produk);
            })

            $('#createNewProduk').click(function() {
                $('#saveBtn').html("Simpan");
                $('#id_produk').val('');
                $(".edt1").css('display','block');
                $(".edt2").css('display','none');
                $('.inputHargaJual').css('display','none');
                $("#btnModalStock").css('display', 'none');
                $('#produkForm').trigger("reset");
                CKEDITOR.instances['deskripsi'].setData('');
                $('#preview-image-before-upload').attr('src', '');
                $('#modelHeading').html("Tambah Produk ");
                $("#jumlah").prop('disabled', false);
                $('#ajaxModel').modal('show');
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Menyimpan..');
                for (instance in CKEDITOR.instances) {
                    $('#' + instance).val(CKEDITOR.instances[instance].getData());
                }
                var form = $('#produkForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/produk/simpan') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success === true) {

                            $('#produkForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
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


            $('#btnModalStock').click(function() {
                $('#modalStock').modal('show');
            });


            $('#btnUpdateStock').click(function(e) {
                e.preventDefault();
                $(this).html('Silahkan Tunggu..');

                var form = $('#formUpdateStock')[0];
                var formData = new FormData(form);
                $.ajax({
                    data: formData,
                    url: "{{ url('/produk/update-stock') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {
                            $('#btnUpdateStock').html('Update Stock');
                            $('#produkForm').trigger("reset");
                            $('#formUpdateStock').trigger("reset");
                            $('#modalStock').modal('hide');
                            $('#ajaxModel').modal('hide');
                            load_data();
                            $('#saveBtn').html('Simpan');
                            swal("Pesan", data.message, "success");
                        } else {
                            $('#btnUpdateStock').html('Update Stock');
                            swal("Pesan", data.message, "error");
                        }
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#btnUpdateStock').html('Update Stock');
                        swal("Pesan", data.message, "error");
                    }
                });
            });


            $('body').on('click', '.editProduk', function() {
                $('#preview-image-before-upload').attr('src', '');
                var id_produk = $(this).data('id_produk');
                $.get("{{ url('/produk') }}" + '/' + id_produk + '/edit', function(data) {
                    $('#modelHeading').html("Ubah Produk ");
                    $('#saveBtn').html('Perbaharui');
                    $('#ajaxModel').modal('show');
                    $("#jumlah").prop('readonly', true);
                    $("#btnModalStock").css('display', 'block');
                    $('#id_produk').val(data.id);
                    $('#nama_produk').val(data.nama_produk);
                    $('#slug').val(data.slug);
                    $('#kode_produk').val(data.kode_produk);
                    $('#merek').val(data.merek);
                    $('#satuan').val(data.satuan);
                    $('#harga').val(data.harga);
                    $('#harga_jual').val(data.harga_jual);
                    $('#jumlah').val(data.jumlah);
                    $('#berat').val(data.berat);
                    $('#jenis_jual').val(data.jenis_jual);
                    CKEDITOR.instances['deskripsi'].setData(data.deskripsi);
                    $("#kategori").select2("trigger", "select", {
                        data: {
                            id: data.kategori_id
                        }
                    });

                    
                if(data.jenis_jual==1){
                    $('.inputHargaJual').css('display','block');
                }else{
                    $('.inputHargaJual').css('display','none');
                }
                    
                $(".edt1").css('display','none');
                    $(".edt2").css('display','block');


                })

            });

            $('body').on('click', '.deleteProduk', function() {
                var id_produk = $(this).data("id_produk");
                status = $(this).data("status");

                myurl = '';
                if (status == 'trash') {
                    myurl = "{{ url('/produk-trash') }}" + '/' + id_produk
                    msg =
                        "Data Produk yang dihapus akan dipindahkan ke Tempat Sampah termasuk Data Stock dari Produk ini! ";
                } else if (status == 'delete') {
                    myurl = "{{ url('/produk-delete') }}" + '/' + id_produk
                    msg =
                        "Data Produk yang dihapus tidak dapat dikembalikan termasuk Data Stock dari Produk ini!";
                }
                swal({
                        title: "Yakin hapus data ini?",
                        text: msg,
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
                                type: "get",
                                url: myurl,
                                success: function(data) {
                                    load_data();
                                    tableTrashed.draw();
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                    swal("Deleted!", "Data Berhasil Dihapus...!",
                                        "success");
                                },
                                error: function(data) {
                                    load_data();
                                    tableTrashed.draw();
                                    console.log('Error:', data);
                                    $("#canvasloading").hide();
                                    $("#loading").hide();
                                }
                            });

                        } else {
                            load_data();
                            tableTrashed.draw();
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Cancelled", "Hapus data dibatalkan...! :)", "error");
                        }
                    });
            });


            $('body').on('click', '.restoreProduk', function() {

                var id_produk = $(this).data("id_produk");
                swal({
                        title: "Kembalikan Produk ini?",
                        text: "Data akan dikembalikan ke tabel Produk!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Kembalikan!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $("#canvasloading").show();
                            $("#loading").show();
                            $.ajax({
                                type: "get",
                                url: "{{ url('/produk-restore') }}" + '/' + id_produk,
                                success: function(data) {
                                    load_data();
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
                            swal("Cancelled", "Produk gagal Dikembalikan...! :)", "error");
                        }
                    });
            });



            $('body').on('click', '.copyProduk', function() {

                var id_produk = $(this).data("id_produk");
                swal({
                        title: "Yakin Duplicate Produk ini?",
                        text: "Produk yang sama akan ditambahkan!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya, Copy Data!",
                        cancelButtonText: "Batal!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: "get",
                                url: "{{ url('/copy-produk') }}" + '/' + id_produk,
                                success: function(data) {
                                    load_data();
                                    swal("Berhasil!", "Produk telah di Duplicate...!",
                                        "success");
                                },
                                error: function(data) {
                                    console.log('Error:', data);
                                    swal("Pesan", data.message, "error");
                                }
                            });

                        } else {
                            swal("Cancelled", "Duplicate data dibatalkan...! :)", "error");
                        }
                    });
            });




            // datatable
            var tableTrashed = $('#datatableTrash').DataTable({
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
                    url: "{{ url('/produkTrashTable') }}",
                    type: "POST",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk'
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
                        data: 'merek',
                        name: 'merek'
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
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'jenis_jual',
                        name: 'jenis_jual'
                    },
                    {
                        data: 'qr',
                        name: 'qr'
                    },

                ]
            });




            $('body').on('click', '.deleteGaleri', function() {

                var id_gambar = $(this).data("id_gambar");
                var id_produk = $(this).data("id_produk");
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
                                url: "{{ url('/hapus-galeri') }}" + '/' + id_gambar,
                                success: function(data) {
                                    $('.list-gambar-produk').load('/get-gambar-produk' +
                                        '/' +
                                        id_produk);
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


            $('body').on('click', '.gambarProduk', function() {
                id = $(this).data('id_produk');
                $("#gambarForm").trigger('reset');
                $("#dataId").val(id);
                $('.list-gambar-produk').load('/get-gambar-produk' + '/' + id);
                $('#gambarModal').modal('show');
            });

            $('#saveGambar').click(function(e) {
                e.preventDefault();
                var form = $('#gambarForm')[0];
                var formData = new FormData(form);
                $("#canvasloading").show();
                $("#loading").show();
                $.ajax({
                    data: formData,
                    url: "{{ url('/simpan-gambar') }}",
                    type: "POST",
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.success == true) {

                            $('#gambarForm').trigger("reset");
                            $('#gambarModal').modal('hide');
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", data.message, "success");
                        } else {
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
                                err += value + ', ';
                            });
                            $("#canvasloading").hide();
                            $("#loading").hide();
                            swal("Pesan", err, "error");
                        }
                    }
                });
            });


        });
    </script>
@endsection
