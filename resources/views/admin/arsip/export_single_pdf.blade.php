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
    <h2>Detail Arsip Peminjaman</h2>
    <table>
        <tr><th>Nama</th><td>{{ $peminjaman->nama }}</td></tr>
        <tr><th>No HP</th><td>{{ $peminjaman->no_telp }}</td></tr>
        <tr><th>Unit/Jurusan</th><td>{{ $peminjaman->unit }}</td></tr>
        <tr><th>Nama Kegiatan</th><td>{{ $peminjaman->nama_kegiatan }}</td></tr>
        <tr><th>Tujuan</th><td>{{ $peminjaman->tujuan }}</td></tr>
        <tr><th>Tgl Pinjam</th><td>{{ $peminjaman->tanggal_mulai }} s/d {{ $peminjaman->tanggal_selesai }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($peminjaman->status) }}</td></tr>
        <tr><th>Barang</th>
            <td>
                <ul>
                @foreach($peminjaman->details as $detail)
                    <li>{{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})</li>
                @endforeach
                </ul>
            </td>
        </tr>
    </table>
</body>
</html> 