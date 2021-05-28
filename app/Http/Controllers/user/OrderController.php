<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\Detailorder;
use App\Rekening;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Midtrans\Config;
use App\Http\Controllers\Midtrans\Snap;

class OrderController extends Controller
{

    public function index()
    {
        //menampilkan semua data pesanan
        $user_id = \Auth::user()->id;

        $order = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', 1)
            ->where('order.user_id', $user_id)->get();
        $dicek = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 5)
            ->Where('order.status_order_id', '!=', 6)
            ->where('order.user_id', $user_id)->get();
        $histori = DB::table('order')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'status_order.name')
            ->where('order.status_order_id', '!=', 1)
            ->Where('order.status_order_id', '!=', 2)
            ->Where('order.status_order_id', '!=', 3)
            ->Where('order.status_order_id', '!=', 4)
            ->where('order.user_id', $user_id)->get();
        $data = array(
            'order' => $order,
            'dicek' => $dicek,
            'histori' => $histori
        );
        return view('user.order.order', $data);
    }

    public function initPayementGateway()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

    public function generatePaymentToken($invoice)
    {
        $this->initPayementGateway();

        $order = Order::where('invoice', $invoice)->first();
        if ($order) {
            $user = User::findOrFail($order->user_id);
            $customer_details = array(
                'first_name'       => $user->name,
                'email'            => $user->email,
                'phone'            => "081322311801",
            );

            $params = array(
                'enable_payments' => \App\Payment::PAYMENT_CHANNELS,
                'transaction_details' => [
                    'order_id' => $order->invoice,
                    'gross_amount' => (int)$order->subtotal + (int) $order->ongkir
                ],
                'customer_details' => $customer_details,
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s T'),
                    'unit' => \App\Payment::EXPIRY_UNIT,
                    'duration' => \App\Payment::EXPIRY_DURATION
                ]
            );

            // $order_details = Detailorder::where('order_id', $order->id)->get();

            // $order_item = [];

            // foreach ($order_details as $order_detail) {
            //     $order_detail->id = $order_detail->id;
            //     $order_detail->price = 2000;
            //     array_push($order_item, $order_detail);
            // };
            // dd($order_item);

            // $items = array(
            //     [
            //         'id'       => 'item1',
            //         'price'    => 100000,
            //         'quantity' => 1,
            //         'name'     => 'Adidas f50'
            //     ],
            //     [
            //         'id'       => 'item2',
            //         'price'    => 50000,
            //         'quantity' => 2,
            //         'name'     => 'Nike N90'
            //     ]
            // );
        }

        // Populate customer's info

        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;


        // Redirect to Snap Payment Page
        return Redirect::to($paymentUrl);
    }

    public function detail($id)
    {
        //function menampilkan detail order
        $detail_order = DB::table('detail_order')
            ->join('products', 'products.id', '=', 'detail_order.product_id')
            ->join('order', 'order.id', '=', 'detail_order.order_id')
            ->select('products.name as nama_produk', 'products.image', 'detail_order.*', 'products.price', 'order.*')
            ->where('detail_order.order_id', $id)
            ->get();
        $order = DB::table('order')
            ->join('users', 'users.id', '=', 'order.user_id')
            ->join('status_order', 'status_order.id', '=', 'order.status_order_id')
            ->select('order.*', 'users.name as nama_pelanggan', 'status_order.name as status')
            ->where('order.id', $id)
            ->first();
        $data = array(
            'detail' => $detail_order,
            'order'  => $order
        );
        return view('user.order.detail', $data);
    }

    public function sukses()
    {
        //menampilkan view terimakasih jika order berhasil dibuat
        return view('user.terimakasih');
    }

    public function kirimbukti($id, Request $request)
    {
        //mengupload bukti pembayaran
        $order = Order::findOrFail($id);
        if ($request->file('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran')->store('buktibayar', 'public');

            $order->bukti_pembayaran = $file;
            $order->status_order_id  = 2;

            $order->save();
        }
        return redirect()->route('user.order');
    }

    public function pembayaran($id)
    {
        //menampilkan view pembayaran
        $data = array(
            'rekening' => Rekening::all(),
            'order' => Order::findOrFail($id)
        );
        return view('user.order.pembayaran', $data);
    }

    public function pesananditerima($id)
    {
        //function untuk menerima pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 5;
        $order->save();

        return redirect()->route('user.order');
    }

    public function pesanandibatalkan($id)
    {
        //function untuk membatalkan pesanan
        $order = Order::findOrFail($id);
        $order->status_order_id = 6;
        $order->save();

        return redirect()->route('user.order');
    }

    public function simpan(Request $request)
    {
        //untuk menyimpan pesanan ke table order
        $cek_invoice = DB::table('order')->where('invoice', $request->invoice)->count();
        if ($cek_invoice < 1) {
            $userid = \Auth::user()->id;
            //jika pelanggan memilih metode cod maka insert data yang ini


            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
            Config::$is3ds = env('MIDTRANS_IS_3DS');
            // jika memilih transfer maka data yang ini
            $save = Order::create([
                'invoice' => $request->invoice,
                'user_id' => $userid,
                'subtotal' => $request->subtotal,
                'status_order_id' => 3,
                'metode_pembayaran' => 'transfer',
                'ongkir' => $request->ongkir,
                'courier' => $request->courier,
                'no_hp' => $request->no_hp,
                'pesan' => $request->pesan
            ]);

            // Membuat Transaksi Midtrans
            $user = User::where('id', \Auth::user()->id)->first();

            $transaction_details = [
                'order_id' => $save->invoice,
                'gross_amount' => $save->subtotal, // no decimal allowed for creditcard
            ];

            $customer_details = [
                'first_name'    => \Auth::user()->name,
                'email'         => $user->email,
                'phone'         => $request->no_hp,
            ];

            $enable_payments = [
                "credit_card", "cimb_clicks", "bca_klikbca",
                "bca_klikpay", "bri_epay", "echannel", "permata_va",
                "bca_va", "bni_va", "bri_va", "other_va", "gopay",
                "indomaret", "danamon_online", "akulaku", "shopeepay"
            ];

            $transactionMidtrans = [
                'enabled_payments' => $enable_payments,
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
            ];

            try {
                $url_transaction = Snap::createTransaction($transactionMidtrans)->redirect_url;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }


        $order = DB::table('order')->where('invoice', $request->invoice)->first();

        $barang = DB::table('keranjang')->where('user_id', $userid)->get();
        //lalu masukan barang2 yang dibeli ke table detail order
        foreach ($barang as $brg) {
            Detailorder::create([
                'order_id' => $order->id,
                'product_id' => $brg->products_id,
                'qty' => $brg->qty,
            ]);
        }
        //lalu hapus data produk pada keranjang pembeli
        DB::table('keranjang')->where('user_id', $userid)->delete();
        return redirect($url_transaction);
    }
}
