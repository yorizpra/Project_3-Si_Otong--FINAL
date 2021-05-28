@extends('user.app')
@section('content')
<div class="site-blocks-cover" style="background-image: url({{ asset('shopper') }}/images/hero_1.jpg);" data-aos="fade">
    <div class="container">
    <div class="row align-items-start align-items-md-center justify-content-end">
        <div class="col-md-5 text-center text-md-left pt-5 pt-md-0">
        <h1 class="mb-2">Cari Kebutuhan Olahraga Kamu Di Sini</h1>
        <div class="intro-text text-center text-md-left">
            <p class="mb-4">Alat olahraga disini terjamin kualitasnya dan tentunya barangnya juga original bukan kw. </p>
            <p>
            <a href="{{ route('user.produk') }}" class="btn btn-sm btn-primary">Belanja Sekarang</a>
            </p>
        </div>
        </div>
    </div>
    </div>
</div>

<div class="site-section site-section-sm site-blocks-1">
    <div class="container">
    <div class="row">
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="">
        <div class="icon mr-4 align-self-start">
            <span class="icon-truck"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Pengiriman</h2>
            <p>Pengiriman bisa ke seluruh wilayah indonesia dengan kurir JNE</p>
        </div>
        </div>
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="100">
        <div class="icon mr-4 align-self-start">
            <span class="icon-star"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Kualitas Oke</h2>
            <p>Kualitas barangnya terjamin karena semuanya disini original bukan kw.</p>
        </div>
        </div>
        <div class="col-md-6 col-lg-4 d-lg-flex mb-4 mb-lg-0 pl-4" data-aos="fade-up" data-aos-delay="200">
        <div class="icon mr-4 align-self-start">
            <span class="icon-security"></span>
        </div>
        <div class="text">
            <h2 class="text-uppercase">Keamanan</h2>
            <p>Kami menjamin keamanan dalam pengiriman barang sampai diterima oleh anda.</p>
        </div>
        </div>
    </div>
    </div>
</div>
<div class="site-section block-3 site-blocks-2 bg-light"  data-aos="fade-up">
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 site-section-heading text-center pt-4">
        <h2>Produk Toko Kelontong</h2>
        </div>
    </div>
    <div class="site-section">
        <div class="container">
    
        <div class="row mb-5">
            <div class="col-md-9 order-2">
    
            <!-- <div class="row">
                <div class="col-md-12 mb-5">
                <div class="float-md-left mb-4"><h2 class="text-black h5">Shop All</h2></div>
                <div class="d-flex">
                    <div class="dropdown mr-1 ml-md-auto">
                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Latest
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                        <a class="dropdown-item" href="#">Men</a>
                        <a class="dropdown-item" href="#">Women</a>
                        <a class="dropdown-item" href="#">Children</a>
                    </div>
                    </div>
                    <div class="btn-group">
                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" id="dropdownMenuReference" data-toggle="dropdown">Reference</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                        <a class="dropdown-item" href="#">Relevance</a>
                        <a class="dropdown-item" href="#">Name, A to Z</a>
                        <a class="dropdown-item" href="#">Name, Z to A</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Price, low to high</a>
                        <a class="dropdown-item" href="#">Price, high to low</a>
                    </div>
                    </div>
                </div>
                </div>
            </div> -->
            <div class="row mb-5">
                @foreach($produks as $produk)
                <div class="col-sm-6 col-lg-4 mb-4" data-aos="fade-up">
                <div class="block-4 text-center border">
                    <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">
                        <img src="{{ asset('storage/' . $produk->image) }}" alt="Image placeholder" class="img-fluid" width="100%" style="height:200px">
                    </a>
                    <div class="block-4-text p-4">
                    <h3><a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}">{{ $produk->name }}</a></h3>
                    <p class="mb-0">RP {{ $produk->price }}</p>
                    <a href="{{ route('user.produk.detail',['id' =>  $produk->id]) }}" class="btn btn-primary mt-2">Detail</a>
                    </div>
                </div>
                </div>
                @endforeach
                
    
            </div>
            <div class="row" data-aos="fade-up">
                <div class="col-md-12 text-right">
                <div class="site-block-27">
                {{ $produks->links() }}
                </div>
                </div>
            </div>
            </div>
    
            <div class="col-md-3 order-1 mb-5 mb-md-0">
            <div class="border p-4 rounded mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Kategori</h3>
                <ul class="list-unstyled mb-0">
                @foreach($categories as $categori)
                <li class="mb-1"><a href="{{ route('user.kategori',['id' => $categori->id]) }}" class="d-flex"><span>{{ $categori->name }}</span> <span class="text-black ml-auto">( {{ $categori->jumlah }} )</span></a>
                </li>
                @endforeach
                </ul>
            </div>
    
            <!-- <div class="border p-4 rounded mb-4">
                <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                <div id="slider-range" class="border-primary"></div>
                <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white" disabled="" />
                </div>
    
                <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                <label for="s_sm" class="d-flex">
                    <input type="checkbox" id="s_sm" class="mr-2 mt-1"> <span class="text-black">Small (2,319)</span>
                </label>
                <label for="s_md" class="d-flex">
                    <input type="checkbox" id="s_md" class="mr-2 mt-1"> <span class="text-black">Medium (1,282)</span>
                </label>
                <label for="s_lg" class="d-flex">
                    <input type="checkbox" id="s_lg" class="mr-2 mt-1"> <span class="text-black">Large (1,392)</span>
                </label>
                </div>
    
                <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Color</h3>
                <a href="#" class="d-flex color-item align-items-center" >
                    <span class="bg-danger color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Red (2,429)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                    <span class="bg-success color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Green (2,298)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                    <span class="bg-info color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Blue (1,075)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                    <span class="bg-primary color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Purple (1,075)</span>
                </a>
                </div>
    
            </div> -->
            </div>
        </div>
        
        </div>
    </div>
    </div>
</div>
    @endsection