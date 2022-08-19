<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
 <!-- Primary Meta Tags -->
 <title>@if(isset($post->title)){{ $post->title }}@else {{  website()->nama_website }} - Blog @endif</title>
        <meta name="title" content="{{ $post->title }}">
        <meta name="description" content="{{ $post->meta_desc }}">
        <meta name="keywords" content="{{ $post->keywords }}">
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ URL::current() }}">
        <meta property="og:title" content="{{ $post->title }}">
        <meta property="og:description" content="{{ $post->meta_desc }}">
        <meta property="og:image" content="{{ asset('storage/'.$post->cover) }}">
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ URL::current() }}">
        <meta property="twitter:title" content="{{ $post->title }}">
        <meta property="twitter:description" content="{{ $post->meta_desc }}">
        <meta property="twitter:image" content="{{ asset('storage/'.$post->cover) }}">

 <!-- Favicons -->
 <link href="{{ asset('fe_assets/img/logo_fix.png') }}" rel="icon">
  <link href="{{ asset('fe_assets/img/logo_fix.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('fe_assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('fe_assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('fe_assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('fe_assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('fe_assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('fe_assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  
  <!-- Template Main CSS File -->
  <link href="{{ asset('fe_assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center">
                <img src="{{ asset('fe_assets/img/logo_fix.png') }}" alt="">
                <span>{{ str_replace(' ','', website()->nama_website) }}</span>
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                  <?php
                  if(!empty(dataMenu())){
                    foreach(dataMenu() as $menu){
                      ?>
                    <li><a class="nav-link scrollto active" href="{{ $menu->url }}">{{ $menu->title }}</a></li>
                    <?php
                    }
                  }
                  ?>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.html">Home</a></li>
          <li><a href="blog.html">Blog</a></li>
          <li>{{$post->title}}</li>
        </ol>
        <!-- <h2>Blog Single</h2> -->

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Blog Single Section ======= -->
    <section id="blog" class="blog">
      <div class="container" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-8 entries">

            <article class="entry entry-single">

              <div class="entry-img">
                <img src="assets/img/blog/blog-1.jpg" alt="" class="img-fluid">
              </div>

              <h2 class="entry-title">
                <a href="#">{{$post->title}}</a>
              </h2>

              <div class="entry-meta">
                <ul>
                  <li class="d-flex align-items-center"><i class="bi bi-person"></i> by {{ $post->user->name }}</li>
                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <time>{{ Carbon\Carbon::parse($post->created_at)->isoFormat('D MMMM Y'); }}</time></li>
                  <!-- <li class="d-flex align-items-center"><i class="bi bi-chat-dots"></i> <a href="blog-single.html">12 Comments</a></li> -->
                </ul>
              </div>

              <div class="entry-content">
              <img src="/postImage/{{$post->cover}}" alt="" class="img-fluid mb-4">
              {!! $post->desc !!}
              </div>

              <div class="entry-footer">
                <i class="bi bi-folder"></i>
                <a href="{{ route('category',$post->category->slug) }}" class="text-dark">{{ $post->category->name }}</a>

                <i class="bi bi-tags"></i>
                <ul class="tags">
                @foreach ($post->tags as $item)
                                <li><a href="{{ route('tag', $item->slug) }}" class="badge badge-secondary mr-1">{{ $item->name }}</a></li>
                                @endforeach
                </ul>
              </div>

            </article><!-- End blog entry -->

          
          </div><!-- End blog entries list -->

          <div class="col-lg-4">

            <div class="sidebar">

              <h3 class="sidebar-title">Search</h3>
              <div class="sidebar-item search-form">
                <form action="">
                  <input type="text">
                  <button type="submit"><i class="bi bi-search"></i></button>
                </form>
              </div><!-- End sidebar search formn-->

              <h3 class="sidebar-title">Categories</h3>
              <div class="sidebar-item categories">
                <ul>
                  @foreach($allCategory as $kategori)
                  <li><a href="/post-category/{{$kategori->slug}}">{{$kategori->name}} <span>({{$kategori->posts_count}})</span></a></li>
                  @endforeach
                </ul>
              </div><!-- End sidebar categories-->

              <h3 class="sidebar-title">Recent Posts</h3>
              <div class="sidebar-item recent-posts">
                @foreach($recent as $recent)
                <div class="post-item clearfix">
                  <img src="/postImage/{{$recent->cover}}" alt="">
                  <h4><a href="blog-single.html">{{$recent->title}}</a></h4>
                  <time><small class="text-muted ml-auto"><i class="bi bi-clock"></i> {{ Carbon\Carbon::parse($recent->created_at)->diffForHumans()}}</small></time>
                </div>
                @endforeach

               

              </div><!-- End sidebar recent posts-->

              <h3 class="sidebar-title">Tags</h3>
              <div class="sidebar-item tags">
                <ul>
                  @foreach($allTag as $tag)
                  <li><a href="/post-tag/{{$tag->slug}}">{{$tag->name}}</a></li>
                  @endforeach
                </ul>
              </div><!-- End sidebar tags-->

            </div><!-- End sidebar -->

          </div><!-- End blog sidebar -->

        </div>

      </div>
    </section><!-- End Blog Single Section -->

  </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">

        <!--   <div class="footer-newsletter">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12 text-center">
          <h4>Our Newsletter</h4>
          <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
        </div>
        <div class="col-lg-6">
          <form action="" method="post">
            <input type="email" name="email"><input type="submit" value="Subscribe">
          </form>
        </div>
      </div>
    </div>
  </div> -->

        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-5 col-md-12 footer-info">
                        <a href="index.html" class="logo d-flex align-items-center">
                            <img src="{{ asset('fe_assets/img/logo_fix.png') }}" alt="">
                            <span>{{  website()->nama_website }}</span>
                        </a>
                        <p>Jika Anda berencana untuk membuat merchandise promosi dengan kualitas terbaik, segera hubungi
                            kami dan kami akan memberikan informasi dan solusi terbaik bagi Anda. So let us help you
                            today!</p>
                        <div class="social-links mt-3">
                            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6 footer-links">
            <!-- <h4> Link utama</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
            </ul> -->
          </div>
<!-- 
          <div class="col-lg-2 col-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bi bi-chevron-right"></i> <a href="#"> Souvenir Kit</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Diskon Produk</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Pengiriman Cepat</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Respon Cepat</a></li>
              <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul>
          </div> -->
                    <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
                        <h4 style="padding: 0;">Hubungi Kami</h4>
                        <p>Jl. M. Yamin, Aur Kuning, Kec. Aur Birugo Tigo Baleh, Kota Bukittinggi, Sumatera Barat 26181<br>
                            
                            <strong>Phone / WA: <a href="https://api.whatsapp.com/send?phone=6282283803383"> </a></strong>
                            0822-8380-3383 / 0812-6602-225
                            <br>
                            <strong>Email:</strong> sumajayaberkah@gmail.com<br>
                        </p>

                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>{{  website()->nama_website }} - 2022</span></strong>. All Rights Reserved
            </div>
            <div class="credits">

                Designed by <a href="#">Tim SJB</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('fe_assets/vendor/purecounter/purecounter.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('fe_assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('fe_assets/js/main.js') }}"></script>

</body>

</html>