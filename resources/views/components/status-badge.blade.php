@props(['status', 'stokTersedia' => null])

@php
    $statusClass = '';
    $statusText = '';
    
    if ($status === 'tersedia') {
        $statusClass = 'bg-green-100 text-green-800';
        $statusText = 'Tersedia';
    } elseif ($status === 'tidak tersedia') {
        $statusClass = 'bg-red-100 text-red-800';
        $statusText = 'Tidak Tersedia';
    } else {
        $statusClass = 'bg-gray-100 text-gray-800';
        $statusText = ucfirst($status);
    }
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
    {{ $statusText }}
    @if($stokTersedia !== null)
        <span class="ml-1 text-xs opacity-75">({{ $stokTersedia }})</span>
    @endif
</span> 