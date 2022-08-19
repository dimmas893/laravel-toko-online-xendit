@extends('../index')

@section('content')
    <?php
    $biaya = 0;
    if (isset($shipping->biaya)) {
        $biaya = $shipping->biaya;
    }
    ?>
    <section class="container" style="margin-top: 50px; margin-bottom:50px;">
        <div class="container justify-content-center mt-50 mb-50">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-transparent header-elements-inline">
                            {{-- <h6 class="card-title">Faktur Penjualan</h6> --}}
                            <div class="header-elements">
                                <!-- <button type="button" class="btn btn-light btn-sm"><i class="fa fa-file mr-2"></i> Save</button> -->
                                <button type="button" id="btnPrint" class="btn btn-light btn-sm ml-3"><i
                                        class="fa fa-print mr-2"></i> Print</button>
                            </div>
                        </div>
                        <div class="print">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center mt-2"
                                    style="border-bottom: 4px solid rgba(0, 0, 0, .125);">
                                    <img class="d-flex mr-2 mt-2" src="/websiteIcon/{{website()->icon}}"
                                        alt="invoice {{ $transaksi->kode_transaksi }}" height="70">
                                    <div class="mb-4 mt-2 text-center">
                                        <h3 style='margin:0; padding:0; font-weight:800; letter-spacing:3px;'>
                                            CV. {{ strtoupper(website()->nama_website) }}</h3>
                                        <span style="letter-spacing: 2px;">Telepon : {{website()->contact}}, Email :
                                            {{strtolower(str_replace(' ','',website()->nama_website))}}@gmail.com<br>
                                            Website : www.{{strtolower(str_replace(' ','',website()->nama_website))}}.com, Instagram : {{'@'.strtolower(str_replace(' ','',website()->nama_website))}}<br>
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <b>INVOICE</b><br>
                                No: {{ $transaksi->kode_trans }}<br>
                                Tanggal: {{ tglIndo($transaksi->tgl_trans) }}
                                <br><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-4 pull-left text-left">
                                            <span class="text-muted">Dari :</span>
                                            <h5 class="my-2">CV. {{ strtoupper(website()->nama_website) }}</h5>
                                            <ul class="list list-unstyled mb-0 text-left">
                                                <li>{{strip_tags(website()->address)}}, {{website()->webkecamatan->name}}, {{website()->kode_pos}}</li>
                                                <li>{{website()->webkota->name}}</li>
                                                <li>{{website()->webprovinsi->name}}</li>
                                                <li>{{website()->contact}}</li>
                                            </ul>
                                        </div>


                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-4 mb-md-2 text-left"> <span class="text-muted">Tagihan
                                                Untuk:</span>
                                            <ul class="list list-unstyled mb-0">
                                                <li>
                                                    <h5 class="my-2">{{ $transaksi->pelanggan->nama_lengkap }}
                                                    </h5>
                                                </li>
                                                <li><span
                                                        class="font-weight-semibold">{{ $transaksi->pelanggan->alamat }}</span>
                                                </li>
                                                <li>{{ $kecamatan->name . ', ' . $kabupaten->name }}</li>
                                                <li>{{ $provinsi->name }}</li>
                                                <li>{{ $pelanggan->kode_pos }}</li>
                                                <li>{{ $transaksi->pelanggan->telpon }}</li>
                                                <li><a href="#" data-abc="true">{{ $transaksi->pelanggan->email }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <div class="table-responsive">
                                    <table class="table table-striped table-lg">
                                        <thead>
                                            <tr>
                                                <th>Nama Produk</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order as $order)
                                                <tr>

                                                    <td>
                                                        {{ $order->produk->nama_produk }}
                                                    </td>
                                                    <td>Rp. {{ number_format($order->harga_jual, 0, ',', '.') }}</td>
                                                    <td><span class="font-weight-semibold">{{ $order->jumlah }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-sm-8">
                                        <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                                            <!-- <h6 class="mb-3 text-left">Total</h6> -->
                                            <div class="table-responsive">
                                                <table id="info" class="table" width="100%">
                                                    <tbody>

                                                        <tr>
                                                            <th class="text-left">Subtotal:</th>
                                                            <td class="text-right">Rp.
                                                                {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">PPN: <span
                                                                    class="font-weight-normal">({{website()->trx_ppn}}%)</span></th>
                                                            <td class="text-right">Rp.
                                                                {{ number_format($transaksi->ppn, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">Biaya Kirim:</th>
                                                            <td class="text-right">Rp.
                                                                {{ number_format($biaya, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">
                                                               Total:
                                                            </th>
                                                            <td class="text-right text-primary">
                                                                <h6 class="font-weight-semibold">
                                                                    <?php
                                                                    
                                                                    $pph = $transaksi->pph;
                                                                    $total = $transaksi->subtotal;
                                                                    $gtotal = $total + $transaksi->ppn + $biaya;
                                                                    $diterima = $gtotal - $transaksi->pph;
                                                                    ?>
                                                                    Rp.
                                                                    {{ number_format($gtotal, 0, ',', '.') }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">PPH: <span
                                                                    class="font-weight-normal">({{website()->trx_pph}}%)</span></th>
                                                            <td class="text-right">Rp.
                                                                -{{ number_format($transaksi->pph, 0, ',', '.') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-left">Total Diterima:</th>
                                                            <td class="text-right text-primary">
                                                                <h5 class="font-weight-semibold">Rp.
                                                                    {{ number_format($diterima, 0, ',', '.') }}</h5>
                                                            </td>
                                                        </tr>
                                                        {{-- <tr>
                                                            <th class="text-left">Status Pembayaran:</th>
                                                            <td class="text-right text-primary">
                                                                <?php
                                                                // $status = $transaksi->status_trans;
                                                                // if ($status == 1) {
                                                                //     echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                // } elseif ($status == 2) {
                                                                //     echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                // } elseif ($status == 3) {
                                                                //     echo "<p class='text-success'>Telah Dibayar</p>";
                                                                // } elseif ($status == 4) {
                                                                //     echo "<p class='text-danger'>Belum Dibayar</p>";
                                                                // }
                                                                ?>
                                                            </td>
                                                        </tr> --}}
                                                        <tr>
                                                            <th class="text-left">Status Transaksi:</th>
                                                            <td class="text-right">
                                                                <?php
                                                                $status = $transaksi->status_trans;
                                                                if ($status == 1) {
                                                                    echo "<p class='text-danger'>Menunggu Pembayaran</p>";
                                                                } elseif ($status == 2) {
                                                                    echo "<p class='text-danger'>Proses</p>";
                                                                } elseif ($status == 3) {
                                                                    echo "<p class='text-success'>Selesai</p>";
                                                                } elseif ($status == 4) {
                                                                    echo "<p class='text-danger'>Dibatalkan</p>";
                                                                } elseif ($status == 5) {
                                                                    echo "<p class='text-info'>Dibayar</p>";
                                                                } elseif ($status == 6) {
                                                                    echo "<p class='text-primary'>Dikirim</p>";
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" style="text-align: center; margin-top:30px;">
                                        @if($xen)
                                        @if($xen['status']=='PENDING')
                                        <span class="text-left">Scan Tagihan</span><br><br>
                                        {!! QrCode::size(80)->generate($xen['invoice_url']) !!}
                                        @endif
                                        @else
                                        QR Code Tidak Tersedia
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <span>THANK YOU FOR YOUR ORDER</span><br>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="{{ asset('printThis/printThis.js') }}"></script>
        <script>
            $("#btnPrint").click(function() {
                url = "{{ url('/invoicePrint') }}" + "/{{ $transaksi->id }}"
                window.location.href = url
            })
        </script>
    </section>
@endsection
