@if(session('success'))
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg border border-green-600 flex items-center space-x-3 max-w-md">
            <svg class="w-6 h-6 text-green-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('success') }}</p>
            <button class="ml-auto text-green-100 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in">
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg border border-red-600 flex items-center space-x-3 max-w-md">
            <svg class="w-6 h-6 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <p class="font-medium">{{ session('error') }}</p>
            <button class="ml-auto text-red-100 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in">
        <div class="bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-lg border border-yellow-600 flex items-center space-x-3 max-w-md">
            <svg class="w-6 h-6 text-yellow-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <p class="font-medium">{{ session('warning') }}</p>
            <button class="ml-auto text-yellow-100 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="fixed top-20 left-1/2 transform -translate-x-1/2 z-50 animate-fade-in">
        <div class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg border border-blue-600 flex items-center space-x-3 max-w-md">
            <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="font-medium">{{ session('info') }}</p>
            <button class="ml-auto text-blue-100 hover:text-white" onclick="this.parentElement.style.display='none'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
@endif

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
</style>