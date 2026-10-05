document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | QUANTITÉ DE PLACES
    |--------------------------------------------------------------------------
    */

    const quantityInput =
        document.getElementById('quantity');

    const minusButton =
        document.querySelector(
            '[data-action="minus"]'
        );

    const plusButton =
        document.querySelector(
            '[data-action="plus"]'
        );


    if (quantityInput) {

        const min =
            parseInt(
                quantityInput.min || '1',
                10
            );

        const max =
            parseInt(
                quantityInput.max || '10',
                10
            );


        function setQuantity(value) {

            value = Math.max(
                min,
                Math.min(max, value)
            );

            quantityInput.value = value;

            updateTotal();
        }


        if (minusButton) {

            minusButton.addEventListener(
                'click',
                function () {

                    const current =
                        parseInt(
                            quantityInput.value || '1',
                            10
                        );

                    setQuantity(
                        current - 1
                    );

                }
            );

        }


        if (plusButton) {

            plusButton.addEventListener(
                'click',
                function () {

                    const current =
                        parseInt(
                            quantityInput.value || '1',
                            10
                        );

                    setQuantity(
                        current + 1
                    );

                }
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL DYNAMIQUE
    |--------------------------------------------------------------------------
    */

    const totalContainer =
        document.querySelector(
            '.gp-reservation-total'
        );

    const totalOutput =
        document.getElementById(
            'reservationTotal'
        );


    function updateTotal() {

        if (
            !totalContainer ||
            !totalOutput ||
            !quantityInput
        ) {
            return;
        }


        const price =
            parseFloat(
                totalContainer.dataset.price || '0'
            );

        const currency =
            totalContainer.dataset.currency || '';

        const quantity =
            parseInt(
                quantityInput.value || '1',
                10
            );


        const total =
            price * quantity;


        totalOutput.textContent =
            new Intl.NumberFormat('fr-FR', {
                maximumFractionDigits: 0
            }).format(total)
            + ' '
            + currency;

    }


    updateTotal();


    /*
    |--------------------------------------------------------------------------
    | PROTECTION DOUBLE SOUMISSION
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            'eventReservationForm'
        );

    const submitButton =
        document.getElementById(
            'reservationSubmit'
        );


    if (
        form &&
        submitButton
    ) {

        form.addEventListener(
            'submit',
            function () {

                if (!form.checkValidity()) {
                    return;
                }


                submitButton.disabled = true;


                const defaultContent =
                    submitButton.querySelector(
                        '.gp-submit-default'
                    );

                const loadingContent =
                    submitButton.querySelector(
                        '.gp-submit-loading'
                    );


                if (defaultContent) {
                    defaultContent.classList.add(
                        'd-none'
                    );
                }


                if (loadingContent) {
                    loadingContent.classList.remove(
                        'd-none'
                    );
                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ÉVÉNEMENTS SIMILAIRES
    |--------------------------------------------------------------------------
    */

    const relatedSlider =
        document.querySelector(
            '.gp-related-swiper'
        );


    if (relatedSlider) {

        new Swiper(
            relatedSlider,
            {

                slidesPerView: 1.08,

                spaceBetween: 18,

                speed: 700,

                grabCursor: true,

                watchOverflow: true,

                autoplay: {
                    delay: 5500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },

                navigation: {
                    nextEl: '.gp-related-next',
                    prevEl: '.gp-related-prev',
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

            }
        );

    }

});
