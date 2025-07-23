<div class="rounded-lg border border-gray-200 p-4">
    <h3 class="text-sm font-semibold text-gray-900">Topik Populer</h3>
    <ul class="mt-2 space-y-1">
        @foreach(['Penalaran Logis', 'Etika Pelayanan Publik', 'Sinonim & Antonim', 'Strategi Silogisme', 'Soal Figural & Gambar'] as $topic)
            <li>
                <a href="#" class="block rounded-md px-2 py-1.5 text-sm text-gray-600 hover:bg-gray-100">{{ $topic }}</a>
            </li>
        @endforeach
    </ul>
</div>
