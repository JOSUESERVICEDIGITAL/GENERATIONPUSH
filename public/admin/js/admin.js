/**
 * ================================================================
 * GENERATION PUSH
 * Administration
 * ================================================================
 */

document.addEventListener('DOMContentLoaded', () => {

    const GPAdmin = {

        sidebar: null,
        overlay: null,
        openButton: null,
        closeButton: null,


        /**
         * Initialisation
         */
        init() {

            this.sidebar =
                document.getElementById('adminSidebar');

            this.overlay =
                document.getElementById('adminOverlay');

            this.openButton =
                document.getElementById('sidebarOpen');

            this.closeButton =
                document.getElementById('sidebarClose');


            this.initSidebar();

            this.initBootstrapTooltips();

            this.initBootstrapPopovers();

            this.initDeleteConfirmations();

            this.initSubmitLoading();

            this.initAutoHideAlerts();

            this.initFileInputs();

        },


        /**
         * ============================================================
         * SIDEBAR MOBILE
         * ============================================================
         */
        initSidebar() {

            if (!this.sidebar) {
                return;
            }


            if (this.openButton) {

                this.openButton.addEventListener(
                    'click',
                    () => this.openSidebar()
                );

            }


            if (this.closeButton) {

                this.closeButton.addEventListener(
                    'click',
                    () => this.closeSidebar()
                );

            }


            if (this.overlay) {

                this.overlay.addEventListener(
                    'click',
                    () => this.closeSidebar()
                );

            }


            document.addEventListener(
                'keydown',
                (event) => {

                    if (event.key === 'Escape') {
                        this.closeSidebar();
                    }

                }
            );


            window.addEventListener(
                'resize',
                () => {

                    if (window.innerWidth >= 992) {

                        this.sidebar.classList.remove('open');

                        if (this.overlay) {
                            this.overlay.classList.remove('active');
                        }

                        document.body.style.overflow = '';

                    }

                }
            );

        },


        openSidebar() {

            this.sidebar?.classList.add('open');

            this.overlay?.classList.add('active');

            document.body.style.overflow = 'hidden';

        },


        closeSidebar() {

            this.sidebar?.classList.remove('open');

            this.overlay?.classList.remove('active');

            document.body.style.overflow = '';

        },


        /**
         * ============================================================
         * TOOLTIPS
         * ============================================================
         */
        initBootstrapTooltips() {

            if (
                typeof bootstrap === 'undefined' ||
                !bootstrap.Tooltip
            ) {
                return;
            }


            const elements =
                document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'
                );


            elements.forEach((element) => {

                new bootstrap.Tooltip(element);

            });

        },


        /**
         * ============================================================
         * POPOVERS
         * ============================================================
         */
        initBootstrapPopovers() {

            if (
                typeof bootstrap === 'undefined' ||
                !bootstrap.Popover
            ) {
                return;
            }


            const elements =
                document.querySelectorAll(
                    '[data-bs-toggle="popover"]'
                );


            elements.forEach((element) => {

                new bootstrap.Popover(element);

            });

        },


        /**
         * ============================================================
         * CONFIRMATION SUPPRESSION
         * ============================================================
         *
         * Pour les futurs formulaires :
         *
         * data-confirm-delete="true"
         *
         */
        initDeleteConfirmations() {

            const forms =
                document.querySelectorAll(
                    'form[data-confirm-delete="true"]'
                );


            forms.forEach((form) => {

                form.addEventListener(
                    'submit',
                    (event) => {

                        const message =
                            form.dataset.confirmMessage ||
                            'Voulez-vous vraiment supprimer cet élément ?';


                        if (!window.confirm(message)) {

                            event.preventDefault();

                            event.stopPropagation();

                        }

                    }
                );

            });

        },


        /**
         * ============================================================
         * LOADING SUR SUBMIT
         * ============================================================
         */
        initSubmitLoading() {

            const forms =
                document.querySelectorAll(
                    'form[data-loading="true"]'
                );


            forms.forEach((form) => {

                form.addEventListener(
                    'submit',
                    () => {

                        const button =
                            form.querySelector(
                                'button[type="submit"]'
                            );


                        if (!button) {
                            return;
                        }


                        button.disabled = true;


                        const loadingText =
                            button.dataset.loadingText ||
                            'Traitement...';


                        button.dataset.originalContent =
                            button.innerHTML;


                        button.innerHTML = `
                            <span
                                class="spinner-border spinner-border-sm me-2"
                                aria-hidden="true">
                            </span>

                            ${loadingText}
                        `;

                    }
                );

            });

        },


        /**
         * ============================================================
         * ALERTES BOOTSTRAP
         * ============================================================
         */
        initAutoHideAlerts() {

            const alerts =
                document.querySelectorAll(
                    '.gp-auto-hide-alert'
                );


            alerts.forEach((alert) => {

                setTimeout(() => {

                    if (
                        typeof bootstrap !== 'undefined' &&
                        bootstrap.Alert
                    ) {

                        bootstrap.Alert
                            .getOrCreateInstance(alert)
                            .close();

                    }

                }, 5000);

            });

        },


        /**
         * ============================================================
         * NOM DES FICHIERS
         * ============================================================
         */
        initFileInputs() {

            const inputs =
                document.querySelectorAll(
                    'input[type="file"][data-file-name]'
                );


            inputs.forEach((input) => {

                input.addEventListener(
                    'change',
                    () => {

                        const targetId =
                            input.dataset.fileName;


                        const target =
                            document.getElementById(targetId);


                        if (!target) {
                            return;
                        }


                        if (
                            input.files &&
                            input.files.length
                        ) {

                            target.textContent =
                                input.files[0].name;

                        } else {

                            target.textContent =
                                'Aucun fichier sélectionné';

                        }

                    }
                );

            });

        }

    };


    GPAdmin.init();


    /**
     * Accessible également depuis les pages enfants.
     */
    window.GPAdmin = GPAdmin;

});
