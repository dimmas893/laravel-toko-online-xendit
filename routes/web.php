<?php

use Illuminate\Support\Facades\Route;
use App\Models\Kategori;
use App\Http\Controllers\PushNotificationController;
use App\Models\Portofolio;
use App\Models\Galeri;
use App\Models\Produk;
use App\Models\Post;
use App\Models\Website;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOngkirController;
use App\Http\Controllers\XenditController;
use App\Http\Controllers\NilaiController;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Support\Facades\Crypt;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // $query=Portofolio::orderBy('created_at','desc');
    // $kategori=$query->groupBy('kategori_id')->get();
    // $portofolio=$query->get();
    $portofolio = Portofolio::orderBy('created_at', 'desc')->get();
    $kategori = Kategori::get();
    $homeSlider = Galeri::where('jenis', 'homeSlider')->get();
    $produkBaru = Produk::where('jenis_jual', '1')->orderBy('created_at', 'desc')->limit(8)->get();
    $posts = Post::latest()->limit(6)->get();
    return view('website.data-index', compact('kategori', 'portofolio', 'homeSlider', 'produkBaru', 'posts'));
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::resource('tags', App\Http\Controllers\TagController::class);

    // Manage Posts
    Route::get('posts/trash', [App\Http\Controllers\PostController::class, 'trash'])->name('posts.trash');
    Route::post('posts/trash/{id}/restore', [App\Http\Controllers\PostController::class, 'restore'])->name('posts.restore');
    Route::delete('posts/{id}/delete-permanent', [App\Http\Controllers\PostController::class, 'deletePermanent'])->name('posts.deletePermanent');
    Route::resource('posts', App\Http\Controllers\PostController::class);
    Route::get('category/{category:slug}', [App\Http\Controllers\FrontController::class, 'category'])->name('category');
    Route::get('tag/{tag:slug}', [App\Http\Controllers\FrontController::class, 'tag'])->name('tag');
});

