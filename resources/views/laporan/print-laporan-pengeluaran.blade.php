<?php
if (!empty($export)) {
    header('Content-type: application/vnd-ms-excel');
    header('Content-Disposition: attachment; filename=laporan_pengeluaran_' . $tanggal . '_status_' . $persetujuan . '.xls');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{  website()->nama_website }} - Laporan Pengeluaran</title>
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
        <h3 class="text-center">Laporan Pengeluaran</h3>
        @if (!empty($tanggal))
            <b>Range Tanggal :</b> {{ $tanggal }}<br>
        @endif

        @if (!empty($jenis))
            <b>Jenis Kas : </b> {{ $jenis }}<br>
        @endif

        @if (!empty($persetujuan))
            <b>Status Pengeluaran : </b> {{ $persetujuan }}<br>
        @endif

        <table id="datatable" width="100%" class="table-striped" border="1px" cellpadding="2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Pengeluaran</th>
                    <th>Biaya Dikeluarkan</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $biaya = 0;
                ?>
                @foreach ($pengeluaran as $index => $p)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($p->tgl)) }}</td>
                        <td>{{ $p->judul }}</td>
                        <td align="right">Rp.{{ number_format($p->biaya, 0, ',', '.') }}</td>
                        <td>{{ $p->deskripsi }}</td>
                        <td>
                            @if ($p->persetujuan == 1)
                                Belum Verifikasi
                            @elseif($p->persetujuan == 2)
                                Disetujui
                            @elseif($p->persetujuan == 3)
                                Ditolak
                            @endif
                        </td>
                    </tr>
                    <?php
                    $biaya += $p->biaya;
                    ?>
                @endforeach
                <tr style="background-color:white">
                    <td colspan="5" align="left"><b>Total</b></td>
                    <td align="right"><b>Rp.{{ number_format($biaya, 0, ',', '.') }}</b></td>
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
