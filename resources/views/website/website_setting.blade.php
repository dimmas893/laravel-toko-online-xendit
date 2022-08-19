@extends("layouts.app")
@section('content')
    <script src="//cdn.ckeditor.com/4.17.2/basic/ckeditor.js"></script>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">

                <h4 class="page-title">Pengaturan Aplikasi</h4>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs nav-bordered mb-3">
        <li class="nav-item">
            <a href="#website" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                <span class="d-none d-md-block">Website</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#transaksi" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                <span class="d-none d-md-block">Transaksi</span>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="website">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session()->has('status'))
                                <div class="alert alert-{{ session('status') }}" role="alert">
                                    <strong>{{ strtoupper(session('status')) }} - </strong> {{ session('message') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{-- <h4 class="header-title with-border">My Profil</h4> --}}
                            <form method="POST" action="/update-pengaturan-website" class="form-horizontal"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    <input type="hidden" required name="id" id="id" value="{{ $website->id }}">

                                    <div class="mb-2">
                                        <label for="nama" class="control-label">Nama Website</label>
                                        <input type="text" class="form-control" id="nama" name="nama"
                                            value="{{ $website->nama_website }}" required="">
                                    </div>

                                    <div class="mb-2">
                                        <label for="tagline" class="control-label">Tagline</label>

                                        <input type="text" class="form-control" id="tagline" name="tagline"
                                            value="{{ $website->tagline }}">

                                    </div>

                                    <div class="mb-2">
                                        <label for="contact" class="control-label">Telpon / HP</label>

                                        <input type="text" class="form-control" id="contact" name="contact" required
                                            value="{{ $website->contact }}">

                                    </div>

                                    <div class="mb-2">
                                        <label class="">Provinsi
                                            Tujuan</label>
                                        <select class="custom-select form-control provinsi-tujuan" required
                                            name="province_destination" id="provinsi">
                                            <option value="">Pilih Provinsi
                                            </option>
                                            @if (isset($provinces))
                                                @foreach ($provinces as $prov)
                                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="">Kota
                                            / Kabupaten Tujuan</label>
                                        <select class="custom-select form-control kota-tujuan" required
                                            name="city_destination" id="city">
                                            <option value="">Pilih Kota /
                                                Kabupaten</option>
                                            @if (isset($city))
                                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="">Kecamatan
                                            Tujuan</label>
                                        <select class="custom-select form-control kecamatan-tujuan" required
                                            name="district_destination" id="district">
                                            <option value="">Pilih Kecamatan
                                            </option>
                                            @if (isset($district))
                                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="mb-2">
                                        <label class="">Kode
                                            Pos</label>
                                        <input class="form-control" type="text" value="{{ $website->kode_pos }}"
                                            name="kode_pos" placeholder="Enter your zip code" id="kode_pos" />
                                    </div>

                                    <div class="mb-2">
                                        <label for="alamat" class="control-label">Alamat </label>
                                        <textarea name="alamat" id="alamat" class="form-control"></textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label for="file" class="control-label">Foto Profil Website </label>
                                        <input type="file" id="file" name="file" class="form-control">
                                        <div class="mb-2">
                                            <img id="preview-image-before-upload" alt="Preview Image"
                                                style="max-height: 100px; max-width:350px">
                                        </div>
                                    </div>

                                    <div class="mb-2 produk-group">
                                        <label for="deskripsi" class="control-label">Deskripsi</label>

                                        <textarea name="deskripsi" id="deskripsi" maxlength="300" class="form-control"></textarea>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="transaksi">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- <h4 class="header-title with-border">My Profil</h4> --}}
                            <form method="POST" action="/update-pengaturan-transaksi" class="form-horizontal"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">

                                    <input type="hidden" required name="id" id="id" value="{{ $website->id }}">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3>PPN & PPH</h3>
                                            <label for="ppn">PPn %</label>
                                            <input type="number" max="100" min="0" name="ppn" id="ppn"
                                                value="{{ $website->trx_ppn }}" class="form-control">
                                            <label for="pph">PPh %</label>
                                            <input type="number" max="100" min="0" name="pph" id="pph"
                                                value="{{ $website->trx_pph }}" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <h3>MarkUp Harga</h3>
                                            <label for="markup"> Transaksi Offline %</label>
                                            <input type="number" max="100" min="0" name="markup" id="markup"
                                                value="{{ $website->trx_markup }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h3>Verifikasi Atasan</h3>
                                            <label for="verifikasi">Apakah transaksi offline perlu verifikasi
                                                atasan?</label>

                                                <div class="mt-2">
                                                    <div class="form-check">
                                                        <input type="radio" id="verifikasi1" name="verifikasi" value="1" class="form-check-input">
                                                        <label class="form-check-label" for="verifikasi1">Ya</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="radio" id="verifikasi2" name="verifikasi" value="0" class="form-check-input">
                                                        <label class="form-check-label" for="verifikasi2">Tidak</label>
                                                    </div>
                                                </div> 
                                           
                                        </div>
                                        <div class="col-md-6">
                                            <h3>Durasi Invoice Transaksi (Detik)</h3>
                                            <label for="duration_online">Transaksi Online</label>
                                            <input type="number"  min="0" name="duration_online" id="duration_online"
                                                value="{{ $website->trx_duration_online }}" class="form-control">
                                                <label for="duration_offline">Transaksi Offline</label>
                                                <input type="number"  min="0" name="duration_offline" id="duration_offline"
                                                    value="{{ $website->trx_duration_offline }}" class="form-control">
                                           
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="saveBtn">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(e) {

            CKEDITOR.replace('deskripsi');
            CKEDITOR.replace('alamat');

            $('#preview-image-before-upload').attr('src', '/websiteIcon/{{ $website->icon }}');
            $('#file').change(function() {

                let reader = new FileReader();

                reader.onload = (e) => {

                    $('#preview-image-before-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);


            });


            CKEDITOR.instances['deskripsi'].setData('{!! $website->description !!}');
            CKEDITOR.instances['alamat'].setData('{!! $website->address !!}');


            //active select2
            $(".provinsi-asal , .kota-asal, .provinsi-tujuan, .kota-tujuan, .kecamatan-tujuan").select2();

            // $("#loading").show();
            // $.ajax({
            //     type: 'get',
            //     url: '{{ url('ongkir/get-all-provinsi') }}',
            //     success: function(data) {
            //         $("select[name=province_destination]").html(data);

            //         $("#loading").hide();
            //     }
            // })

            $("#provinsi").select2("trigger", "select", {
                data: {
                    id: "{{ $website->provinsi }}"
                }
            });

            $("#city").select2("trigger", "select", {
                data: {
                    id: "{{ $website->kota }}"
                }
            });

            $("#district").select2("trigger", "select", {
                data: {
                    id: "{{ $website->kecamatan }}"
                }
            });
@if($website->trx_verifikasi)
            var $radios = $('input:radio[name=verifikasi]');
        if($radios.is(':checked') === false) {
            $radios.filter('[value={{$website->trx_verifikasi}}]').prop('checked', true);
        }

        @endif

            

            //ajax select kota tujuan
            $('select[name="province_destination"]').on('change', function() {
            
                let provindeId = $(this).val();
                if (provindeId) {

                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kota') }}' + '/' + provindeId,
                        success: function(data) {
                            $("select[name=city_destination]").html(data);

                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="city_destination"]').append(
                        '<option value="">-- pilih kota tujuan --</option>');
                }
            });

            //ajax select kecamatan tujuan
            $('select[name="city_destination"]').on('change', function() {
                let cityId = $(this).val();
                if (cityId) {

                    $("#loading").show();
                    $.ajax({
                        type: 'get',
                        url: '{{ url('ongkir/get-all-kecamatan') }}' + '/' + cityId,
                        success: function(data) {
                            $("select[name=district_destination]").html(data);

                            $("#loading").hide();
                        }
                    })
                } else {
                    $('select[name="district_destination"]').append(
                        '<option value="">-- pilih Kecamatan Tujuan --</option>');
                }
            });
        });
    </script>
@endsection
