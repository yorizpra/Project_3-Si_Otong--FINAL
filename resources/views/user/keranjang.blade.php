@extends('user.app')
@section('content')
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cart | E-Shopper</title>
    <link href="{{ asset('eshopper') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('eshopper') }}/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('eshopper') }}/css/prettyPhoto.css" rel="stylesheet">
    <link href="{{ asset('eshopper') }}/css/price-range.css" rel="stylesheet">
    <link href="{{ asset('eshopper') }}/css/animate.css" rel="stylesheet">
	<link href="{{ asset('eshopper') }}/css/main.css" rel="stylesheet">
	<link href="{{ asset('eshopper') }}/css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->       
    <link rel="shortcut icon" href="{{ asset('eshopper') }}/images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('eshopper') }}/images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('eshopper') }}/images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('eshopper') }}/images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('eshopper') }}/images/ico/apple-touch-icon-57-precomposed.png">
</head><!--/head-->


<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="{{ route('home') }}">Beranda</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Keranjang</strong></div>
    </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
    <div class="row mb-5">
        <form class="col-md-12" method="post" action="{{ route('user.keranjang.update') }}">
        @csrf




<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<tr>
                            <?php $subtotal=0; foreach($keranjangs as $keranjang): ?>
							<td class="cart_product">
								<a href=""><img src="{{ asset('storage/'.$keranjang->image) }}" alt="Image" class="img-fluid" width="150"></a>
							</td>
							<td class="cart_description">
								<h4><a href="">{{ $keranjang->nama_produk }}</a></h4>
							</td>
							<td class="cart_price">
								<p>Rp. {{ number_format($keranjang->price,2,',','.') }} </p>
							</td>
                            <td>
                                <div class="input-group mb-3" style="max-width: 35px;">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
                                </div>
                                <input type="hidden" name="id[]" value="{{ $keranjang->id }}">
                                <input type="text" name="qty[]" class="form-control text-center" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1" value="{{ $keranjang->qty }}">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
                                </div>
                                </div>
                            </td>
                            <?php
                                $total = $keranjang->price * $keranjang->qty;
                                $subtotal = $subtotal + $total;
                            ?>
							<td class="cart_total">
								<p class="cart_total_price">Rp. {{ number_format($total,2,',','.') }}</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="{{ route('user.keranjang.delete',['id' => $keranjang->id]) }}"><i class="fa fa-times"></i></a>
							</td>
						</tr>
                        <?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</section> <!--/#cart_items-->





            
        
    </div>

    <div class="row">
        <div class="col-md-6">
        <div class="row mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
            <button type="submit" class="btn btn-primary btn-sm btn-block">Update Keranjang</button>
            </div>
            </form>       
        </div>
        </div>
        <div class="col-md-6 pl-5">
        <div class="row justify-content-end">
            <div class="col-md-7">
            <div class="row">
                <div class="col-md-12 text-right border-bottom mb-5">
                <h3 class="text-black h4 text-uppercase">Total Belanja</h3>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6">
                <span class="text-black">Total</span>
                </div>
                <div class="col-md-6 text-right">
                <strong class="text-black">Rp. {{ number_format($subtotal,2,',','.') }}</strong>
                </div>
            </div>

            <div class="row">
                @if($cekalamat > 0)
                <div class="col-md-12">
                <a href="{{ route('user.checkout') }}" class="btn btn-primary btn-lg py-3 btn-block" >Checkout</a>
                <small>Jika Merubah Quantity Pada Keranjang Maka Klik Update Keranjang Dulu Sebelum Melakukan Checkout</small>
                </div>
                @else
                <div class="col-md-12">
                <a href="{{ route('user.alamat') }}" class="btn btn-primary btn-lg py-3 btn-block" >Atur Alamat</a>
                <small>Anda Belum Mengatur Alamat</small>
                </div>
                @endif
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection