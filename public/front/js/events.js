document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | PROCHAINS ÉVÉNEMENTS
    |--------------------------------------------------------------------------
    */

    const eventsElement = document.querySelector('.gp-events-swiper');

    let eventsSwiper = null;

    if (eventsElement) {

        eventsSwiper = new Swiper(eventsElement, {

            slidesPerView: 1.08,

            spaceBetween: 18,

            speed: 700,

            grabCursor: true,

            watchOverflow: true,

            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },

            navigation: {
                nextEl: '.gp-events-next',
                prevEl: '.gp-events-prev',
            },

            pagination: {
                el: '.gp-events-pagination',
                clickable: true,
            },

            breakpoints: {

                576: {
                    slidesPerView: 1.5,
                    spaceBetween: 20,
                },

                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },

                1200: {
                    slidesPerView: 3,
                    spaceBetween: 26,
                },
            },
        });
    }


    /*
    |--------------------------------------------------------------------------
    | ÉVÉNEMENTS PASSÉS
    |--------------------------------------------------------------------------
    */

    const pastElement = document.querySelector('.gp-past-swiper');

    if (pastElement) {

        new Swiper(pastElement, {

            slidesPerView: 1.08,

            spaceBetween: 18,

            speed: 700,

            grabCursor: true,

            watchOverflow: true,

            navigation: {
                nextEl: '.gp-past-next',
                prevEl: '.gp-past-prev',
            },

            breakpoints: {

                576: {
                    slidesPerView: 1.5,
                },

                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },

                1200: {
                    slidesPerView: 3,
                    spaceBetween: 26,
                },
            },
        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTRES
    |--------------------------------------------------------------------------
    */

    const filterButtons =
        document.querySelectorAll('.gp-event-filter');


    filterButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            const filter =
                this.dataset.filter;


            /*
            |--------------------------------------------------------------------------
            | Bouton actif
            |--------------------------------------------------------------------------
            */

            filterButtons.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');


            /*
            |--------------------------------------------------------------------------
            | Filtrage des slides
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.gp-event-slide')
                .forEach(function (slide) {

                    const category =
                        slide.dataset.category;

                    if (
                        filter === 'all'
                        || category === filter
                    ) {
                        slide.style.display = '';
                    } else {
                        slide.style.display = 'none';
                    }
                });


            /*
            |--------------------------------------------------------------------------
            | Mise à jour Swiper
            |--------------------------------------------------------------------------
            */

            if (eventsSwiper) {

                eventsSwiper.update();

                eventsSwiper.slideTo(0);

            }

        });

    });

});
