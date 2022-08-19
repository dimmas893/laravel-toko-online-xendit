@extends('../index')

@section('content')
    
<div class="container">
<!-- start page title -->
<div class="row" style="margin-top: 100px;">
    <div class="col-12">
        <div class="page-title-box">
            <h5 class="page-title">Detail Produk</h5>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row mt-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <!-- Product image -->
                        <a href="{{ asset('/gambar/'.$produk->gambar_utama) }}" class="text-center d-block mb-4">
                            <img src="{{ asset('/gambar/'.$produk->gambar_utama) }}" class="img-fluid" style="max-width: 280px;"
                                alt="{{$produk->nama_produk}}" />
                        </a>

                        <div class="d-lg-flex d-none justify-content-center">
                        @php $i=1; @endphp
                            @foreach($galeri as $galeri)
                            <a href="{{ url('/galeriImage/'.$galeri->gambar) }}" @if($i>1) class="ms-2" @endif>
                                <img src="/galeriImage/{{$galeri->gambar}}" class="img-fluid img-thumbnail p-2"
                                    style="max-width: 75px;" alt="{{$produk->nama_produk}}" />
                            </a>
                            @php $i++ @endphp
                            @endforeach
                        </div>
                    </div> <!-- end col -->
                    <div class="col-lg-7">
                        <form class="ps-lg-4">
                            <!-- Product title -->
                            <h1 style="font-size:22px" class="mt-0">{{$produk->nama_produk}}</h1>
                            <h3 style="font-size: 16px" class="d-flex"><img width="20px" class="me-1" src="{{ asset('icon/'.$produk->kategori->icon)}}"
                                    alt="{{$produk->kategori->nama_kategori}}"> {{$produk->kategori->nama_kategori}}
                            </h3>
                            <!-- <h6>Merek : {{$produk->merek}}</h6> -->
                            <p style="font-size: 12px;"  class="mb-1">Ditambahkan:
                                <time>{{ Carbon\Carbon::parse($produk->created_at)->isoFormat('D MMMM Y'); }}</time></p>
                            <!-- <p class="font-16">
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                                <span class="text-warning mdi mdi-star"></span>
                            </p> -->

                            <!-- Product stock -->
                            <div class="mt-2">
                                <h4><span class="text-success">Stock Tersedia</span></h4>
                            </div>

                            <!-- Product description -->
                            @if(isset(auth()->user()->level) || $produk->jenis_jual==1)
                                <div class="mt-4">
                                                                <h6 class="font-14">Harga:</h6>
                                                                <h3> Rp. {{number_format((float)$produk->harga_jual, 0, ',', '.')}}</h3>
                                                            </div>
                                @endif

                            <!-- Quantity -->
                            <!-- <div class="mt-4">
                                                        <h6 class="font-14">Quantity</h6>
                                                        <div class="d-flex">
                                                            <input type="number" min="1" value="1" class="form-control" placeholder="Qty" style="width: 90px;">
                                                            <button type="button" class="btn btn-danger ms-2"><i class="mdi mdi-cart me-1"></i> Add to cart</button>
                                                        </div>
                                                    </div> -->



                            <!-- Product information -->
                            <div class="mt-2">
                                <div class="row">
                                    <!-- <div class="col-md-4">
                                        <h6 class="font-14">Available Stock:</h6>
                                        <p class="text-sm lh-150">{{$produk->jumlah.' '.$produk->satuan}}</p>
                                    </div> -->
                                    <!-- <div class="col-md-4">
                                        <h6 class="font-14">Number of Orders:</h6>
                                        <p class="text-sm lh-150">5,458</p>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="font-14">Revenue:</h6>
                                        <p class="text-sm lh-150">$8,57,014</p>
                                    </div> -->
                                </div>
                            </div>
                            <?php
                            if($produk->jenis_jual==1){
                                echo '<a 
                                href="/cart-add/' . $produk->id . '"
                                class="btn btn-outline-dark m-2"><i class="fa fa-cart-plus text-danger"></i> Add To Cart</a>';
                            }elseif($produk->jenis_jual==2){
                                echo '<a 
                                href="https://wa.me/6282283803383?text=Saya%20ingin%20memesan%20produk%20kode%20' . $produk->kode_produk . '%20nama%20produk%20' . $produk->nama_produk . '"
                                class="btn btn-outline-success m-2"><i class="fa fa-whatsapp"></i> 
                                Pesan
                                Sekarang</a>';
                            }
                            ?>
                            <!-- Product description -->
                            <div class="mt-4">
                                <h6 class="font-14">Deskripsi Produk:</h6>
                                {!!$produk->deskripsi!!}
                            </div>

                        </form>
                    </div> <!-- end col -->
                </div> <!-- end row-->
                <!-- <div class="row">
                    <div class="col-sm-12"> -->
                <!-- Product description -->
                <!-- <div class="mt-4">
                            <h6 class="font-14">Deskripsi Produk:</h6>
                            {!!$produk->deskripsi!!}
                        </div>
                    </div>
                </div> -->
                <!-- <div class="table-responsive mt-4">
                                            <table class="table table-bordered table-centered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Outlets</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Revenue</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>ASOS Ridley Outlet - NYC</td>
                                                        <td>$139.58</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">478 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$1,89,547</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Marco Outlet - SRT</td>
                                                        <td>$149.99</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">73 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 16%;" aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$87,245</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Chairtest Outlet - HY</td>
                                                        <td>$135.87</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">781 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$5,87,478</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nworld Group - India</td>
                                                        <td>$159.89</td>
                                                        <td>
                                                            <div class="progress-w-percent mb-0">
                                                                <span class="progress-value">815 </span>
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>$55,781</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div> end table-responsive -->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->

</div>
@endsection