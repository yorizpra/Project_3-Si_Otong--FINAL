<?php

namespace App\Http\Controllers\user;

use App\Alamat;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class CheckoutController extends Controller
{
    public function index()
    {
        //ambil session user id
        $id_user = \Auth::user()->id;
        //ambil produk apa saja yang akan dibeli user dari table keranjang
        $keranjangs = DB::table('keranjang')
            ->join('users', 'users.id', '=', 'keranjang.user_id')
            ->join('products', 'products.id', '=', 'keranjang.products_id')
            ->select('products.name as nama_produk', 'products.image', 'products.weigth', 'users.name', 'keranjang.*', 'products.price')
            ->where('keranjang.user_id', '=', $id_user)
            ->get();

        //lalu hitung jumlah berat total dari semua produk yang akan di beli
        $berattotal = 0;
        foreach ($keranjangs as $k) {
            $berat = $k->weigth * $k->qty;
            $berattotal = $berattotal + $berat;
        }
        //lalu ambil id kota si pelanngan
        $city = DB::table('alamat')->where('user_id', $id_user)->get();
        $city_destination =  $city[0]->cities_id;
        //ambil id kota toko
        $alamat_toko = DB::table('alamat_toko')->first();


        //lalu ambil alamat user untuk ditampilkan di view
        $alamat_user = DB::table('alamat')
            ->join('cities', 'cities.city_id', '=', 'alamat.cities_id')
            ->join('provinces', 'provinces.province_id', '=', 'cities.province_id')
            ->select('alamat.*', 'cities.title as kota', 'provinces.title as prov')
            ->where('alamat.user_id', $id_user)
            ->first();

        //buat kode invoice sesua tanggalbulantahun dan jam
        $invoice = 'STG' . Date('Ymdhi');
        $alamat = $alamat_user;

        return view('user.checkout', compact('invoice', 'keranjangs', 'alamat'));
    }

    public function check_ongkir(Request $request)
    {
        $keranjangs = $request->keranjangs;
        $alamat = $request->alamat;
        $id_user = \Auth::user()->id;

        $berattotal = 0;
        foreach ($keranjangs as $k) {
            $berat = $k['weigth'] * $k['qty'];
            $berattotal = $berattotal + $berat;
        }

        $city = Alamat::where('user_id', $id_user)->get();
        $city_destination =  $city[0]->cities_id;

        $cost = RajaOngkir::ongkosKirim([
            'origin'  => $alamat['id'],
            'destination' => $city_destination,
            'weight' => $berattotal,
            'courier' => $request->courier
        ])->get();


        return response()->json($cost);
    }
}
