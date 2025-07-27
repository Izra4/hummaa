@extends('layouts.tryout-layout')

@section('content')
    <div x-data="ujianState()">
        <div class="flex flex-col lg:flex-row gap-6 lg:items-start">
            <div class="w-full lg:w-1/3">
                <x-tryout.grid-question-number 
                :title="'Tes Potensi Akademik'"
                :imageUrl="asset('images/tpa-logo.png')" />
                    
            </div>

            <div class="w-full lg:w-2/3">
                <x-tryout.question-card />
            </div>

        </div>
        <x-tryout.confirm-submit-modal />
    </div>

    <script>
        function ujianState() {
            const urlParams = new URLSearchParams(window.location.search);
            const pageMode = urlParams.get('mode') || 'tryout';
            return {
                mode: pageMode,

                questions: [{
                        id: 1,
                        text: 'Pertanyaan pertama tentang logika verbal.',
                        type: 'pilihan_ganda',
                        image: null,
                        correctAnswer: 'B',
                        explanation: 'Dalam menghadapi tugas baru, inisiatif untuk belajar dan mencari informasi adalah sikap proaktif yang paling dihargai, menunjukkan kemauan untuk berkembang.'
                    },
                    {
                        id: 2,
                        text: 'Kenapa dinamakan Beling ?',
                        type: 'isian',
                        image: null
                    },
                    {
                        id: 3,
                        text: 'Ketika Anda diberikan tugas baru yang belum pernah Anda kerjakan sebelumnya, apa yang biasanya Anda lakukan?',
                        type: 'pilihan_ganda',
                        image: '{{ asset("images/contoh-soal-gambar.jpg") }}',
                        correctAnswer: 'B',
                        explanation: 'Dalam menghadapi tugas baru, inisiatif untuk belajar dan mencari informasi adalah sikap proaktif yang paling dihargai, menunjukkan kemauan untuk berkembang.'
                    },
                    // ... tambahkan 47 soal lainnya
                ],
                options: {
                    'A': 'Menunda mengerjakan hingga ada orang lain yang memulai terlebih dahulu',
                    'B': 'Mencoba memahami tugas tersebut dan mencari informasi sebanyak Mungkin',
                    'C': 'Langsung menolak karena merasa tidak mampu',
                    'D': 'Menunda mengerjakan hingga ada orang lain yang memulai terlebih dahulu',
                    'E': 'Menghindari tanggung jawab dan berpura-pura tidak tahu'
                },

                // STATE

                currentIndex: 2,
                answers: {
                    1: 'B',
                    2: 'Dina anggota PSHT',
                    3: 'E'
                }, // Jawaban yg sudah tersimpan
                timeLeft: 3600, // Sisa waktu dalam detik
                isModalOpen: false,

                // COMPUTED PROPERTIES (Getters)
                get currentQuestion() {
                    return this.questions[this.currentIndex];
                },
                get totalQuestions() {
                    return this.questions.length; // Ganti 50 dengan total soal asli
                },


                // METHODS
                init() {
                    // Inisialisasi timer
                    setInterval(() => {
                        if (this.timeLeft > 0) this.timeLeft--;
                    }, 1000);
                },
                selectAnswer(optionKey) {
                    this.answers[this.currentQuestion.id] = optionKey;
                    // Di sini Anda bisa menambahkan logic AJAX untuk auto-save ke server
                    console.log(`Soal ${this.currentQuestion.id} dijawab: ${optionKey}`);
                },
                changeQuestion(index) {
                    this.currentIndex = index;
                },
                nextQuestion() {
                    if (this.currentIndex < this.totalQuestions - 1) {
                        this.currentIndex++;
                    }
                },
                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },
                getQuestionStatus(questionId) {
                    if (this.currentQuestion.id === questionId) return 'active';
                    if (this.answers.hasOwnProperty(questionId)) return 'answered';
                    return 'unanswered';
                },
                formatTime() {
                    const hours = Math.floor(this.timeLeft / 3600).toString().padStart(2, '0');
                    const minutes = Math.floor((this.timeLeft % 3600) / 60).toString().padStart(2, '0');
                    const seconds = (this.timeLeft % 60).toString().padStart(2, '0');
                    return `${hours}:${minutes}:${seconds}`;
                },

                toggleMode() {
                    this.mode = (this.mode === 'tryout') ? 'belajar' : 'tryout';
                    console.log('Mode diubah menjadi:', this.mode)
                },

                submitExam() {
                    this.isModalOpen = false; // Tutup modal setelah diklik
                    console.log('Proses submit dimulai, mengarahkan ke halaman hasil...');
                    
            // // Tambahkan loading state jika perlu
            // // this.isSubmitting = true;

            // fetch('/ujian/submit', { // Ganti dengan URL endpoint Anda
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Penting untuk keamanan Laravel
            //     },
            //     body: JSON.stringify({
            //         answers: this.answers
            //     })
            // })
            // .then(response => response.json())
            // .then(data => {
            //     console.log('Sukses:', data);
            //     // Arahkan ke halaman hasil
            //     window.location.href = '/ujian/hasil'; // Ganti dengan URL halaman hasil
            // })
            // .catch(error => {
            //     console.error('Error:', error);
            //     alert('Terjadi kesalahan saat mengirim jawaban.');
            //     // this.isSubmitting = false;
            window.location.href = '/tryout/hasil';
            },
            }
        }
    </script>
@endsection
