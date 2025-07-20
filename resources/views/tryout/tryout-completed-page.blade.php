@extends('layouts.tryout-layout')

@section('content')
    <div class="text-center">
        {{-- Header --}}
        <h1 class="text-4xl font-bold text-gray-800">Tryout Selesai!</h1>
        <p class="mt-2 text-lg text-gray-500">Selamat! Anda telah menyelesaikan Tryout Anda.</p>

        {{-- Konten Hasil --}}
        <div class="mx-auto mt-10 flex max-w-4xl flex-col justify-center gap-8 md:flex-row">

            {{-- Kartu Skor --}}
            <div class="w-full rounded-xl border border-gray-200 bg-white p-8 shadow-sm md:w-1/2">
                <h2 class="text-xl font-semibold text-black">Hasil Anda</h2>
                <p class="my-6 text-7xl font-bold text-[#4DC96A]">89%</p>
                <p class="text-gray-500">Skor Keseluruhan</p>
            </div>

            {{-- Kartu Analisis Performa --}}
            <div class="w-full rounded-xl border border-gray-200 bg-white p-8 text-left shadow-sm md:w-1/2">
                <h2 class="text-xl font-semibold text-black">Analisis Performa</h2>
                <div class="mt-6 space-y-4">
                    <div>
                        <h3 class="font-semibold text-[#6CA7FF]">Kekuatan:</h3>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-black">
                            <li>Berpikir Analitis</li>
                            <li>Pemecahan Masalah</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[#F4854A]">Area yang Perlu Ditingkatkan:</h3>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-black">
                            <li>Manajemen Waktu</li>
                            <li>Penulisan Teknis</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-12">
            <a href="/"
                class="rounded-lg bg-blue-600 px-8 py-3 font-semibold text-white transition-colors hover:bg-blue-700">
                Kembali ke beranda
            </a>
        </div>
    </div>
@endsection
