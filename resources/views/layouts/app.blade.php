<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{  website()->nama_website }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('websiteIcon/'.website()->icon) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App css -->
    <!-- third party css -->
    <link href="{{ asset('hy_assets/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/buttons.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/vendor/select.bootstrap5.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <link href="{{ asset('hy_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('hy_assets/css/app-modern.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('hy_assets/css/app-modern-dark.min.css') }}" rel="stylesheet" type="text/css"
        id="dark-style" />

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>




    <style>
        table.dataTable td {
            padding: 10px;
        }

        #loading {
            border: 16px solid #f3f3f3;
            /* Light grey */
            border-top: 16px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        #preloading {
            position: fixed;
            left: 50%;
            top: 40%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
            text-align: center;
        }

        #canvasloading {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            height: 100%;
            z-index: 999999;
            position: absolute;
            display: none;
        }

        #txt {
            font-weight: 700;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        body {
            /* font-family: "Quicksand", "Open Sans"; */
            font-size: 14px;
        }

    </style>
</head>

<body class="loading" data-layout="detached"
    data-layout-config='{"leftSidebarCondensed":false,"darkMode":false, "showRightSidebarOnStart": false}'>
    <div id="canvasloading">

        <div id="preloading">
            <div id="loading"></div>
            <p id="txt">Mohon Tunggu Sebentar...</p>
        </div>
    </div>
    <!-- Topbar Start -->
    <div class="navbar-custom topnav-navbar topnav-navbar-dark">
        <div class="container-fluid">

            <!-- LOGO -->
            <a href="/" class="topnav-logo">
                <span class="topnav-logo-lg">
                    <img src="{{ asset('websiteIcon/'.website()->icon) }}" alt="{{  website()->nama_website }}" height="30">
                </span>
                <span class="topnav-logo-lg" style="vertical-align: middle; color: #ced4da; font-size:20px; margin-left: 10px;">{{  website()->nama_website }}</span>
                <span class="topnav-logo-sm">
                    <img src="{{ asset('websiteIcon/'.website()->icon) }}" alt="" height="16">
                </span>
            </a>
            <ul class="list-unstyled topbar-menu float-end mb-0">

                <li class="dropdown notification-list d-xl-none">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i class="dripicons-search noti-icon"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                        <form class="p-3">
                            <input type="text" class="form-control" placeholder="Search ..."
                                aria-label="Recipient's username">
                        </form>
                    </div>
                </li>



                <!--
                <li class="notification-list">
                    <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                        <i class="dripicons-gear noti-icon"></i>
                    </a>
                </li> -->

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                        id="topbar-notifydrop" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="dripicons-bell noti-icon"></i>
                        <span class="noti-icon-badge"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg"
                        aria-labelledby="topbar-notifydrop">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                    {{-- <a href="javascript: void(0);" class="text-dark">
                                        <small>Clear All</small>
                                    </a> --}}
                                </span>Notification
                            </h5>
                        </div>

                        <div id="body-notifikasi" style="max-height: 230px;" data-simplebar>
                            <div id="data-notifikasi"></div>
                            <div class="auto-load-notifikasi text-center">
                                <div class="spinner-border text-success" role="status"></div>
                            </div>

                        </div>

                        <!-- All-->
                        {{-- <a href="javascript:void(0);"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            View All
                        </a> --}}

                    </div>
                </li>

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown"
                        id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="{{ asset('userFoto/'.auth()->user()->foto) }}" alt="{{auth()->user()->name}}"
                                class="rounded-circle">
                        </span>
                        <span>
                            <span class="account-user-name">{{ auth()->user()->name }}</span>
                            <span class="account-position">{{ auth()->user()->level }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown"
                        aria-labelledby="topbar-userdrop">
                        <!-- item-->
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="/profil" class="dropdown-item notify-item">
                            <i class="mdi mdi-account-circle me-1"></i>
                            <span>Profil</span>
                        </a>


                        <!-- item-->
                        <a href="{{ route('logout') }}" class="dropdown-item notify-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout me-1"></i>
                            <span>Logout</span>
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>

            </ul>

            <a class="button-menu-mobile disable-btn">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>

        </div>
    </div>
    <!-- end Topbar -->

    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Begin page -->
        <div class="wrapper">

            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu leftside-menu-detached">

                <div class="leftbar-user">
                    <a href="javascript: void(0);">
                        <img src="{{ asset('userFoto/'.auth()->user()->foto) }}" alt="{{auth()->user()->name}}" height="42"
                            class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name">{{ auth()->user()->name }}</span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title side-nav-item">Navigation</li>
                    <!--
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <span class="badge bg-info rounded-pill float-end">4</span>
                                <span> Dashboards </span>
                            </a>
                            <div class="collapse" id="sidebarDashboards">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="dashboard-analytics.html">Analytics</a>
                                    </li>
                                    <li>
                                        <a href="dashboard-crm.html">CRM</a>
                                    </li>
                                    <li>
                                        <a href="index.html">Ecommerce</a>
                                    </li>
                                    <li>
                                        <a href="dashboard-projects.html">Projects</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="side-nav-title side-nav-item">Apps</li> -->
                    <li class="side-nav-item">
                        <a href="/dashboard" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>


                    <li class="side-nav-title side-nav-item">Produk</li>
                    <li class="side-nav-item">
                        <a href="/kategori" class="side-nav-link">
                            <i class="uil-tag-alt"></i>
                            <span> Kategori </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/produk" class="side-nav-link">
                            <i class="uil-archive"></i>
                            <span> Produk </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/stock" class="side-nav-link">
                            <i class="uil-archive"></i>
                            <span> Stock </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/transaksi" class="side-nav-link">
                            <i class="uil-invoice"></i>
                            <span> Transaksi </span>
                        </a>
                    </li>
                    <li class="side-nav-title side-nav-item">Kantor</li>
                    @if (auth()->user()->level == 'CEO' or auth()->user()->level == 'ADMIN')
                        <li class="side-nav-item">
                            <a href="/kas" class="side-nav-link">
                                <i class="uil-dollar-sign"></i>
                                <span> Kas </span>
                            </a>
                        </li>
                    @endif
                    <li class="side-nav-item">
                        <a href="/pengeluaran" class="side-nav-link">
                            <i class="uil-money-stack"></i>
                            <span> Pengeluaran </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/aset" class="side-nav-link">
                            <i class="uil-bag "></i>
                            <span> Aset </span>
                        </a>
                    </li>
                    <li class="side-nav-title side-nav-item">Website</li>

                    <li class="side-nav-item">
                        <a href="/portofolio" class="side-nav-link">
                            <i class="dripicons-checklist text-muted"></i>
                            <span> Portofolio </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/testimoni" class="side-nav-link">
                            <i class="uil uil-comment-check  text-muted"></i>
                            <span> Testimonial </span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="/galeri" class="side-nav-link">
                            <i class="uil uil-image text-muted"></i>
                            <span> Galeri </span>
                        </a>
                    </li>
                    <li class="side-nav-title side-nav-item">Blog</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarBlog" aria-expanded="false"
                            aria-controls="sidebarBlog" class="side-nav-link">
                            <i class="uil uil-comment"></i>
                            <span> Blog </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarBlog">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('tags.index') }}">Tags</a>
                                </li>
                                <li>
                                    <a href="{{ route('categories.index') }}">Categori</a>
                                </li>
                                <li>
                                    <a href="{{ route('posts.index') }}">Post</a>
                                </li>
                                <li>
                                    <a href="{{ route('posts.trash') }}">Post Trash</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-title side-nav-item">Laporan</li>
                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarLaporan" aria-expanded="false"
                            aria-controls="sidebarEmail" class="side-nav-link">
                            <i class="mdi mdi-file-document"></i>
                            <span> Laporan </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarLaporan">
                            <ul class="side-nav-second-level">
                                <li class="side-nav-item">
                                    <a href="/laporan-transaksi">
                                        {{-- <i class="mdi mdi-file-document"></i> --}}
                                        <span> Laporan Transaksi </span>
                                    </a>
                                </li>
                                <li class="side-nav-item">
                                    <a href="/laporan-pengeluaran">
                                        {{-- <i class="mdi mdi-file-document"></i> --}}
                                        <span> Laporan Pengeluaran </span>
                                    </a>
                                </li>
                                @if (auth()->user()->level == 'CEO' or auth()->user()->level == 'ADMIN')
                                <li class="side-nav-item">
                                    <a href="/laporan-kas">
                                        {{-- <i class="mdi mdi-file-document"></i> --}}
                                        <span> Laporan Kas </span>
                                    </a>
                                </li>
                                @endif
                                <li class="side-nav-item">
                                    <a href="/laporan-aset">
                                        {{-- <i class="mdi mdi-file-document"></i> --}}
                                        <span> Laporan Aset </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="side-nav-title side-nav-item">Pengaturan</li>
                    @if (auth()->user()->level == 'ADMIN')
                        <li class="side-nav-item">
                            <a href="/setting" class="side-nav-link">
                                <i class="uil uil-globe"></i>
                                <span> Aplikasi</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="/menu" class="side-nav-link">
                                <i class="uil-align-justify"></i>
                                <span> Menu</span>
                            </a>
                        </li>
                        <li class="side-nav-item">
                            <a href="/user" class="side-nav-link">
                                <i class="uil uil-users-alt"></i>
                                <span> Pengguna </span>
                            </a>
                        </li>
                    @endif


                    <li class="side-nav-item">
                        <a href="/profil" class="side-nav-link">
                            <i class="uil-user"></i>
                            <span> Akun Saya </span>
                        </a>
                    </li>


                    <div class="clearfix"></div>
                    <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <div class="content-page">
                <div class="content">

                    @yield('content')

                </div> <!-- End Content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                © {{  website()->nama_website }} - 2022 All Right Reserved.
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a href="javascript: void(0);">About</a>
                                    <a href="javascript: void(0);">Support</a>
                                    <a href="javascript: void(0);">Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->




    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->



    <script>
        $(document).ready(function() {
            var ENDPOINT = "{{ url('/') }}";
            // var page = 1;
            // infinteLoadMore(page);
            // $('#body-notifikasi').scroll(function() {
            // if ($('#body-notifikasi').scrollTop() + $('#body-notifikasi').height() >= $('#body-notifikasi').height()) {
            // page++;
            // infinteLoadMore(page);
            // }
            // });


            // function infinteLoadMore(page) {
            $.ajax({
                    url: ENDPOINT + "/notifikasi",
                    datatype: "html",
                    type: "get",
                    beforeSend: function() {
                        $('.auto-load-notifikasi').show();
                    }
                })
                .done(function(response) {
                    if (response.length == 0) {
                        $('.auto-load-notifikasi').html("We don't have more data to display :(");
                        return;
                    }
                    $('.auto-load-notifikasi').hide();
                    $("#data-notifikasi").append(response);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    console.log('Server error occured');
                });



        $(document).on("mouseover",".notify-item",function(){
            var id=$(this).data('id');
            myurl = "{{ url('/notif-read') }}" + '/' + id
            $.ajax({
                        type: "get",
                        url: myurl,
                        success: function(data) {
                           
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
        })

        });
    </script>

    <!-- bundle -->
    <script src="{{ asset('hy_assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/app.min.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('hy_assets/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/buttons.print.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('hy_assets/js/vendor/dataTables.select.min.js') }}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.datatable-init.js') }}"></script>
    <!-- end demo js-->

    <script src="{{ asset('hy_assets/js/vendor/apexcharts.min.js') }}"></script>

    <!-- Todo js -->
    <script src="{{ asset('hy_assets/js/ui/component.todo.js') }}"></script>

    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.dashboard-crm.js') }}"></script>
    <!-- end demo js-->

    <!-- demo -->
    <script src="{{ asset('hy_assets/js/pages/demo.materialdesignicons.js') }}"></script>
    <!-- end demo js-->

    <script src="{{ asset('js/sweetalert/sweetalert.min.js') }}"></script>
    <!-- <script src="https://cdn.rawgit.com/igorescobar/jQuery-Mask-Plugin/1ef022ab/dist/jquery.mask.min.js"></script> -->
    <script src="/js/jquery.mask.min.js"></script>
    <!-- demo app -->
    <script src="{{ asset('hy_assets/js/pages/demo.form-wizard.js') }}"></script>
    <!-- end demo js-->

</body>

</html>
