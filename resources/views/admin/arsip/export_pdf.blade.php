<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Arsip Peminjaman & Pengembalian</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>No HP</th>
                <th>Unit/Jurusan</th>
                <th>Nama Kegiatan</th>
                <th>Tujuan</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
                <th>Barang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $p)
            <tr>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ $p->nama_kegiatan }}</td>
                <td>{{ $p->tujuan }}</td>
                <td>{{ $p->tanggal_mulai }} s/d {{ $p->tanggal_selesai }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>
                    @foreach($p->details as $detail)
                        {{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 