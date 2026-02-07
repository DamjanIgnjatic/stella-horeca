// Example class
let _this = (module.exports = {

    // Set the dom elements
    dom: {
        body: document.querySelector('body'),
        modals: document.querySelectorAll('.theme-modal')
    },

    // Set variables
    vars: {
        flag: false,
	},

    /**
     * Init functions
     */
    init: function() {

        // Init
        _this = this;

        // Bind event listeners
        _this.bind();
    },

    /**
     * Bind the event listeners
     */
    bind: function() {

        // Check for the slider
        if (_this.dom.modals) {

            // Add click event listener
            _this.clickListener();
        }
    },

    clickListener: function() {
        // Go trought the modals and detect the inside / close button click
        if (_this.dom.modals && _this.dom.modals.length > 0) {
            _this.dom.modals.forEach(function (modal) {
                modal.addEventListener('click', function(evt) {
                    if (!evt.target.classList.contains('modal-close') && evt.target !== this) {
                        return;
                    }
                    
                    _this.hide();
                })
            });
        }
    },

    /**
     * Fill the modal before showing it
     * @param {String} title 
     * @param {String} content 
     * @param {Bool|String} redirect 
     * @param {String} selector 
     */
    fill: function(title = '', content = '', redirect = false, selector = 'default') {

        // Find the modal by selector
        _this.dom.modals.forEach(function(modal) {
            if (modal.classList.contains(selector)) {

                // Add title
                var modalTitle = modal.querySelector('.modal-title');
                if (modalTitle) {
                    if (title) {
                        modalTitle.style.display = 'block';
                        modalTitle.textContent = title;
                    } else {
                        modalTitle.style.display = 'none';
                    }
                }
    
                // Add content
                var modalContent = modal.querySelector('.modal-text');
                if (modalContent) {
                    if (content) {
                        modalContent.style.display = 'block';
                        modalContent.innerHTML = content;
                    } else {
                        modalContent.style.display = 'none';
                    }
                }
    
                // Add redirect url
                if (redirect) {
                    modal.setAttribute('data-redirect', redirect);
                }
            }
        });
    },

    /**
     * Show the modal window
     * @param {String} selector 
     */
    show: function(selector = 'default') {

        // Find the modal by selector
        _this.dom.modals.forEach(function(modal) {
            if (modal.classList.contains(selector)) {
                modal.classList.add('show');

                // Freez the site's body
                _this.dom.body.classList.add('freez');
            }
        });
    },

    /**
     * Hide the modal window
     */
    hide: function() {
        // Hide all modals
        _this.dom.modals.forEach(function (modal) {
            if (modal.classList.contains('show')) {
                modal.classList.remove('show');

                // Check for redirect
                var redirect = starterthemeModal.getAttribute('data-redirect');
                if (redirect) {
                    document.location.href = document.location.origin+redirect;
                }
            }
        });

        // Unfreez the site's body
        _this.dom.body.classList.remove('freez');
    }
});
