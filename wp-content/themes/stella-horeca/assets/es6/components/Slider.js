// Slider functionality
let _this = (module.exports = {
    // Set the dom elements
    dom: {
        wrapper: false,
        items: false,
        controllers: false
    },

    // Set variables
    vars: {
        c: false,
		prev: false,
        next: false,
        touchstartX: 0,
        touchendX: 0,
        interval: 0,
        autoslide: false,
        lastWidth: window.innerWidth
	},

    /**
     * Testimonials slider init functions
     * @param {DOM Object} section
     */
    init: function(section) {

        // Set variables
        _this = this;
        if (section && section.querySelector('.items-wrapper')) {
            _this.dom.wrapper = section.querySelector('.items-wrapper');
            _this.dom.items = section.querySelector('.items-wrapper .items');
            _this.dom.controllers = section.querySelectorAll('.controllers .controller');
            _this.dom.prev = section.querySelectorAll('.controllers .controller.prev');
            _this.dom.next = section.querySelectorAll('.controllers .controller.next');
            _this.vars.interval = parseInt(section.getAttribute('data-interval'));
            _this.bind();
        }
    },

    /**
     * Bind the event listeners
     */
	bind: function() {

        // Check for the slider
        if (_this.dom.wrapper) {

            // Add resize event listener
            window.addEventListener('resize', _this.resizeHandler);
            
            // Add keydown event listener
            document.addEventListener('keydown', _this.keydownHandler);

            // Add touchstart event listener
            document.addEventListener('touchstart', _this.touchStartHandler, false);

            // Add touchend event listener
            document.addEventListener('touchend', _this.touchEndHandler, false);

            // Add click event listener
            _this.dom.controllers.forEach(function(controller) {
                controller.addEventListener('click', _this.clickHandler, false);
            });

            // Detect auto slide
            _this.vars.autoslide = _this.autoSlide();
        }
	},

    /**
     * Resize event handler
     * @param {Event} evt 
     * @returns 
     * @todo - Instead of movieng to the start position, fix the position of the current active element
     */
    resizeHandler: function() {
        // Check for the changed width
        if (_this.vars.lastWidth != window.innerWidth) {
            _this.dom.items.setAttribute('data-translate', 0);
            _this.dom.items.style.transform = 'translateX(0px)';
            _this.swipe();
        }

        // Re-set the last width
        _this.vars.lastWidth = window.innerWidth;
    },

    /**
     * Handle the keydown event
     * @param {Event} evt 
     */
    keydownHandler: function(evt) {
        if (evt.keyCode == '37') {
            // left arrow
            _this.swipe(true);
        } else if (evt.keyCode == '39') {
            // right arrow
            _this.swipe(false);
        }
    },

    /**
     * Handle the touch event
     * @param {Event} evt 
     */
    touchStartHandler: function(evt) {
        _this.vars.touchstartX = evt.changedTouches[0].screenX;
    },

    /**
     * Handle the touch event
     * @param {Event} evt 
     */
    touchEndHandler: function(evt) {
        _this.vars.touchendX = evt.changedTouches[0].screenX;
        _this.gestureHandler();
    },
            
    // Add click event listener
    clickHandler: function(evt) {
        evt.preventDefault();

        // Swipe
        _this.swipe(!this.classList.contains('next'));
    },

    // On scroll left/right - Increase/reduce the size with the screen width
    gestureHandler: function() {

        // Check the swipe intention
        if ((_this.vars.touchendX + 50) <= _this.vars.touchstartX || _this.vars.touchendX >= (_this.vars.touchstartX + 50)) {

            // Swiped left
            if ((_this.vars.touchendX + 50) <= _this.vars.touchstartX) {
                _this.swipe();
            }

            // Swiped right
            if (_this.vars.touchendX >= (_this.vars.touchstartX + 50)) {
                _this.swipe(true);
            }
        }
    },

    /**
     * Swipe the items by the given data
     * @param {Boolean} prev 
     */
    swipe: function(prev = false)  {

        // RE-set autoslide
        _this.vars.autoslide = _this.autoSlide();

        // Get the necessary data
        var items = _this.dom.items.querySelectorAll('.item'),
            firstWidth = items[0].offsetWidth,
            containerWidth = _this.dom.items.offsetWidth - 18,
            allWidth = 0;
            items.forEach(function(m) {
                allWidth = allWidth + m.offsetWidth
            });
    
        var current = parseInt(_this.dom.items.getAttribute('data-translate')),
            translate = current + (prev ? firstWidth : -firstWidth);

        if ((allWidth + translate) > (containerWidth + 1) && translate < 1) {
            _this.dom.items.setAttribute('data-translate', translate);
            _this.dom.items.style.transform = 'translateX('+translate+'px)';
        }
    
        // Disable / enable next
        if (_this.dom.next) {
            _this.dom.next.forEach(function(next) {
                if (allWidth - (containerWidth + Math.abs(translate) + 18) < firstWidth) {
                    next.classList.add('disabled');
                } else {
                    next.classList.remove('disabled');
                }
            });
        }
    
        // Disable / enable prev
        if (_this.dom.prev) {
            _this.dom.prev.forEach(function(prev) {
                if ((Math.abs(translate) + 18) < firstWidth) {
                    prev.classList.add('disabled');
                } else {
                    prev.classList.remove('disabled');
                }
            });
        }
    },

    /**
     * Auto swipe functionality
     * @param {DOM Object} itemSection 
     */
    autoSlide: function(itemSection) {
        // Clear interval
        if (_this.vars.autoslide) {
            clearInterval(_this.vars.autoslide);
        }

        // Ser autoslide
        if (_this.vars.interval && _this.vars.interval > 1000) {
            if (_this.vars.items && _this.vars.items.length) {
                _this.vars.autoslide = setInterval(function() {
                    _this.vars.c = parseInt(itemSection.querySelector('.item.active').getAttribute('data-no'));
                    if (_this.vars.c == 0) {
                        _this.swipe()
                    } else  if (_this.vars.c == (_this.vars.items.length - 1)) {
                        _this.swipe(true);
                    }
                }, _this.vars.interval);
            }
        }
    }
});
