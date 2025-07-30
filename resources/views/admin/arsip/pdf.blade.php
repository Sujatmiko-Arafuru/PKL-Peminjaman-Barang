<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Peminjaman SarPras</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #1565c0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1565c0;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 10px 0;
        }
        
        .header .info {
            font-size: 11px;
            color: #888;
        }
        
        .filter-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .filter-info h3 {
            color: #1565c0;
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .filter-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        .summary-section {
            margin-bottom: 25px;
        }
        
        .summary-section h3 {
            color: #1565c0;
            font-size: 14px;
            margin: 0 0 10px 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .summary-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .summary-item {
            flex: 1;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
        }
        
        .summary-item h4 {
            color: #1565c0;
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        
        .summary-item p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        
        th {
            background: #1565c0;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1565c0;
        }
        
        td {
            padding: 6px 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        
        .status-dikembalikan { background: #28a745; }
        .status-disetujui { background: #007bff; }
        .status-pengembalian_diajukan { background: #ffc107; color: #333; }
        .status-ditolak { background: #dc3545; }
        .status-pengembalian-ditolak { background: #dc3545; }
        .status-menunggu { background: #6c757d; }
        
        .barang-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .barang-list li {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 3px;
            padding: 2px 6px;
            margin: 1px 0;
            font-size: 9px;
            display: inline-block;
            margin-right: 3px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .total-info {
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }
        
        .total-info h4 {
            color: #2e7d32;
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        
        .total-info p {
            margin: 3px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>ARSIP PEMINJAMAN SARANA DAN PRASARANA</h1>
        <h2>Sistem Peminjaman Sarana dan Prasarana (SarPras)</h2>
        <div class="info">
            <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
            <p>Total Data: {{ $peminjamans->count() }} peminjaman</p>
        </div>
    </div>

    <!-- Filter Information -->
    @if(!empty($filterInfo))
    <div class="filter-info">
        <h3>📋 Informasi Filter</h3>
        @if(isset($filterInfo['search']))
            <p><strong>Pencarian:</strong> {{ $filterInfo['search'] }}</p>
        @endif
        @if(isset($filterInfo['status']))
            <p><strong>Status:</strong> {{ ucfirst($filterInfo['status']) }}</p>
        @endif
        @if(isset($filterInfo['tanggal_mulai']))
            <p><strong>Tanggal Mulai:</strong> {{ date('d/m/Y', strtotime($filterInfo['tanggal_mulai'])) }}</p>
        @endif
        @if(isset($filterInfo['tanggal_selesai']))
            <p><strong>Tanggal Selesai:</strong> {{ date('d/m/Y', strtotime($filterInfo['tanggal_selesai'])) }}</p>
        @endif
    </div>
    @endif

    <!-- Summary Section -->
    <div class="summary-section">
        <h3>📊 Ringkasan Data</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <h4>🏆 Barang Terlaris</h4>
                <p>
                    @if($terlaris)
                        {{ $terlaris->nama }} ({{ $terlaris->details_count ?? 0 }}x dipinjam)
                    @else
                        Tidak ada data
                    @endif
                </p>
            </div>
            <div class="summary-item">
                <h4>📦 Barang Tidak Pernah Dipinjam</h4>
                <p>
                    @if($tidakPernah && count($tidakPernah) > 0)
                        {{ count($tidakPernah) }} item
                    @else
                        Tidak ada
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Total Information -->
    <div class="total-info">
        <h4>📈 Statistik Status Peminjaman</h4>
        @php
            $statusCounts = $peminjamans->groupBy('status')->map->count();
        @endphp
        <p><strong>Total Peminjaman:</strong> {{ $peminjamans->count() }}</p>
        <p><strong>Dikembalikan:</strong> {{ $statusCounts['dikembalikan'] ?? 0 }}</p>
        <p><strong>Disetujui:</strong> {{ $statusCounts['disetujui'] ?? 0 }}</p>
        <p><strong>Menunggu:</strong> {{ $statusCounts['menunggu'] ?? 0 }}</p>
        <p><strong>Ditolak:</strong> {{ $statusCounts['ditolak'] ?? 0 }}</p>
        <p><strong>Pengembalian Diajukan:</strong> {{ $statusCounts['pengembalian_diajukan'] ?? 0 }}</p>
    </div>

    <!-- Data Table -->
    @if($peminjamans->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama Peminjam</th>
                <th style="width: 10%;">No HP</th>
                <th style="width: 12%;">Unit/Jurusan</th>
                <th style="width: 15%;">Nama Kegiatan</th>
                <th style="width: 12%;">Tujuan</th>
                <th style="width: 12%;">Tanggal Pinjam</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 11%;">Barang Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $index => $p)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $p->nama }}</strong><br>
                    <small>Kode: {{ $p->kode_peminjaman }}</small>
                </td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ $p->unit }}</td>
                <td>{{ Str::limit($p->nama_kegiatan, 30) }}</td>
                <td>{{ Str::limit($p->tujuan, 25) }}</td>
                <td>
                    <strong>Mulai:</strong> {{ date('d/m/Y', strtotime($p->tanggal_mulai)) }}<br>
                    <strong>Selesai:</strong> {{ date('d/m/Y', strtotime($p->tanggal_selesai)) }}
                </td>
                <td style="text-align: center;">
                    <span class="status-badge status-{{ str_replace(' ', '-', $p->status) }}">
                        @if($p->status == 'pengembalian_diajukan')
                            Pengembalian Diajukan
                        @elseif($p->status == 'pengembalian ditolak')
                            Pengembalian Ditolak
                        @else
                            {{ ucfirst($p->status) }}
                        @endif
                    </span>
                </td>
                <td>
                    <ul class="barang-list">
                        @foreach($p->details as $detail)
                        <li>{{ $detail->barang->nama ?? '-' }} ({{ $detail->jumlah }})</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h3>📭 Tidak Ada Data</h3>
        <p>Tidak ada data peminjaman yang sesuai dengan filter yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Dokumen ini dibuat secara otomatis oleh sistem SarPras</strong></p>
        <p>© {{ date('Y') }} Sistem Peminjaman Sarana dan Prasarana. All rights reserved.</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html> 