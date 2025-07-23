@extends('layouts.bank-soal-layout')

@section('content')
    <div class="container mx-auto p-4 md:p-8">
        <div x-data="{ activeTab: 'home' }" class="flex flex-col md:flex-row md:space-x-8">

            <aside class="mb-6 w-full md:mb-0 md:w-1/4">
                <div class="rounded-xl border border-gray-300 bg-white p-4 shadow-2xl">

                    <h2 class="mb-2 px-3 pb-4 text-xl font-extrabold text-gray-800">
                        KELOMPOK <span class="text-main-bg">SOAL</span>
                    </h2>

                    <a href="#" @click.prevent="activeTab = 'tpa'"
                        :class="{ 'bg-main-blue-button/50 border-l-4 border-main-blue-button  text-white': activeTab === 'tpa', 'text-gray-800 hover:bg-gray-100': activeTab !== 'tpa' }"
                        class="flex w-full items-center border-b border-b-gray-200 p-3 text-left transition-colors duration-200">

                        <div class="mr-4 flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full"
                            :class="{ 'bg-main-blue-button': activeTab === 'tpa', 'bg-gray-200': activeTab !== 'tpa' }">
                            <x-bank-soal.book-icon />
                        </div>
                        <span class="text-base font-semibold">Bank Soal TPA</span>
                    </a>

                    <a href="#" @click.prevent="activeTab = 'tkd'"
                        :class="{ 'bg-main-blue-button/50 border-l-4 border-main-blue-button text-white': activeTab === 'tkd', 'text-gray-800 hover:bg-gray-100': activeTab !== 'tkd' }"
                        class="flex w-full items-center border-b border-b-gray-200 p-3 text-left transition-colors duration-200">
                        <div class="mr-4 flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full"
                            :class="{ 'bg-main-blue-button': activeTab === 'tkd', 'bg-gray-200': activeTab !== 'tkd' }">
                            <x-bank-soal.book-icon />
                        </div>
                        <span class="text-base font-semibold">Bank Soal TKD</span>
                    </a>

                    <a href="#" @click.prevent="activeTab = 'tiu'"
                        :class="{ 'bg-main-blue-button/50 border-l-4 border-main-blue-button text-white': activeTab === 'tiu', 'text-gray-800 hover:bg-gray-100': activeTab !== 'tiu' }"
                        class="flex w-full items-center border-b border-b-gray-200 p-3 text-left transition-colors duration-200">
                        <div class="mr-4 flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full"
                            :class="{ 'bg-main-blue-button': activeTab === 'tiu', 'bg-gray-200': activeTab !== 'tiu' }">
                            <x-bank-soal.book-icon />
                        </div>
                        <span class="text-base font-semibold">Bank Soal TIU</span>
                    </a>

                </div>
            </aside>

            <div class="w-full md:w-3/4 md:pl-5">
                <div x-show="activeTab === 'home'" x-transition.opacity>
                    @include('bank-soal.landing-content')
                </div>

                <div x-show="activeTab === 'tpa'" x-transition.opacity style="display: none;">
                    <x-bank-soal.bs-content title="Bank Soal Tes Potensi Akademik"
                        description="PPPKin menyediakan bank soal Tes Potensi Akademik (TPA) yang dirancang untuk mengasah kemampuan berpikir logis, analitis, dan verbal. Soal-soal disusun secara sistematis mencakup berbagai tipe: analogi, silogisme, aritmetika dasar, hingga deret logika. Dengan latihan yang terus-menerus, kamu akan terbiasa menghadapi berbagai variasi soal dan meningkatkan kepercayaan diri dalam menjawab soal-soal TPA.">

                        <x-slot:icon>
                            <div class="mr-4 p-3">
                                <img src="{{ asset('images/tpa-logo.png') }}" alt="book logo" class="h-14 w-14" >
                            </div>
                        </x-slot:icon>

                        <x-bank-soal.bs-question-card judul="PPPK 2022 Aljabar" jumlahSoal="100"
                            tryout-url="{{ route('tryout-detail') }}" />
                        <x-bank-soal.bs-question-card judul="PPPK 2022 Silogisme" jumlahSoal="80" />
                        <x-bank-soal.bs-question-card judul="PPPK 2022 Analogi Verbal" jumlahSoal="120" />
                        <x-bank-soal.bs-question-card judul="PPPK 2022 Deret Angka" jumlahSoal="100" />
                        <x-bank-soal.bs-question-card judul="PPPK 2022 Deret Angka" jumlahSoal="100" />
                        <x-bank-soal.bs-question-card judul="PPPK 2022 Deret Angka" jumlahSoal="100" />
                    </x-bank-soal.bs-content>
                </div>

                <div x-show="activeTab === 'tiu'" x-transition.opacity style="display: none;">
                    <x-bank-soal.bs-content title="Bank Soal Tes Intelegensi Umum"
                        description="Bank soal TIU di PPPKin dirancang untuk menguji dan mengembangkan kemampuan intelektual secara menyeluruh, mencakup aspek verbal, numerik, dan figural. Setiap soal disusun agar pengguna mampu berpikir kritis, cepat, dan tepat dalam menyelesaikan berbagai jenis permasalahan logika. Latihan secara rutin melalui soal-soal ini akan membantu kamu menjadi lebih siap dan tanggap dalam menghadapi tantangan seleksi PPPK.">

                        <x-slot:icon>
                            <div class="mr-4 p-3">
                                <img src="{{ asset('images/tiu-logo.png') }}" alt="book logo" class="h-14 w-14" >
                            </div>
                        </x-slot:icon>

                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Numerik" jumlahSoal="150" />
                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Figural" jumlahSoal="110" />
                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TIU - Kemampuan Verbal" jumlahSoal="95" />
                    </x-bank-soal.bs-content>
                </div>

                <div x-show="activeTab === 'tkd'" x-transition.opacity style="display: none;">
                    <x-bank-soal.bs-content title="Bank Soal Tes Kompetensi Dasar"
                        description="Melalui bank soal TKD dari PPPKin, kamu dapat memperdalam pemahaman terhadap nilai-nilai dasar ASN seperti integritas, etika pelayanan publik, profesionalisme, serta karakteristik pribadi. Soal-soal disusun mengikuti standar kompetensi yang diujikan, membantu kamu berlatih mengenali situasi, mengambil keputusan, dan menilai sikap yang sesuai dengan peran sebagai ASN profesional.">

                        <x-slot:icon>
                            <div class="mr-4 p-3">
                                <img src="{{ asset('images/tkd-logo.png') }}" alt="book logo" class="h-14 w-14" >
                            </div>
                        </x-slot:icon>

                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Numerik" jumlahSoal="150" />
                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Figural" jumlahSoal="110" />
                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Verbal" jumlahSoal="95" />
                        <x-bank-soal.bs-question-card judul="TKD - Kemampuan Verbal" jumlahSoal="95" />
                    </x-bank-soal.bs-content>
                </div>
            </div>
        </div>
    </div>
@endsection
