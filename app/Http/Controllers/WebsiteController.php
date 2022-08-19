<?php

namespace App\Http\Controllers;

use App\Models\ProvinceOngkir;
use App\Models\RegencyOngkir;
use App\Models\DistrictOngkir;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $website = Website::first();
        $provinces = ProvinceOngkir::orderBy('name', 'asc')->get();
        $city = RegencyOngkir::find($website->kota);
        $district = DistrictOngkir::find($website->kecamatan);
        return view('website/website_setting', compact(['website', 'provinces', 'city', 'district']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    public function updateWebsite(Request $request)
    {
        $website = Website::find($request->id);

        $request->validate([
            'nama' => 'required',
            'tagline' => 'required',
            'contact' => 'required',
            'province_destination' => 'required',
            'city_destination' => 'required',
            'district_destination' => 'required',
            'kode_pos' => 'required',
            'alamat' => 'required',
        ], [
            'required' => 'Silahkan lengkapi data',
            'contact.unique' => 'Telpon / HP sudah terdaftar',
        ]);
        if ($request->file) {
            $request->validate([
                'file' => 'mimes:ico,png,jpg,jpeg|max:1024',
            ], [
                'max' => 'Maksimal Kapasitas Foto 1024KB/1MB',
                'mimes' => 'Ekstensi Foto Diizinkan .ico / .png / .jpg / .jpeg'
            ]);
            $filename = 'website' . time() . '.' . $request->file->extension();
            $request->file->move(public_path('websiteIcon'), $filename);
        } else {
            $filename = $website->icon;
        }



        Website::updateOrCreate([
            'id' => $request->id
        ], [
            'nama_website' => $request->nama,
            'tagline' => $request->tagline,
            'provinsi' => $request->province_destination,
            'kota' => $request->city_destination,
            'kecamatan' => $request->district_destination,
            'kode_pos' => $request->kode_pos,
            'address' => $request->alamat,
            'contact' => $request->contact,
            'icon' => $filename,
            'description' => $request->deskripsi,
        ]);
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    public function updateTransaksi(Request $request)
    {
        $request->validate([
            'ppn' => 'required',
            'pph' => 'required',
            'verifikasi' => 'required',
            'markup' => 'required',
            'duration_online' => 'required',
            'duration_offline' => 'required',
        ], [
            'required' => 'Silahkan lengkapi data',
        ]);
        $website = Website::find($request->id);
        $website->trx_ppn = $request->ppn;
        $website->trx_pph = $request->pph;
        $website->trx_verifikasi = $request->verifikasi;
        $website->trx_markup = $request->markup;
        $website->trx_duration_online = $request->duration_online;
        $website->trx_duration_offline = $request->duration_offline;
        $website->save();
        return redirect()->back()->with(['status' => 'success', 'message' => 'Berhasil Diperbaharui!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function edit(Website $website)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        //
    }
}
