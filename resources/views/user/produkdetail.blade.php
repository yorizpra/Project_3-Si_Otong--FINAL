@extends('user.app')
@section('content')

<!-- <div class="site-blocks-cover" style="background-image: url({{ asset('shopper') }}/images/slide.png);" data-aos="fade">
    <div class="container">
    <div class="row align-items-start align-items-md-center justify-content-end">
        <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
        <h1 class="mb-2">Si-Otong</h1>
        <h2>Aplikasi Toko Kelontong</h2>
        <div class="intro-text text-center text-md-left">
            <p class="mb-4">Penuhi segala kebutuhan rumah tangga Anda sehari-hari dengan koleksi produk di Si-Otong. </p>
            <p>
            <a href="{{ route('user.produk') }}" class="btn btn-secondary">Belanja Sekarang</a>
            </p>
        </div>
        </div>
    </div>
    </div>
</div> -->

<div class="bg-light py-3">
    <div class="container">
    <div class="row">
        <div class="col-md-12 mb-0"><a href="{{ route('home') }}">Beranda</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Produk</strong></div>
    </div>
    </div>
</div>  

<div class="site-section">
    <div class="container">
    <div class="row">
        <div class="col-md-6">
        <img src="{{ asset('storage/'.$produk->image) }}" alt="Image" class="img-fluid">
        </div>
        <div class="col-md-6">
        <h2 class="text-black">{{ $produk->name }}</h2>
        <p>
            {{ $produk->description }}
        </p>
        <p><strong class="text-primary h4">Rp {{ $produk->price }} </strong></p>
        <div class="mb-5">
            <form action="{{ route('user.keranjang.simpan') }}" method="post">
                @csrf
                @if(Route::has('login'))
                    @auth
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    @endauth
                @endif
            <input type="hidden" name="products_id" value="{{ $produk->id }}">
            <small>Sisa Stok {{ $produk->stok }}</small>
            <input type="hidden" value="{{ $produk->stok }}" id="sisastok">
            <div class="input-group mb-3" style="max-width: 120px;">
            <div class="input-group-prepend">
            <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
            </div>
            <input type="text" name="qty" class="form-control text-center" value="1" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
            <div class="input-group-append">
            <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
            </div>
        </div>

        </div>
        <p><button type="submit" class="buy-now btn btn-sm btn-primary">Tambah ke keranjang</button></p>
        </form>
        </div>
    </div>
    </div>
</div>
@endsection