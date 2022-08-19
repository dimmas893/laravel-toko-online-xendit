<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_transaksi_' . $tanggal . '_status_' . $status_trans . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{  website()->nama_website }} - Laporan Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        body {
            font-size: 12px;
        }

        table th {
            text-align: center;
        }

    </style>
</head>

<body>
    <div style="width: 98%; margin:auto;">
        <h3 class="text-center">Laporan Transaksi</h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif
        @if (!empty($status_trans))
            <b>Status Transaksi : </b> {{ $status_trans }}<br>
        @endif
        <table id="datatable" style="width: 100%;" class="table-striped" border="1px" cellpadding="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Tanggal Pembayaran</th>
                    <!-- <th>Tanggal Transaksi</th> -->
                    <th>Pelanggan</th>
                    <th>Modal</th>
                    <th>Biaya Jasa</th>
                    <th>Sub Total</th>
                    <th>Pengiriman</th>
                    <th>PPN (<?php echo website()->trx_ppn; ?>%)</th>
                    <th>PPH (<?php echo website()->trx_pph; ?>%)</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $modal = 0;
                $biaya = 0;
                $pph = 0;
                $ppn = 0;
                $subtotal = 0;
                $total = 0;
                $totalOngkir=0;
                
                foreach ($transaksi as $index => $trans){
                    if($trans->shipping){
                        $ongkir=$trans->shipping->biaya;
                        $colTotal=$trans->total;
                    }else{
                        $ongkir=0;
                        $colTotal=$trans->total;
                    }
                    ?>
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $trans->kode_trans }}</td>
                        <td>{{ date('d-m-Y', strtotime($trans->updated_at)) }}</td>
                        <!-- <td>{{ date('d-m-Y', strtotime($trans->tgl_trans)) }}</td> -->
                        <td>{{ $trans->pelanggan->nama_depan." ".$trans->pelanggan->nama_belakang }}</td>
                        <td align="right">Rp.{{ number_format($trans->totalModal, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($trans->totalBiaya, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($trans->subtotal, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($ongkir, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($trans->ppn, 0, ',', '.') }}</td>
                        <td align="right">Rp.-{{ number_format($trans->pph, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($colTotal, 0, ',', '.') }}</td>
                    </tr>
                    <?php
                    $totalOngkir+=$ongkir;
                    $modal += $trans->totalModal;
                    $biaya += $trans->totalBiaya;
                    $pph += $trans->pph;
                    $ppn += $trans->ppn;
                    $subtotal += $trans->subtotal;
                    $total += $colTotal;
                }
                ?>
                <tr style="background-color:white">
                    <td colspan="4" align="left"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($modal, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($biaya, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($subtotal, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($totalOngkir, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($ppn, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.-{{ number_format($pph, 0, ',', '.') }}</b></td>
                    <td colspan="2" align="right"><b>Rp.{{ number_format($total, 0, ',', '.') }}</b></td>
                </tr>
            </tbody>
        </table>
        <b>Rumus</b><br>
        Total : Sub Total + Pengiriman + PPN - PPH<br>
        PPN : (Sub Total / 100) x <?php echo website()->trx_ppn; ?><br>
        PPH : (Biaya Jasa / 100) x <?php echo website()->trx_pph; ?> 
    </div>
    <script>
        var css = '@page { size: landscape; }',
    head = document.head || document.getElementsByTagName('head')[0],
    style = document.createElement('style');

style.type = 'text/css';
style.media = 'print';

if (style.styleSheet){
  style.styleSheet.cssText = css;
} else {
  style.appendChild(document.createTextNode(css));
}

head.appendChild(style);

window.print();

    </script>

</body>

</html>
