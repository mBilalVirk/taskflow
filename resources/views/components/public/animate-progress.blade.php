<style>
    .progress-bar {
        transition: width 0.1s ease-out;
    }
</style>

<!-- Scroll Progress Bar -->
<div class="fixed top-0 left-0 w-full h-1 bg-dark-bg z-50 glassmorphism border-0">
    <div id="progress" class="progress-bar h-full bg-gradient-to-r from-blue-500 to-purple-600 w-0">
    </div>
</div>
<script>
    const progressBar = document.getElementById('progress');

    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;

        progressBar.style.width = `${scrollPercent}%`;
    });
</script>
