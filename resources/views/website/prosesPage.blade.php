@extends("index")

@section("content")

@if(isset($result))
<section style="margin-top: 100px;">
    <header class="section-header" style="padding-bottom: 10px;">
        <h2>Transaksi Kamu Sedang Dalam Proses</h2>
        {{-- <p>Cek Produk Terbaru Kami</p> --}}
    </header>
    <p class="text-center">Kamu dapat melakukan pengecekan pesananmu menggunakan Kode Transaksi Dibawah ini:</p>
    <h3 class='text-center'>{{$result}}</h3>

</section>
@else
<section style="margin-top: 100px;">
    <header class="section-header">
        <h2>Transaksi Kamu Tidak Dapat Diproses</h2>
        {{-- <p>Cek Produk Terbaru Kami</p> --}}
    </header>
    <p class="text-center">{{$pesan}}</p>

</section>
@endif

@endsection