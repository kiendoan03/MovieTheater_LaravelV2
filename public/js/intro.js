document.addEventListener('DOMContentLoaded', () => {

    const intro = document.getElementById('container');
    const home = document.querySelector('.home');

    const introPlayed = sessionStorage.getItem('introPlayed');

    // đã chạy trong tab này
    if (introPlayed === 'true') {

        intro?.remove();

        document.documentElement.classList.remove('preload-intro');

        if (home) {
            home.style.opacity = '1';
            home.style.visibility = 'visible';
        }

        return;
    }

    // đánh dấu đã chạy
    sessionStorage.setItem('introPlayed', 'true');

    window.addEventListener('load', () => {

        setTimeout(() => {

            intro.style.transition = '.6s';
            intro.style.opacity = '0';

            setTimeout(() => {

                intro.remove();

                // HIỆN LẠI TOÀN BỘ TRANG
                document.documentElement.classList.remove('preload-intro');

                if (home) {
                    home.style.opacity = '1';
                    home.style.visibility = 'visible';
                }

            }, 600);

        }, 2000);

    });

});