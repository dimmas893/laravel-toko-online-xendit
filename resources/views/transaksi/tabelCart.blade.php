<?php
$totalModal;
$subTotal=0;
$totalBiaya=0;
?>
@if(session('cart'))
@foreach(session('cart') as $index => $item)
<tr>
    <td>{{$index}}</td>
    <td>
        <input type="hidden" id="cartId" name="cartId" value="{{$item['id']}}">
        <img src="gambar/{{$item['image']}}" alt="contact-img"
        title="contact-img" class="rounded me-3" height="35" />
        <p class="m-0 d-inline-block align-middle font-16">
            <a href="apps-ecommerce-products-details.html"
            class="text-body">{{$item['name']}}</a>
        </p>
    </td>
    <td valign="center">
       <input type="text" name="harga_modal" id="cartHargaModal" data-id="{{$item['id']}}" value="{{number_format($item['price'],0,',','.')}}" class="form-control" readonly>
     
   </td>
   <td valign="center">
       <input type="text" name="biaya" id="cartBiaya" data-id="{{$item['id']}}" data-modal="{{$item['price']}}" value="{{number_format($item['biaya'],0,',','.')}}" class="form-control input" placeholder="">
    
    </td>
   <td valign="center">
       <input type="text" name="harga_jual" id="cartHargaJual" data-id="{{$item['id']}}" value="{{number_format($item['harga_jual'],0,',','.')}}" class="form-control input" placeholder="">
      
    </td>
   <td valign="center">
    <input type="text" min="1" value="{{$item['quantity']}}" class="form-control" id="cartJumlah" data-id="{{$item['id']}}" 
    placeholder="Jumlah" style="width: 90px;">
</td>
<td>
    @php
    $total=$item['harga_jual']*$item['quantity']
    @endphp
    <p id="cartTotal">{{number_format($total,0,',','.')}}</p>
</td>
<td>
    <a href="javascript:void(0);" id="cartRemove" data-id="{{$item['id']}}" class="btn btn-danger"> <i
        class="mdi mdi-delete"></i>
</a>

    </td>
</tr>
<?php 
// $modal=$item['price']*$item['quantity'];
// $totalModal+=$modal;

$biaya=$item['biaya']*$item['quantity'];
$totalBiaya+=$biaya;

$subTotal += $total;
?>
@endforeach
@endif

<tr style="display: none;">
    <td class="table-light" colspan="3"><b>Total<b></td><td align="right" class="table-light" colspan="3">
    <input type="hidden" value="{{$totalBiaya}}" name="totalbiaya" id="totalbiaya">
        <input type="hidden" value="{{number_format($subTotal,0,',','.')}}" name="subtotal" id="subtotal">
    </td>
</tr>

<?php
if(session()->has('ppn')){
    $ppn=($subTotal/100)*website()->trx_ppn;
    ?>
    <script>
        $('#ppn').prop('checked', true);
        $('.ppn').html('Rp. {{number_format($ppn,0,',','.')}}');
    </script>
    <?php
}else{
    $ppn=0;
}

if(session()->has('pph')){
    $pph=($totalBiaya/100)*website()->trx_pph;
    ?>
    <script>
        $('#pph').prop('checked', true);
        $('.pph').html('Rp. {{number_format($pph,0,',','.')}}');
    </script>
    <?php
}else{
    $pph=0;
}

$grandTotal=$subTotal+$ppn;
$terimaTotal=($subTotal+$ppn)-$pph;
?>
<script>
    
    $(".subTotalCart").html('Rp. {{number_format($subTotal,0,',','.')}}')
    $(".grandTotal").html('Rp. {{number_format($grandTotal,0,',','.')}}')
    $(".terimaTotal").html('Rp. {{number_format($terimaTotal,0,',','.')}}')
</script>
<?php
?>