<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_kas_' . $tanggal . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{  website()->nama_website }} - Laporan Kas</title>
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
        <h3 class="text-center">Laporan Kas</h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif

        @if (!empty($jenis))
            <b>Jenis Kas :</b> {{ $jenis }}<br>
        @endif

        <table id="datatable" class="table-striped" width="100%" border="1px" cellpadding="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                    <th>Saldo</th>
                    <th>Sumber</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $debit = 0;
                $kredit = 0;
                $nominal = 0;
                ?>
                @foreach ($kas as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td align="right">Rp.{{ number_format($p->debit, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($p->kredit, 0, ',', '.') }}</td>
                        <td align="right">Rp.{{ number_format($p->nominal, 0, ',', '.') }}</td>
                        <td>{{ $p->sumber }}</td>
                        <td>{{ $p->deskripsi }}</td>
                        <td>{{ date('d-m-Y', strtotime($p->created_at)) }}</td>
                    </tr>
                    <?php
                    $debit += $p->debit;
                    $kredit += $p->kredit;
                    ?>
                @endforeach
                <?php
                $nominal = $debit - $kredit;
                ?>
                <tr style="background-color:white">
                    <td align="left"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($debit, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($kredit, 0, ',', '.') }}</b></td>
                    <td align="right"><b>Rp.{{ number_format($nominal, 0, ',', '.') }}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

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
