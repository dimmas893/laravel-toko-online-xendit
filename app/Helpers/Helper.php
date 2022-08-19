<?php

use App\Http\Controllers\XenditController;
use App\Models\Notifikasi;
use App\Models\Website;
use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\Kas;
use Illuminate\Support\Facades\Crypt;

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " Belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " Puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " Ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " Ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " Juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " Milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " Trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "Minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}



function tglIndo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}


function sendNotif($token_1, $comment)
{
    $SERVER_API_KEY = '';

    // $token_1 = 'frtiwtX4RRONp_JpU-6pXl:APA91bFjUChGY1J-TQ1I1xV4PLOlNvplv8-KpVluw5SXTvf7J53veDtG7HJAvcQ9Z0sO1Ss2iYhuOTQoFr3_3fq6L10cdomMsOHyqQJHK-GrElYgtywM5Kih0qOW9M93J0_dDyifSrlL';

    $data = [

        "registration_ids" => [
            $token_1
        ],

        "notification" => $comment,

    ];

    $dataString = json_encode($data);

    $headers = [

        'Authorization: key=' . $SERVER_API_KEY,

        'Content-Type: application/json',

    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
}

function createNotifikasi($data)
{
    $query = Notifikasi::Create($data);
    return $query;
}

function website()
{
    $setting = Website::with('webprovinsi')->with('webkota')->with('webkecamatan')->first();
    return $setting;
}

function gantiformat($nomorhp)
{
    //Terlebih dahulu kita trim dl
    $nomorhp = trim($nomorhp);
    //bersihkan dari karakter yang tidak perlu
    $nomorhp = strip_tags($nomorhp);
    // Berishkan dari spasi
    $nomorhp = str_replace(" ", "", $nomorhp);
    // bersihkan dari bentuk seperti  (022) 66677788
    $nomorhp = str_replace("(", "", $nomorhp);
    // bersihkan dari format yang ada titik seperti 0811.222.333.4
    $nomorhp = str_replace(".", "", $nomorhp);

    //cek apakah mengandung karakter + dan 0-9
    if (!preg_match('/[^+0-9]/', trim($nomorhp))) {
        // cek apakah no hp karakter 1-3 adalah +62
        if (substr(trim($nomorhp), 0, 3) == '+62') {
            $nomorhp = trim($nomorhp);
        }
        // cek apakah no hp karakter 1 adalah 0
        elseif (substr($nomorhp, 0, 1) == '0') {
            $nomorhp = '+62' . substr($nomorhp, 1);
        }
    }
    return $nomorhp;
}

function createCode($tipe)
{
    //$tipe 
    //1. Online
    //2. Offile
    $cek_kode = Transaksi::latest()->first();
    if (empty($cek_kode->kode_trans)) {
        $kode_trans = 'SJB-'.$tipe.'-' . date('Ymd') . '-1000';
    } else {
        $kode = substr($cek_kode->kode_trans, 15);
        $kode = $kode + 1;
        $kode_trans = 'SJB-'.$tipe.'-' . date('Ymd') . '-' . $kode;
    }
    return $kode_trans;
}

function enc($data){
    $str=Crypt::encryptString($data);
    return $str;
}

function dec($data){
    $str=Crypt::decryptString($data);
    return $str;
}

function dataMenu(){
	$menu=Menu::orderBy('indek','asc')->get();
    return $menu;
}

function saldo(){
    $xen=new XenditController();
    $saldo=$xen->saldo();
    return $saldo['balance'];
}

function kasBesar(){
    $kas=Kas::where('jenis','2')->orderBy('created_at','desc')->first();
    if($kas){
        return $kas->nominal;
    }else{
        return '0';
    }
}

function kasKecil(){
    $kas=Kas::where('jenis','1')->orderBy('created_at','desc')->first();
    if($kas){
        return $kas->nominal;
    }else{
        return '0';
    }
}
