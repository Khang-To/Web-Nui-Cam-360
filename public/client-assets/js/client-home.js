document.addEventListener("DOMContentLoaded", function() {
    // 1. Khởi tạo AOS Animation
    AOS.init({ duration: 800, once: true, offset: 50 });

    // 2. Xử lý đổi màu Navbar khi scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 3. Hiệu ứng tự động nạp và phát video YouTube khi cuộn tới
    const videoIframe = document.getElementById('flycam-video');
    if (videoIframe) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    videoIframe.setAttribute('src', videoIframe.getAttribute('data-src'));
                    obs.unobserve(videoIframe);
                }
            });
        }, { threshold: 0.3 });
        observer.observe(videoIframe);
    }

    // 4. Xử lý nút Back To Top
    const backToTopButton = document.getElementById("backToTop");
    if(backToTopButton) {
        window.addEventListener("scroll", () => {
            if (window.scrollY > 400) {
                backToTopButton.classList.add("show");
            } else {
                backToTopButton.classList.remove("show");
            }
        });

        backToTopButton.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }
});
