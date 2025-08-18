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
    const tryoutId = urlParams.get('tryout_id') || 1;

    return {
        // Configuration
        mode: pageMode,
        tryoutId: tryoutId,
        sessionId: null,
        
        // Question data - Initialize dengan data kosong yang aman
        questions: [],
        options: {},
        currentIndex: 0,
        
        // User state
        answers: {},
        timeLeft: 3600,
        isModalOpen: false,
        isLoading: true, // Mulai dengan loading true
        isSubmitting: false,
        
        // Auto-save settings
        autoSaveInterval: null,
        lastSaveTime: null,
        hasUnsavedChanges: false,
        
        // Error state
        error: null,
        isInitialized: false, // Track initialization status

        // Computed properties dengan safety checks
        get currentQuestion() {
            if (!this.isInitialized || !this.questions.length || this.currentIndex < 0) {
                return null; // Return null instead of undefined
            }
            return this.questions[this.currentIndex] || null;
        },
        
        get totalQuestions() {
            return this.questions.length || 0;
        },

        get currentQuestionId() {
            return this.currentQuestion?.id || null;
        },

        // Safe check untuk apakah data sudah ready
        get isDataReady() {
            return this.isInitialized && !this.isLoading && this.questions.length > 0;
        },

        // Initialization
        async init() {
            try {
                console.log('Initializing tryout...');
                this.isLoading = true;
                this.error = null;
                
                // Wait untuk DOM ready jika diperlukan
                await this.waitForDOM();
                
                await this.startTryoutSession();
                this.setupAutoSave();
                this.setupBeforeUnload();
                this.startTimer();
                
                this.isInitialized = true;
                console.log('Tryout initialized successfully');
                
            } catch (error) {
                console.error('Failed to initialize tryout:', error);
                this.error = error.message || 'Failed to load tryout. Please refresh the page.';
                this.showError(this.error);
            } finally {
                this.isLoading = false;
            }
        },

        // Wait untuk DOM elements yang diperlukan
        async waitForDOM() {
            return new Promise((resolve) => {
                const checkCSRF = () => {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        resolve();
                    } else {
                        setTimeout(checkCSRF, 100);
                    }
                };
                checkCSRF();
            });
        },

        // API Integration dengan better error handling
        async startTryoutSession() {
            try {
                // Safe CSRF token retrieval
                const csrfElement = document.querySelector('meta[name="csrf-token"]');
                if (!csrfElement) {
                    throw new Error('CSRF token not found. Please refresh the page.');
                }
                
                const csrfToken = csrfElement.getAttribute('content');
                if (!csrfToken) {
                    throw new Error('Invalid CSRF token. Please refresh the page.');
                }

                console.log('Starting tryout session...', { tryoutId: this.tryoutId, mode: this.mode });

                const response = await fetch('/tryout/api/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        tryout_id: this.tryoutId,
                        mode: this.mode
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
                }

                const data = await response.json();
                console.log('API Response:', data);

                if (!data.success) {
                    throw new Error(data.message || 'Failed to start tryout');
                }

                // Validate response data
                if (!data.data || !data.data.questions || !Array.isArray(data.data.questions)) {
                    throw new Error('Invalid response: questions data missing');
                }

                if (data.data.questions.length === 0) {
                    throw new Error('No questions available for this tryout');
                }

                // Initialize session data
                this.sessionId = data.data.session_id;
                this.questions = data.data.questions;
                this.timeLeft = (data.data.remaining_time || 60) * 60; // Convert to seconds with fallback
                
                // Setup options mapping
                this.setupOptionsMapping();

                // Load existing answers if resuming session
                await this.loadExistingAnswers();

                console.log('Session started successfully:', {
                    sessionId: this.sessionId,
                    questionsCount: this.questions.length,
                    timeLeft: this.timeLeft
                });

            } catch (error) {
                console.error('Start session error:', error);
                throw new Error(`Failed to start tryout: ${error.message}`);
            }
        },

        async loadExistingAnswers() {
            if (!this.sessionId) return;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch(`/tryout/api/session/${this.sessionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success && data.data.answers) {
                        this.answers = data.data.answers;
                        console.log('Loaded existing answers:', Object.keys(this.answers).length);
                    }
                }
            } catch (error) {
                console.warn('Failed to load existing answers:', error);
            }
        },

        setupOptionsMapping() {
            this.options = {}; // Reset options
            
            this.questions.forEach(question => {
                if (!question.choices || !Array.isArray(question.choices)) {
                    console.warn('Question missing choices:', question.id);
                    return;
                }

                this.options[question.id] = {};
                question.choices.forEach((choice, index) => {
                    const optionKey = String.fromCharCode(65 + index); // A, B, C, D, E
                    this.options[question.id][optionKey] = {
                        id: choice.id,
                        text: choice.text
                    };
                });
            });
            
            console.log('Options mapping setup complete');
        },

        // Answer Management
        async selectAnswer(optionKey) {
            if (!this.isDataReady) return;
            
            const questionId = this.currentQuestionId;
            const choiceId = this.options[questionId]?.[optionKey]?.id;

            if (!choiceId) {
                console.warn('Invalid option selected:', { questionId, optionKey });
                return;
            }

            // Update local state
            this.answers[questionId] = choiceId;
            this.hasUnsavedChanges = true;

            console.log('Answer selected:', { questionId, optionKey, choiceId });

            // Auto-save in learning mode or if configured
            if (this.mode === 'belajar') {
                await this.saveAnswerToServer(questionId, choiceId);
            }
        },

        async saveAnswerToServer(questionId, choiceId) {
            if (!this.sessionId) return;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch('/tryout/api/answer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        question_id: questionId,
                        choice_id: choiceId
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.success) {
                        this.hasUnsavedChanges = false;
                        this.lastSaveTime = Date.now();
                    }
                }
            } catch (error) {
                console.warn('Failed to auto-save answer:', error);
            }
        },

        // Auto-save functionality
        setupAutoSave() {
            // Auto-save every 30 seconds if there are unsaved changes
            this.autoSaveInterval = setInterval(async () => {
                if (this.hasUnsavedChanges && this.sessionId) {
                    await this.saveAllAnswers();
                }
            }, 30000);
        },

        async saveAllAnswers() {
            if (!this.hasUnsavedChanges || !this.sessionId) return;

            try {
                const savePromises = Object.entries(this.answers).map(([questionId, choiceId]) => 
                    this.saveAnswerToServer(parseInt(questionId), choiceId)
                );

                await Promise.all(savePromises);
                this.hasUnsavedChanges = false;
            } catch (error) {
                console.warn('Failed to save all answers:', error);
            }
        },

        // Navigation
        changeQuestion(index) {
            if (!this.isDataReady) return;
            
            if (index >= 0 && index < this.totalQuestions) {
                this.currentIndex = index;
            }
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

        // Question status
        getQuestionStatus(questionIndex) {
            if (!this.isDataReady || !this.questions[questionIndex]) {
                return 'unanswered';
            }

            const question = this.questions[questionIndex];
            
            if (this.currentIndex === questionIndex) return 'active';
            if (this.answers.hasOwnProperty(question.id)) return 'answered';
            return 'unanswered';
        },

        getSelectedOption(questionId) {
            const choiceId = this.answers[questionId];
            if (!choiceId) return null;

            // Find the option key for this choice
            const questionOptions = this.options[questionId] || {};
            for (const [key, option] of Object.entries(questionOptions)) {
                if (option.id === choiceId) {
                    return key;
                }
            }
            return null;
        },

        // Timer Management
        startTimer() {
            setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    // Auto-submit when time runs out
                    this.autoSubmitTimeUp();
                }
            }, 1000);
        },

        formatTime() {
            const hours = Math.floor(this.timeLeft / 3600).toString().padStart(2, '0');
            const minutes = Math.floor((this.timeLeft % 3600) / 60).toString().padStart(2, '0');
            const seconds = (this.timeLeft % 60).toString().padStart(2, '0');
            return `${hours}:${minutes}:${seconds}`;
        },

        async autoSubmitTimeUp() {
            if (this.isSubmitting) return;

            try {
                await this.saveAllAnswers();
                await this.submitExam();
                this.showMessage('Time is up! Your exam has been automatically submitted.');
            } catch (error) {
                console.error('Auto-submit failed:', error);
                this.showError('Time is up, but there was an error submitting your exam. Please contact support.');
            }
        },

        // Exam Submission
        async submitExam() {
            if (this.isSubmitting) return;

            this.isSubmitting = true;
            this.isModalOpen = false;

            try {
                await this.saveAllAnswers();

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                const response = await fetch('/tryout/api/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_id: this.sessionId
                    })
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Failed to submit exam');
                }

                this.cleanup();

                if (data.data.redirect_url) {
                    window.location.href = data.data.redirect_url;
                } else {
                    window.location.href = `/tryout/hasil/${this.sessionId}`;
                }

            } catch (error) {
                console.error('Submit failed:', error);
                this.showError('Failed to submit exam: ' + error.message);
                this.isSubmitting = false;
            }
        },

        // Utility Functions
        setupBeforeUnload() {
            window.addEventListener('beforeunload', (e) => {
                if (this.hasUnsavedChanges) {
                    const message = 'You have unsaved changes. Are you sure you want to leave?';
                    e.returnValue = message;
                    return message;
                }
            });
        },

        cleanup() {
            if (this.autoSaveInterval) {
                clearInterval(this.autoSaveInterval);
            }
        },

        showError(message) {
            console.error('Error:', message);
            alert('Error: ' + message);
        },

        showMessage(message) {
            console.log('Message:', message);
            alert(message);
        },

        // Statistics
        getAnsweredCount() {
            return Object.keys(this.answers).length;
        },

        getUnansweredCount() {
            return this.totalQuestions - this.getAnsweredCount();
        },

        getProgressPercentage() {
            if (this.totalQuestions === 0) return 0;
            return Math.round((this.getAnsweredCount() / this.totalQuestions) * 100);
        }
    }
}
    </script>
@endsection
