<?php

namespace App\Http\Controllers;

use App\Models\OngkirCity;
use App\Models\OngkirProvince;
use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use App\Models\DistrictOngkir;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use PhpParser\JsonDecoder;
use Illuminate\Support\Facades\Http;

class CheckOngkirController extends Controller
{
    public function index()
    {
        $provinces = OngkirProvince::pluck('name', 'province_id');
        return view('website.ongkir', compact('provinces'));
    }

    public function getCities($id)
    {
        $city = OngkirCity::where('province_id', $id)->pluck('name', 'city_id');
        return response()->json($city);
    }

    public function check_ongkir(Request $request)
    {
        $cost = RajaOngkir::ongkosKirim([
            'origin'        => $request->city_origin, // ID kota/kabupaten asal
            'destination'   => $request->city_destination, // ID kota/kabupaten tujuan
            'weight'        => $request->weight, // berat barang dalam gram
            'courier'       => $request->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();


        return response()->json($cost);
    }

    public function getAllProvinsi()
    {
        // dd('ds');
        // $ongkirProvinsi = ProvinceOngkir::orderBy('name', 'asc')->get();
        $response = Http::withHeaders([
            'key' => 'ae91f7b2f38c5da665525ce2bf62b2b6',
        ])->get('https://pro.rajaongkir.com/api/province');
        $array = $response->getBody()->getContents();
        $json = json_decode($array, true);
        $collection = collect($json);
        if (isset($collection['rajaongkir']['results'])) {
            $data = $collection['rajaongkir']['results'];
            echo "<option value=''>Pilih Provinsi</option>";
            foreach ($data as $prov) {
                echo "<option value='" . $prov['province_id'] . "'>" . $prov['province'] . "</option>";
            }
        } else {
            return new JsonResponse([
                'status' => 'success',
                'message' => _('no data')
            ], 404);
        }
    }

    public function getAllKota($id)
    {
        // $ongkirKota = RegencyOngkir::where('province_id', $id)->orderBy('name', 'asc')->get();
        $dataprovince = (int)$id;
        $response = Http::withHeaders([
            'key' => 'ae91f7b2f38c5da665525ce2bf62b2b6',
        ])->get('https://pro.rajaongkir.com/api/city?province=' . $dataprovince);
        $array = $response->getBody()->getContents();
        $json = json_decode($array, true);
        $collection = collect($json);
        // dd($collection);
        if (isset($collection['rajaongkir']['results'])) {
            $data = $collection['rajaongkir']['results'];
            echo "<option value=''>Pilih Kota</option>";
            foreach ($data as $kota) {
                echo "<option value='" . $kota['city_id'] . "'>" . $kota['city_name'] . ' - ' . $kota['postal_code'] . "</option>";
            }
            // return $data;
        } else {
            return new JsonResponse([
                'status' => 'success',
                'message' => _('no data')
            ], 404);
        }
    }

    public function getAllKecamatan($id)
    {
        $ongkirKecamatan = DistrictOngkir::where('regency_id', $id)->orderBy('name', 'asc')->get();
        echo "<option value=''>Pilih Kecamatan</option>";
        foreach ($ongkirKecamatan as $kecamatan) {
            echo "<option value='" . $kecamatan['id'] . "'>" . $kecamatan['name'] . "</option>";
        }
    }

    public function costs(Request $request)
    {
        // dd($request->all());
        $origin = website()->kota;
        // dd($origin);
        $curl = curl_init();
        $berat = (int) $request->berat;
        // dd($berat);origin=" . $origin . "&destination=" . $request->tujuan .
        // CURLOPT_POSTFIELDS => "origin=501&originType=city&destination=" . $request->tujuan . "&weight=" . (int) $request->berat . "&courier=" . $request->kurir,
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => 'origin=501&destination=114&weight=' . $berat . '&courier=' . $request->kurir,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 5a0895a9493036fcb4157ebeca070e9c"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $data = json_decode($response, true);
        $collection = collect($data);
        // dd($collection);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, TRUE);
            $collection = collect($data);
            // dd($collection);
            if (isset($data["rajaongkir"]["results"]["0"]["costs"])) {
                $costs = $data["rajaongkir"]["results"]["0"]["costs"];
                echo "<option value=''>Pilih Paket</option>";
                foreach ($costs as $cost) {
                    if ($request->kurir <> 'pos') {
                        $day = 'HARI';
                    } else {
                        $day = '';
                    }
                    $text = $cost['service'] . " - Biaya Rp. " . number_format($cost['cost']['0']['value'], 0, ',', '.') . " / " . $cost['cost']['0']['etd'] . " " . $day;
                    echo "<option data-paket='" . $cost['service'] . "' data-ongkir='" . $cost['cost']['0']['value'] . "' value='" . $cost['cost']['0']['value'] . "'  data-etd='" . $cost['cost']['0']['etd'] . "'>" . $text . "</option>";
                }
            } else {

                echo "<option>--Silahkan pilih espedisi lain---</option>";
            }
        }
    }
}
