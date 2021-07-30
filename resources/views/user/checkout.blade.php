@extends('user.app')
@section('content')
<div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="{{ route('home') }}">Beranda</a> <span class="mx-2 mb-0">/</span> <a href="cart.html">Keranjang</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black">Pesanan Anda</h2>
                <div class="p-3 p-lg-5 border">
                  <form action="{{ route('user.order.simpan') }}" method="POST">
                    @csrf
                  <table class="table site-block-order-table mb-5" id="table-checkout" data-alamat="{{json_encode($alamat)}}" data-keranjangs="{{json_encode($keranjangs)}}">
                    <thead>
                      <th>Produk</th>
                      <th>Total</th>
                    </thead>
                    <tbody>
                      <?php $subtotal=0;?>
                      @foreach($keranjangs as $keranjang)
                      <tr>
                        <td>{{ $keranjang->nama_produk }} <strong class="mx-2">x</strong> {{ $keranjang->qty }}</td>
                        <?php
                          $total = $keranjang->price * $keranjang->qty;
                          $subtotal = $subtotal + $total;
                      ?>
                        <td>Rp. {{ number_format($total,2,',','.') }}</td>
                      </tr>
                      @endforeach
                      <tr>
                        <td>
                          Ongkir
                        </td>
                        <td>
                          Rp. <span id="text-ongkir">0</span>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          Kurir
                        </td>
                        <td>
                          <span id="text-courier"></span>
                        </td>
                      </tr>
                      <tr>
                        <td class="text-black font-weight-bold"><strong>Jumlah Pembayaran</strong></td>
                        <td class="text-black font-weight-bold">
                        <?php $alltotal = $subtotal; ?>  Rp. 
                        <strong id="total-text">{{ number_format($subtotal,2,',','.') }}</strong></td>
                      </tr>
                      <tr>
                      <td>Alamat Penerima</td>
                      <td>{{ $alamat->detail }}, {{ $alamat->kota }}, {{ $alamat->prov }}</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="form-group" id="kurir">
                    <label>Kurir</label>
                    <select class="form-control kurir" name="courier">
                        <option value="0">-- pilih kurir --</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS</option>
                        <option value="tiki">TIKI</option>
                    </select>
                  </div>
                  <div class="form-group d-none" id="pilih-ongkir">
                    <ul class="list-group" id="ongkir2"></ul>
                  </div>
                  <div class="form-group">
                    <label for="">Catatan</label>
                    <textarea name="pesan" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">No telepon yang bisa dihubungi</label>
                    <input type="text" name="no_hp" id="" class="form-control">
                  </div>
                  
                  <input type="hidden" name="invoice" value="{{ $invoice }}">
                  <input type="hidden" name="barang" id="barang" value="{{ $subtotal }}">
                  <input type="hidden" name="subtotal" id="total-inp" value="{{ $subtotal }}">
                  <input type="hidden" name="ongkir" id="inp-ongkir">
                  <input type="hidden" name="courier" id="courier-inp" >
                  <div class="form-group">
                    @php
                        $alamat_second = $alamat;
                        $keranjangs_second = $keranjangs;
                    @endphp
                  {{-- <label for="">Pilih Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="pay-method"  data-keranjangs="@php $keranjangs_second @endphp" class="form-control">
                      <option value="0">-- pilih metode --</option>
                      <option value="trf">Transfer</option>
                      <option value="cod">Cod</option>
                    </select>
                    <small>Jika memilih cod maka akan dikenakan biaya tambahan sebesar Rp. 10.000,00</small> --}}
                  </div>
                  
                 

                  <div class="form-group">
                    <button class="btn btn-primary btn-lg py-3 btn-block" onclick="return confirm('Yakin ingin meneruskan? Pesanan anda tidak dapat dibatalkan!')" type="submit">Pesan Sekarang</button>
                    <small>Mohon periksa alamat penerima dengan benar agar tidak terjadi salah pengiriman</small>
                  </div>
                </form>
                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- </form> -->
      </div>
    </div>
@endsection

@push('cs-script')

<script>


  $('#pay-method').on('change', function () {
      if($(this).val() === "trf"){
        $('#kurir').removeClass('d-none');
        $('#kurir').addClass('d-block');
      }else{
        $('#kurir').removeClass('d-block');
        $('#kurir').addClass('d-none');
      }
  });

  let isProcessing = false;
        $('#kurir').on('change', function (e) {
            e.preventDefault();

            let token            = $("meta[name='csrf-token']").attr("content");
            let keranjangs  =  $('#table-checkout').data('keranjangs');
            let alamat  =  $('#table-checkout').data('alamat');
            let courier          = $('select[name=courier]').val();
            console.log(courier);

            if(isProcessing){
                return;
            }

            isProcessing = true;
            jQuery.ajax({
                url: "/ongkir",
                data: {
                    _token:              token,
                    keranjangs: keranjangs,
                    alamat: alamat,
                    courier: courier,
                },
                dataType: "JSON",
                type: "POST",
                success: function (response) {
                    isProcessing = false;
                    console.log(response);
                    if (response) {
                        $('#ongkir2').empty();
                        $('#pilih-ongkir').addClass('d-block');
                        $.each(response[0]['costs'], function (key, value) {
                            console.log(value.service);
                            $('#ongkir2').append(`<li class="list-group-item">${response[0].code.toUpperCase()} : <strong>${value.service}</strong> - Rp. ${value.cost[0].value} (${value.cost[0].etd} hari) <a href="#" class="btn btn-primary float-right" onclick="chooseOngkir(`+ value.cost[0].value +`,'`+ response[0].code.toUpperCase() +`','`+ value.service +`')">Pilih</a>  </li>`)
                        });

                    }
                },
                error:function(err){
                  console.log(err);
                }

            });

        });


        let total = 0;
        function chooseOngkir(price, courier, service){
          event.preventDefault();

          var	reverse = price.toString().split('').reverse().join(''),
          ribuan 	= reverse.match(/\d{1,3}/g);
          ribuan	= ribuan.join('.').split('').reverse().join('');

          let courierService = courier +' : '+ service;
          $('#text-ongkir').text(ribuan + ',00');
          $('#text-courier').text(courierService);
          $('#inp-ongkir').val(price);
          $('#courier-inp').val(courierService);

          let harga_barang = $('#barang').val();
          totalHarga(harga_barang, price);

        }

        function totalHarga(harga_barang, price){
          total = parseFloat(harga_barang) + parseFloat(price);

          var	reverse = total.toString().split('').reverse().join(''),
          ribuan 	= reverse.match(/\d{1,3}/g);
          ribuan	= ribuan.join('.').split('').reverse().join('');

          $('#total-text').text(ribuan + ',00');
          $('#total-inp').val(total);
        }

</script>
  
@endpush