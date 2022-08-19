@extends('layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <!-- <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">CRM</li>
                            </ol>
                        </div> -->
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Saldo Xendit</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format(saldo(), 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span> -->
                                        {{-- <span class="text-nowrap">This Month</span> --}}
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                        <div class="col-lg-4">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Kas Besar</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format(kasBesar(), 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span> -->
                                        {{-- <span class="text-nowrap">This Month</span> --}}
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                        <div class="col-lg-4">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Kas Kecil</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format(kasKecil(), 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span> -->
                                        {{-- <span class="text-nowrap">This Month</span> --}}
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-account-multiple widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
                                    <h3 class="mt-3 mb-3">{{ $pelanggan }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 5.27%</span> -->
                                        <span class="text-nowrap">This Month</span>
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-cart-plus widget-icon bg-success-lighten text-success"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Transaksi Selesai</h5>
                                    <h3 class="mt-3 mb-3">{{ $transaksi }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 1.08%</span> -->
                                        <span class="text-nowrap">This Month</span>
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-currency-usd widget-icon bg-success-lighten text-success"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Pendapatan</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 7.00%</span> -->
                                        <span class="text-nowrap">This Month</span>
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-end">
                                        <i class="mdi mdi-pulse widget-icon"></i>
                                    </div>
                                    <h5 class="text-muted fw-normal mt-0" title="Growth">Pengeluaran</h5>
                                    <h3 class="mt-3 mb-3">Rp. {{ number_format($pengeluaran, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted">
                                        <!-- <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span> -->
                                        <span class="text-nowrap">This Month</span>
                                    </p>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row -->

                </div> <!-- end col -->



            </div>
        </div>
    @endsection