Route::group(['middleware' => ['auth', 'ceklevel:ADMIN,CEO,STAFF']], function () {
    Route::get('/galeri', [App\Http\Controllers\GaleriController::class, 'index']);
    Route::post('/upload-gambar', [App\Http\Controllers\GaleriController::class, 'store']);
    Route::post('/aksi-galeri', [App\Http\Controllers\GaleriController::class, 'aksiGaleri']);
    Route::delete('/hapus-galeri/{id}', [App\Http\Controllers\GaleriController::class, 'destroy']);

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);
    //Produk
    Route::get('/produk', [App\Http\Controllers\ProdukController::class, 'index']);
    Route::post('/produkTable', [App\Http\Controllers\ProdukController::class, 'index']);
    Route::post('/produkTrashTable', [App\Http\Controllers\ProdukController::class, 'trashTable']);
    Route::post('/produk/simpan', [App\Http\Controllers\ProdukController::class, 'store']);
    Route::get('/produk/{id}/edit', [App\Http\Controllers\ProdukController::class, 'edit']);
    Route::get('/produk-trash/{id}', [App\Http\Controllers\ProdukController::class, 'trash']);
    Route::get('/produk-delete/{id}', [App\Http\Controllers\ProdukController::class, 'delete']);
    Route::get('/produk-restore/{id}', [App\Http\Controllers\ProdukController::class, 'restore']);
    Route::post('/produk/update-stock', [App\Http\Controllers\ProdukController::class, 'updateStock']);
    Route::get('/copy-produk/{id}', [App\Http\Controllers\ProdukController::class, 'copyProduk']);
    Route::post('/simpan-gambar', [App\Http\Controllers\ProdukController::class, 'simpanGambar']);
    Route::get('/get-gambar-produk/{id}', [App\Http\Controllers\ProdukController::class, 'getGambarProduk']);
    //Kategori
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index']);
    Route::post('/kategoriTable', [App\Http\Controllers\KategoriController::class, 'index']);
    Route::post('/kategori/simpan', [App\Http\Controllers\KategoriController::class, 'store']);
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\KategoriController::class, 'edit']);
    Route::post('/kategoriTableTrash', [App\Http\Controllers\KategoriController::class, 'trashTable']);
    Route::get('/kategori-trash/{id}', [App\Http\Controllers\KategoriController::class, 'trash']);
    Route::get('/kategori-delete/{id}', [App\Http\Controllers\KategoriController::class, 'delete']);
    Route::get('/kategori-restore/{id}', [App\Http\Controllers\KategoriController::class, 'restore']);
    Route::get('/kategori/bulk-delete', [App\Http\Controllers\KategoriController::class, 'bulkDelete']);

    //transaksi
    Route::get('/transaksi', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::post('/transaksiTable', [App\Http\Controllers\TransaksiController::class, 'index']);
    Route::post('/transaksi/simpan', [App\Http\Controllers\TransaksiController::class, 'store']);
    Route::get('/transaksi/{id}/edit', [App\Http\Controllers\TransaksiController::class, 'edit']);
    Route::delete('/transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'destroy']);
    Route::post('/transaksi/simpan-pengiriman', [App\Http\Controllers\TransaksiController::class, 'savePengiriman']);
    Route::get('/transaksi/create-invoice/{id}', [App\Http\Controllers\TransaksiController::class, 'createInvoiceOffline']);


    Route::get('/cari/{produk_name}', [App\Http\Controllers\TransaksiController::class, 'getProduk']);
    Route::get('/addcart/{id}', [App\Http\Controllers\TransaksiController::class, 'addToCart']);
    Route::post('/cartremove/{id}', [App\Http\Controllers\TransaksiController::class, 'removeCart']);
    Route::get('/getcarttable', [App\Http\Controllers\TransaksiController::class, 'getCartTable']);
    Route::post('/cartupdate', [App\Http\Controllers\TransaksiController::class, 'updateCart']);
    Route::post('/cart-update-harga-jual', [App\Http\Controllers\TransaksiController::class, 'updateCartHargaJual']);
    Route::post('/cart-update-biaya', [App\Http\Controllers\TransaksiController::class, 'updateCartBiaya']);
    Route::get('/dengan-ppn', [App\Http\Controllers\TransaksiController::class, 'enablePpn']);
    Route::get('/hapus-ppn', [App\Http\Controllers\TransaksiController::class, 'removePpn']);
    Route::get('/dengan-pph', [App\Http\Controllers\TransaksiController::class, 'enablePph']);
    Route::get('/hapus-pph', [App\Http\Controllers\TransaksiController::class, 'removePph']);
    Route::post('/verifikasi-transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'verifikasiTrans']);

    Route::get('/list-pelanggan', [App\Http\Controllers\TransaksiController::class, 'listPelanggan']);
    Route::get('/cari-pelanggan/{name}', [App\Http\Controllers\TransaksiController::class, 'cariPelanggan']);
    //Aset
    Route::get('/aset', [App\Http\Controllers\AsetController::class, 'index']);
    Route::post('/asetTable', [App\Http\Controllers\AsetController::class, 'index']);
    Route::post('/aset/simpan', [App\Http\Controllers\AsetController::class, 'store']);
    Route::get('/aset/{id}/edit', [App\Http\Controllers\AsetController::class, 'edit']);
    Route::delete('/aset/{id}', [App\Http\Controllers\AsetController::class, 'destroy']);

    //Stock
    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index']);
    Route::post('/stockTable', [App\Http\Controllers\StockController::class, 'index']);
    Route::post('/stock/simpan', [App\Http\Controllers\StockController::class, 'store']);
    // Route::get('/stock/{id}/edit',[App\Http\Controllers\StockController::class,'edit']);
    // Route::delete('/stock/{id}',[App\Http\Controllers\StockController::class,'destroy']);
    Route::get('/cari-produk-stock/{produk_name}', [App\Http\Controllers\StockController::class, 'cariProduk']);
    Route::get('/get-produk-stock/{id}', [App\Http\Controllers\StockController::class, 'getProduk']);

    //User
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/userTable', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/user/simpan', [App\Http\Controllers\UserController::class, 'store']);
    Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit']);
    Route::delete('/user/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('/profil', [App\Http\Controllers\UserController::class, 'profil']);
    Route::post('/update-profil', [App\Http\Controllers\UserController::class, 'updateProfil']);

    //Produk
    Route::get('/pengeluaran', [App\Http\Controllers\PengeluaranController::class, 'index']);
    Route::post('/pengeluaranTable', [App\Http\Controllers\PengeluaranController::class, 'index']);
    Route::post('/pengeluaran/simpan', [App\Http\Controllers\PengeluaranController::class, 'store']);
    Route::get('/pengeluaran/{id}/edit', [App\Http\Controllers\PengeluaranController::class, 'edit']);
    Route::delete('/pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'destroy']);

    Route::get('/portofolio', [App\Http\Controllers\PortofolioController::class, 'index']);
    Route::post('/portofolioTable', [App\Http\Controllers\PortofolioController::class, 'index']);
    Route::post('/portofolio/simpan', [App\Http\Controllers\PortofolioController::class, 'store']);
    Route::get('/portofolio/{id}/edit', [App\Http\Controllers\PortofolioController::class, 'edit']);
    Route::delete('/portofolio/{id}', [App\Http\Controllers\PortofolioController::class, 'destroy']);

    Route::get('/testimoni', [App\Http\Controllers\TestimoniController::class, 'index']);
    Route::post('/testimoniTable', [App\Http\Controllers\TestimoniController::class, 'index']);
    Route::post('/testimoni/simpan', [App\Http\Controllers\TestimoniController::class, 'store']);
    Route::get('/testimoni/{id}/edit', [App\Http\Controllers\TestimoniController::class, 'edit']);
    Route::delete('/testimoni/{id}', [App\Http\Controllers\TestimoniController::class, 'destroy']);

    //laporan
    Route::get('/laporan-transaksi', [App\Http\Controllers\LaporanController::class, 'view_laporan_transaksi']);
    Route::post('/print-laporan-transaksi', [App\Http\Controllers\LaporanController::class, 'print_laporan_transaksi']);
    Route::get('/laporan-pengeluaran', [App\Http\Controllers\LaporanController::class, 'view_laporan_pengeluaran']);
    Route::post('/print-laporan-pengeluaran', [App\Http\Controllers\LaporanController::class, 'print_laporan_pengeluaran']);
    Route::get('/laporan-aset', [App\Http\Controllers\LaporanController::class, 'view_laporan_aset']);
    Route::post('/print-laporan-aset', [App\Http\Controllers\LaporanController::class, 'print_laporan_aset']);

    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index']);
    Route::get('/notif-read/{id}', [App\Http\Controllers\NotifikasiController::class, 'update']);
});

Route::group(['middleware' => ['auth', 'ceklevel:ADMIN,CEO']], function () {
    Route::post('/verifikasi-pengeluaran/{id}', [App\Http\Controllers\PengeluaranController::class, 'verifikasiPengeluaran']);
    //Kas
    Route::get('/kas', [App\Http\Controllers\KasController::class, 'index']);
    Route::post('/kasTable', [App\Http\Controllers\KasController::class, 'index']);
    Route::post('/kas/simpan', [App\Http\Controllers\KasController::class, 'store']);
    Route::get('/laporan-kas', [App\Http\Controllers\LaporanController::class, 'view_laporan_kas']);
    Route::post('/print-laporan-kas', [App\Http\Controllers\LaporanController::class, 'print_laporan_kas']);
});

Route::group(['middleware' => ['auth', 'ceklevel:ADMIN']], function () {
    Route::get('/setting', [App\Http\Controllers\WebsiteController::class, 'index']);
    Route::post('/update-pengaturan-website', [App\Http\Controllers\WebsiteController::class, 'updateWebsite']);
    Route::post('/update-pengaturan-transaksi', [App\Http\Controllers\WebsiteController::class, 'updateTransaksi']);
    Route::get('down', function () {
        \Artisan::call('down', array('--secret' => '131'));
    });

    Route::get('up', function () {
        \Artisan::call('up');
    });
    Route::get('/menu', [App\Http\Controllers\MenuController::class, 'index']);
    Route::post('/menuTable', [App\Http\Controllers\MenuController::class, 'index']);
    Route::post('/menuTableTrash', [App\Http\Controllers\MenuController::class, 'trashTable']);
    Route::post('/menu/simpan', [App\Http\Controllers\MenuController::class, 'store']);
    Route::get('/menu/{id}/edit', [App\Http\Controllers\MenuController::class, 'edit']);
    Route::get('/menu/trash/{id}', [App\Http\Controllers\MenuController::class, 'trash']);
    Route::get('/menu/delete/{id}', [App\Http\Controllers\MenuController::class, 'delete']);
    Route::get('/menu/restore/{id}', [App\Http\Controllers\MenuController::class, 'restore']);
    Route::get('/menu/bulk-delete', [App\Http\Controllers\MenuController::class, 'bulkDelete']);
});

Route::get('/invoice/{id}', [App\Http\Controllers\TransaksiController::class, 'invoice']);
Route::get('/invoicePrint/{id}', [App\Http\Controllers\TransaksiController::class, 'invoicePrint']);
Route::get('/tanda-terima/{id}', [App\Http\Controllers\TransaksiController::class, 'tandaTerima']);
Route::get('/tanda-terima-print/{id}', [App\Http\Controllers\TransaksiController::class, 'tandaTerimaPrint']);

Route::get('/produk-detail/{id}', [App\Http\Controllers\ProdukController::class, 'show']);
Route::get('/produk-page', [App\Http\Controllers\ProdukController::class, 'produkPage']);
Route::get('/produk-kategori/{id}', [App\Http\Controllers\ProdukController::class, 'produkKategori']);
Route::get('/produk-cari/{id}', [App\Http\Controllers\ProdukController::class, 'produkCari']);

Route::get('/how-to-order', function () {
    return view('website.howtoorder');
});
Route::get('/blog', [App\Http\Controllers\FrontController::class, 'index'])->name('homepage');
Route::get('post/{slug}', [App\Http\Controllers\FrontController::class, 'show'])->name('show');

Route::get('/testimoni-page', [App\Http\Controllers\TestimoniController::class, 'show']);
//Notification Controllers
Route::post('send', [PushNotificationController::class, 'bulksend'])->name('bulksend');
Route::get('all-notifications', [PushNotificationController::class, 'index']);
Route::get('get-notification-form', [PushNotificationController::class, 'create']);


Route::get('/cart', [CartController::class, 'cartList'])->name('cart.list');
Route::get('/cart-add/{id}', [CartController::class, 'addToCart']);
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/remove', [CartController::class, 'removeCart'])->name('cart.remove');
Route::post('/clear', [CartController::class, 'clearAllCart'])->name('cart.clear');
Route::get('/tax', [CartController::class, 'tax']);


Route::get('/ongkir', [CheckOngkirController::class, 'index']);
Route::post('/ongkir', [CheckOngkirController::class, 'check_ongkir']);
Route::get('/cities/{province_id}', [CheckOngkirController::class, 'getCities']);
Route::get('/ongkir/get-all-provinsi', [CheckOngkirController::class, 'getAllProvinsi']);
Route::get('/ongkir/get-all-kota/{id}', [CheckOngkirController::class, 'getAllKota']);
Route::get('/ongkir/get-all-kecamatan/{id}', [CheckOngkirController::class, 'getAllKecamatan']);
Route::get('/ongkir/get-expedisi', [CheckOngkirController::class, 'getExpedisi']);
Route::post('/ongkir/costs', [CheckOngkirController::class, 'costs']);
Route::post('/cart/checkout', [CartController::class, 'checkout']);
Route::post('/cart/add-ongkir', [CartController::class, 'addOngkir']);

Route::get('/kabupaten/{id}', [App\Http\Controllers\TransaksiController::class, 'getKabupaten']);
Route::get('/kecamatan/{id}', [App\Http\Controllers\TransaksiController::class, 'getKecamatan']);
Route::get('/desa/{id}', [App\Http\Controllers\TransaksiController::class, 'getDesa']);


Route::get('/xendit', [XenditController::class, 'index']);
Route::get('/xendit/create-invoice', [XenditController::class, 'createInvoice']);
Route::get('/xendit/report', [XenditController::class, 'getReport']);


Route::get('/transaksi/confirs/{id}', [TransaksiController::class, 'confirs']);
Route::get('/transaksi/failed/{id}', [TransaksiController::class, 'failed']);
Route::get('/transaksi/proses-page', [TransaksiController::class, 'prosesPage']);
Route::get('/transaksi/cek/{id}', [TransaksiController::class, 'getInvoice']);
Route::get('/transaksi/cek-transaksi', [TransaksiController::class, 'vCekTransaksi']);



// Route::get('/team', function () {
// 	return view('sailor.team');
// });
// Route::get('/testimonials', function () {
// 	return view('sailor.testimonials');
// });
// Route::get('/services', function () {
// 	return view('sailor.services');
// });
// Route::get('/pricing', function () {
// 	return view('sailor.pricing');
// });
// Route::get('/portfolio', function () {
// 	return view('sailor.portfolio');
// });
// Route::get('/portfolio-details', function () {
// 	return view('sailor.portfolio-details');
// });
// Route::get('/contact', function () {
// 	return view('sailor.contact');
// });
// Route::get('/blog', function () {
// 	return view('blog');
// });
// Route::get('/blog-single', function () {
// 	return view('sailor.blog-single');
// });
// Route::get('/index', function () {
// 	return view('sailor.index');
// });
