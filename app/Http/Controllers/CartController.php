<?php

namespace App\Http\Controllers;

use App\Models\DistrictOngkir;
use App\Models\Produk;
use App\Models\Province;
use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use App\Models\Pelanggan;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Transaksi;
use App\Models\Xeninvoice;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cartList()
    {
        // $provinces = OngkirProvince::pluck('name', 'province_id');
        $this->tax();
        $cartItems = \Cart::getContent();
        $subTotal = \Cart::getSubTotal();
        $condition = \Cart::getCondition('ppn');
        $ppn = $condition->parsedRawValue;
        return view('website.cart', compact('cartItems', 'ppn'));
    }


    public function addToCart($id)
    {
        $produk = Produk::find($id);
        if ($produk->jumlah > 0) {
            \Cart::add([
                'id' => $produk->id,
                'name' => $produk->nama_produk,
                'price' => $produk->harga_jual,
                'quantity' => 1,
                'image' => $produk->gambar_utama,
                'berat' => $produk->berat,
            ]);

            session()->flash('success', 'Produk telah ditambahkan ke Keranjang !');
            return redirect()->route('cart.list');
        } else {
            session()->flash('alert', 'Stock produk telah habis !');
            return redirect()->back();
        }
    }

    public function updateCart(Request $request)
    {
        \Cart::update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item telah diperbaharui !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        \Cart::remove($request->id);
        session()->flash('success', 'Item telah dihapus !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        \Cart::clear();

        session()->flash('success', 'Keranjang belanja telah dikosongkan !');

        return redirect()->route('cart.list');
    }


    public function tax()
    {
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'ppn',
            'type' => 'tax',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => website()->trx_ppn . '%',
            'attributes' => array( // attributes field is optional
                'description' => 'Value added tax',
                'more_data' => 'more data here'
            )
        ));

        \Cart::condition($condition);
    }

    public function checkout(Request $request)
    {

        $request->validate([
            "nama_depan" => "required",
            "nama_belakang" => "required",
            "email" => "required|email",
            "telpon" => "required",
            "province_destination" => "required",
            "city_destination" => "required",
            "district_destination" => "required",
            "kode_pos" => "required",
            "alamat" => "required",
            "courier" => "required",
            "paket" => "required",
        ]);

        if (\Cart::isEmpty()) {
            return redirect()->back()->with('alert', 'Keranjang Masih Kosong');
        }



        $result = DB::transaction(function () use ($request) {
            $condition = \Cart::getCondition('biaya');
            $biayaKirim = $condition->getValue();

            $condition = \Cart::getCondition('ppn');
            $subTotal = \Cart::getSubTotal();
            $ppn = $condition->parsedRawValue;
            $pph = 0;
            $total = $subTotal;
            $gtotal = $biayaKirim + $subTotal;
            $subTotal = $subTotal - $ppn;



            $invoice = createCode(1);
            $trans = [
                'invoice' => $invoice,
                'amount' => $gtotal,
                'deskription' => 'Tagihan Pembelian Produk di ' . config('app.name'),
                'duration' => website()->trx_duration_online,
            ];

            $provinsi = ProvinceOngkir::find($request->province_destination);
            $provinsi = $provinsi->name;
            $kota = RegencyOngkir::find($request->city_destination);
            $kota = $kota->name;
            $kecamatan = DistrictOngkir::find($request->district_destination);
            $kecamatan = $kecamatan->name;
            $hp = gantiformat($request->telpon);
            $customer = [
                'given_names' => $request->nama_depan,
                'surname' => $request->nama_belakang,
                'email' => $request->email,
                'mobile_number' => $hp,
                'address' => [
                    [
                        'city' => $kota,
                        'country' => 'Indonesia',
                        'postal_code' => $request->kode_pos,
                        'state' => $provinsi,
                        'street_line1' => $request->alamat,
                        'street_line2' => $kecamatan,
                    ]
                ]
            ];

            $ongkir = $request->paket;
            $successUrl = config('app.url') . '/transaksi/confirs/' . $invoice;
            $failurUrl = config('app.url') . '/transaksi/failed/' . $invoice;


            $cartItems = \Cart::getContent();
            // $totalBerat=0;
            // $totalBelanja=0;
            $data = array();
            foreach ($cartItems as $index => $i) {
                $data[] = array(
                    'name' => $i->name,
                    'quantity' => $i->quantity,
                    'price' => $i->price,
                );

                //     $berat=$item->berat*$item->quantity;
                //     $totalBerat+=$berat;
                //     $belanja=$item->price*$item->quantity;
                //     $totalBelanja+=$belanja;
            }

            $item = $data;
            //   dd($item);

            $pelanggan = Pelanggan::create([
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'perusahaan' => '',
                'email' => $request->email,
                'telpon' => $request->telpon,
                'hp' => $request->telpon,
                'provinsi_id' => $request->province_destination,
                'kota_id' => $request->city_destination,
                'kecamatan_id' => $request->district_destination,
                'desa_id' => '0',
                'pos' => $request->kode_pos,
                'alamat' => $request->alamat,
                'logo' => '',
                'status_data' => '1',
            ]);
            $pelanggan_id = $pelanggan->id;




            $totalModal = 0;
            foreach ($cartItems as $index => $i) {
                $itemTotal = 0;
                $itemTotal = $i->quantity * $i->price;
                $totalModal += $itemTotal;
                $order = Order::create([
                    'produk_id' => $i->id,
                    'trans_kode' => $invoice,
                    'biaya' => 0,
                    'jumlah' => $i->quantity,
                    'harga_jual' => $i->price,
                    'total' => $itemTotal,
                    'status_data' => 1,
                ]);
                // if ($order->id) {
                //     $stock = Stock::create([
                //         'produk_id' => $i->id,
                //         'jumlah' => $i->quantity,
                //         'harga' => $i->price,
                //         'status' => 3,
                //         'ref_id' => $invoice,
                //         'deskripsi' => '',
                //         'status_data' => '1',
                //     ]);
                // }
            }

            $transaksi = Transaksi::create([
                'kode_trans' => $invoice,
                'tgl_trans' => date('Y-m-d'),
                'pelanggan_id' => $pelanggan_id,
                'totalModal' => $totalModal,
                'totalBiaya' => 0,
                'subTotal' => $subTotal,
                'ppn' => $ppn,
                'pph' => 0,
                'total' => $gtotal,
                'deskripsi' => $request->catatan,
                'status_trans' => 1,
                'jenis_trans' => 1,
            ]);

            $shipping = Shipping::create([
                'transaksi_id' => $transaksi->id,
                'resi' => '',
                'kurir' => $request->courier,
                'biaya' => $request->paket,
            ]);


            $xendit = new XenditController();
            $proses = $xendit->createInvoice($trans, $customer, $item, $ongkir, $ppn, $pph, $successUrl, $failurUrl);
            if ($proses['status'] == 'PENDING') {
                $xenvoice = Xeninvoice::create([
                    'transaksi_id' => $transaksi->id,
                    'xen_id' => $proses['id'],
                    'xen_user_id' => $proses['user_id'],
                    'xen_external_id' => $proses['external_id'],
                    'xen_status' => $proses['status'],
                    'xen_invoice_url' => $proses['invoice_url'],
                    'xen_expiry_date' => $proses['expiry_date'],
                ]);

                \Cart::clear();
                // dd($request->all());
                return $proses['invoice_url'];
            } else {
                return false;
            }
        });
        if ($result == false) {
            return redirect('/transaksi/failed');
        } else {
            return redirect($result);
        }
    }

    public function addOngkir(Request $request)
    {
        $condition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'biaya',
            'type' => 'shipping',
            'target' => '', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => $request->ongkir,
            'order' => 1
        ));
        $condition = \Cart::condition($condition);
        if ($condition) {
            $response = [
                'success' => true,
                'message' => 'Berhasil Ditambahkan.',
            ];
            return response()->json($response, 200);
        }
    }
}
