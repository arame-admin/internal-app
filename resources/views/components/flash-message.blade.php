@if(session('success') || session('error'))
    <div id="custom-alert" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-8 relative transform animate-bounce-in">
            <div class="text-center">
                @if(session('success'))
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center animate-pulse">
                            <svg class="w-8 h-8 text-white animate-checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 animate-slide-in mb-2">Success!</h3>
                    <p class="text-gray-600 text-lg animate-slide-in-delay">{{ session('success') }}</p>
                @elseif(session('error'))
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-full flex items-center justify-center animate-shake">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 animate-slide-in mb-2">Error!</h3>
                    <p class="text-gray-600 text-lg animate-slide-in-delay">{{ session('error') }}</p>
                @endif
            </div>
            <button onclick="closeAlert()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors animate-fade-in">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="mt-6 bg-gray-200 rounded-full h-1">
                <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-indigo-600 h-1 rounded-full animate-progress"></div>
            </div>
        </div>
    </div>

    <script>
        function closeAlert() {
            const alert = document.getElementById('custom-alert');
            alert.classList.add('animate-fade-out');
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }
        setTimeout(closeAlert, 3000);
    </script>
@endif

<style>
    .animate-bounce-in {
        animation: bounceIn 0.6s ease-out;
    }

    .animate-checkmark {
        animation: checkmark 0.8s ease-in-out 0.2s both;
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    .animate-slide-in {
        animation: slideIn 0.5s ease-out 0.1s both;
    }

    .animate-slide-in-delay {
        animation: slideIn 0.5s ease-out 0.3s both;
    }

    .animate-progress {
        animation: progress 3s linear;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-in-out;
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes checkmark {
        0% {
            transform: scale(0) rotate(45deg);
            opacity: 0;
        }
        50% {
            transform: scale(1.2) rotate(45deg);
            opacity: 1;
        }
        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        10%, 30%, 50%, 70%, 90% {
            transform: translateX(-5px);
        }
        20%, 40%, 60%, 80% {
            transform: translateX(5px);
        }
    }

    @keyframes slideIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes progress {
        0% {
            width: 100%;
        }
        100% {
            width: 0%;
        }
    }

    @keyframes fadeOut {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
</style>
