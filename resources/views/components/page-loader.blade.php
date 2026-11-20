<!-- Top Progress Bar (Otomatis jalan saat pindah halaman / submit form) -->
<div x-data="{ loading: false, progress: 0 }"
     x-init="
        const start = () => {
            loading = true;
            progress = 25;
            const timer = setInterval(() => {
                if (progress < 85) progress += Math.random() * 15;
                else clearInterval(timer);
            }, 200);
        };

        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && !link.target && !link.href.includes('#') && link.origin === window.location.origin && !e.ctrlKey && !e.metaKey) {
                start();
            }
        });

        document.addEventListener('submit', () => start());
        window.addEventListener('pageshow', () => { loading = false; progress = 0; });
     "
     x-show="loading"
     style="display: none;"
     class="fixed top-0 inset-x-0 z-[9999] h-[3px] bg-indigo-100 pointer-events-none">
    <div class="h-full bg-indigo-600 transition-all duration-200 ease-out shadow-[0_0_8px_rgba(79,70,229,0.6)]"
         :style="`width: ${progress}%`">
    </div>
</div>