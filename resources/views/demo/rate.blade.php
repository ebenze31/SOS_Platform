@extends('layouts.theme')

@section('content')

<style>
    .map-pattern {
        background-color: #f1f5f9;
        background-image:
            linear-gradient(rgba(203, 213, 225, 0.4) 1px, transparent 1px),
            linear-gradient(90deg, rgba(203, 213, 225, 0.4) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .dark .map-pattern {
        background-color: #1e293b;
        background-image:
            linear-gradient(rgba(51, 65, 85, 0.4) 1px, transparent 1px),
            linear-gradient(90deg, rgba(51, 65, 85, 0.4) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    body,
    html {
        width: 100% !important;
        height: 100% !important;
    }

    .rating-selected {
        transform: scale(1.15);
    }

    .rating-selected > div {
        box-shadow: 0 0 0 3px currentColor;
    }

    @keyframes pop {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-pop {
        animation: pop 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>
<div class="w-full md:h-[calc(100% - 71px)] relative flex justify-center sm:items-center p-3 bg-slate-50 dark:bg-slate-900 " style="height: calc(100% - 71.75px);margin-top:71.75px;overflow: auto;">

    <div class="relative w-full  max-w-[500px] bg-white dark:bg-[#1a2632] rounded-xl shadow-xl flex flex-col overflow-auto ring-1 ring-black/5 dark:ring-white/10">
    <header class="px-8 py-6 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between bg-white dark:bg-[#1a2632]">
        <div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Rate Your Experience</h1>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide">ID: #REQ-2023-891</p>
        </div>
        <button class="h-10 w-10 rounded-full bg-slate-50 text-slate-400 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-500 dark:hover:bg-slate-700 flex items-center justify-center transition-colors">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
    </header>
    <div class="flex flex-col bg-white dark:bg-[#1a2632]">
        <section class="px-8 pt-8 pb-2 text-center">
            <div class="inline-block relative">
                <div class="w-20 h-20 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 font-bold text-sm shrink-0 overflow-hidden ring-4 ring-slate-50 dark:ring-slate-800 mx-auto">
                    <span class="material-symbols-outlined text-4xl">person</span>
                </div>
                <div class="absolute bottom-0 right-0 bg-green-500 border-2 border-white dark:border-[#1a2632] w-5 h-5 rounded-full"></div>
            </div>
            <h2 class="mt-3 text-lg font-bold text-slate-900 dark:text-white">Officer Somchai</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Patrol Unit #882</p>
        </section>
        <section class="px-6 py-6">
            <h3 class="text-center text-base font-semibold text-slate-700 dark:text-slate-200 mb-6">ความประทับใจในการช่วยเหลือ</h3>
            <div class="flex justify-between items-end px-2 gap-2">
                <button onclick="selectSatisfactionRating(1)" class="satisfaction-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="1">
                    <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-400 dark:text-red-400 group-hover:bg-red-100 dark:group-hover:bg-red-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_very_dissatisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-red-500 transition-colors">แย่มาก</span>
                </button>
                <button onclick="selectSatisfactionRating(2)" class="satisfaction-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="2">
                    <div class="w-12 h-12 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-400 dark:text-orange-400 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_dissatisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-orange-500 transition-colors">แย่</span>
                </button>
                <button onclick="selectSatisfactionRating(3)" class="satisfaction-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="3">
                    <div class="w-12 h-12 rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-400 dark:text-yellow-400 group-hover:bg-yellow-100 dark:group-hover:bg-yellow-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_neutral</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-yellow-500 transition-colors">พอใช้</span>
                </button>
                <button onclick="selectSatisfactionRating(4)" class="satisfaction-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="4">
                    <div class="w-12 h-12 rounded-full bg-lime-50 dark:bg-lime-900/20 text-lime-500 dark:text-lime-400 group-hover:bg-lime-100 dark:group-hover:bg-lime-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_satisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-lime-600 transition-colors">ดี</span>
                </button>
                <button onclick="selectSatisfactionRating(5)" class="satisfaction-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="5">
                    <div class="w-12 h-12 rounded-full bg-green-50 dark:bg-green-900/20 text-green-500 dark:text-green-400 group-hover:bg-green-100 dark:group-hover:bg-green-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_very_satisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-green-600 transition-colors">ดีมาก</span>
                </button>
            </div>
        </section>
        <section class="px-6 py-6">
            <h3 class="text-center text-base font-semibold text-slate-700 dark:text-slate-200 mb-6">ระยะเวลาในการช่วยเหลือ</h3>
            <div class="flex justify-between items-end px-2 gap-2">
                <button onclick="selectTimeRating(1)" class="time-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="1">
                    <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-400 dark:text-red-400 group-hover:bg-red-100 dark:group-hover:bg-red-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_very_dissatisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-red-500 transition-colors">แย่มาก</span>
                </button>
                <button onclick="selectTimeRating(2)" class="time-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="2">
                    <div class="w-12 h-12 rounded-full bg-orange-50 dark:bg-orange-900/20 text-orange-400 dark:text-orange-400 group-hover:bg-orange-100 dark:group-hover:bg-orange-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_dissatisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-orange-500 transition-colors">แย่</span>
                </button>
                <button onclick="selectTimeRating(3)" class="time-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="3">
                    <div class="w-12 h-12 rounded-full bg-yellow-50 dark:bg-yellow-900/20 text-yellow-400 dark:text-yellow-400 group-hover:bg-yellow-100 dark:group-hover:bg-yellow-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_neutral</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-yellow-500 transition-colors">พอใช้</span>
                </button>
                <button onclick="selectTimeRating(4)" class="time-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="4">
                    <div class="w-12 h-12 rounded-full bg-lime-50 dark:bg-lime-900/20 text-lime-500 dark:text-lime-400 group-hover:bg-lime-100 dark:group-hover:bg-lime-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_satisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-lime-600 transition-colors">ดี</span>
                </button>
                <button onclick="selectTimeRating(5)" class="time-btn group flex flex-col items-center gap-2  transition-all hover:-translate-y-1" data-rating="5">
                    <div class="w-12 h-12 rounded-full bg-green-50 dark:bg-green-900/20 text-green-500 dark:text-green-400 group-hover:bg-green-100 dark:group-hover:bg-green-900/40 flex items-center justify-center transition-all">
                        <span class="material-symbols-outlined text-3xl">sentiment_very_satisfied</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-400 uppercase tracking-wide group-hover:text-green-600 transition-colors">ดีมาก</span>
                </button>
            </div>
        </section>
        <section class="px-8 pb-2">
            <div class="relative">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2" for="feedback">คำแนะนำ / ติชม</label>
                <textarea class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-primary focus:ring-primary dark:focus:border-blue-500 dark:focus:ring-blue-500 text-sm p-3 min-h-[100px] resize-none" id="feedback" placeholder="คำแนะนำของท่านช่วยให้เราสามารถพัฒนาให้ดียิ่งขึ้นได้"></textarea>
            </div>
        </section>
    </div>
    <footer class="px-8 py-6 bg-white dark:bg-[#1a2632] border-t border-slate-100 dark:border-slate-700">
        <button onclick="submitRating()" class="w-full flex items-center justify-center gap-2 px-4 py-3.5 text-sm font-bold text-white bg-primary hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98]">
            <span>ยืนยันการประเมิน</span>
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
    </footer>
</div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm transition-all duration-300 animate-fadeIn">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-[440px] bg-white dark:bg-[#1a2632] rounded-xl shadow-2xl flex flex-col overflow-hidden ring-1 ring-black/5 dark:ring-white/10 p-10 items-center text-center transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="mb-8 animate-pop">
                <div class="w-24 h-24 rounded-full bg-green-50 dark:bg-green-900/20 flex items-center justify-center ring-1 ring-green-100 dark:ring-green-800">
                    <span class="material-symbols-outlined text-6xl text-green-500 dark:text-green-400" style="font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 48;">check</span>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-0 tracking-tight">ขอบคุณ</h1>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4 tracking-tight">สำหรับความคิดเห็น</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-10 leading-relaxed text-[15px]">
                ความคิดเห็นของท่านช่วยให้เราสามารถปรับปรุงและพัฒนาให้ดียิ่งขึ้นในอนาคต
            </p>
            <div class="w-full space-y-4">
                <button onclick="returnHome()" class="w-full py-3.5 px-4 bg-primary hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>กลับหน้าแรก</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Rating state
    let ratingData = {
        satisfactionRating: null,
        timeRating: null,
        feedback: ''
    };

    const ratingLabels = {
        1: 'แย่มาก',
        2: 'แย่',
        3: 'พอใช้',
        4: 'ดี',
        5: 'ดีมาก'
    };

    // Select satisfaction rating
    function selectSatisfactionRating(rating) {
        ratingData.satisfactionRating = rating;
        
        // Remove selected class from all buttons
        document.querySelectorAll('.satisfaction-btn').forEach(btn => {
            btn.classList.remove('rating-selected');
            btn.style.transform = '';
        });
        
        // Add selected class to clicked button
        const selectedBtn = document.querySelector(`.satisfaction-btn[data-rating="${rating}"]`);
        selectedBtn.classList.add('rating-selected');
        
        console.log('Satisfaction Rating Selected:', rating, '-', ratingLabels[rating]);
    }

    // Select time rating
    function selectTimeRating(rating) {
        ratingData.timeRating = rating;
        
        // Remove selected class from all buttons
        document.querySelectorAll('.time-btn').forEach(btn => {
            btn.classList.remove('rating-selected');
            btn.style.transform = '';
        });
        
        // Add selected class to clicked button
        const selectedBtn = document.querySelector(`.time-btn[data-rating="${rating}"]`);
        selectedBtn.classList.add('rating-selected');
        
        console.log('Time Rating Selected:', rating, '-', ratingLabels[rating]);
    }

    // Submit rating
    function submitRating() {
        // Get feedback text
        ratingData.feedback = document.getElementById('feedback').value;

        // Validate ratings
        if (ratingData.satisfactionRating === null) {
            alert('กรุณาเลือกคะแนนความประทับใจในการช่วยเหลือ');
            return;
        }

        if (ratingData.timeRating === null) {
            alert('กรุณาเลือกคะแนนระยะเวลาในการช่วยเหลือ');
            return;
        }

        // Log complete rating data
        console.log('=== Rating Submission ===');
        console.log('Request ID: #REQ-2023-891');
        console.log('Officer: Officer Somchai - Patrol Unit #882');
        console.log('---');
        console.log('ความประทับใจในการช่วยเหลือ:', ratingData.satisfactionRating, '-', ratingLabels[ratingData.satisfactionRating]);
        console.log('ระยะเวลาในการช่วยเหลือ:', ratingData.timeRating, '-', ratingLabels[ratingData.timeRating]);
        console.log('คำแนะนำ/ติชม:', ratingData.feedback || '(ไม่มี)');
        console.log('---');
        console.log('Full Data Object:', ratingData);
        console.log('========================');

        // Show success modal
        showSuccessModal();
    }

    // Show success modal
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('modalContent');
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        
        modal.classList.remove('hidden');
        
        // Trigger animation after a brief delay
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    // Close success modal
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Return to home
    function returnHome() {
        console.log('Returning to home page...');
        closeSuccessModal();
        // Add your navigation logic here
        // window.location.href = '/home';
    }

 

    // Optional: Reset form function
    function resetForm() {
        ratingData = {
            satisfactionRating: null,
            timeRating: null,
            feedback: ''
        };

        document.querySelectorAll('.satisfaction-btn, .time-btn').forEach(btn => {
            btn.classList.remove('rating-selected');
            btn.style.transform = '';
        });

        document.getElementById('feedback').value = '';
    }
</script>

@endsection