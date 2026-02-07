// Slider functionality
let _this = (module.exports = {

    // Set the dom elements
    dom: {
        body: document.querySelector('body'),
        wrapper: false,
        items: false,
        controllers: false
    },

    // Set variables
    vars: {
        i: 1,
        c: false,
		prev: false,
        next: false,
        touchstartX: 0,
        touchendX: 0,
        interval: 0,
        autoslide: false,
        flag: false,
        itemsNo: 0,
        yDown: null
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
            _this.dom.items = section.querySelectorAll('.items-wrapper .slider-item');
            _this.dom.controllers = section.querySelectorAll('.controllers .controller');
            _this.vars.interval = parseInt(section.getAttribute('data-interval'));
            _this.vars.itemsNo = (_this.dom.items && _this.dom.items.length) ? _this.dom.items.length : 0,
            _this.bind();
        }
    },

    /**
     * Bind the event listeners
     */
	bind: function() {

        // Check for the slider
        if (_this.dom.wrapper) {

            // Disable scroll
            _this.dom.body.classList.add('freez');

            // Add touchmove event listener
            document.addEventListener('touchmove', _this.touchMoveHandler, false);

            // Add touchstart event listener
            document.addEventListener('touchstart', _this.touchStartHandler, false);

            // Add wheel event listener to the wrapper
            _this.dom.wrapper.addEventListener('wheel', _this.wheelHandler);

            // Add swipe event listener to the wrapper
            _this.dom.wrapper.addEventListener('swipe', _this.swipeHandler);
            
            // Add keydown event listener
            document.addEventListener('keydown', _this.keydownHandler);
            
            // Detect auto slide
            _this.vars.autoslide = _this.autoSlide();

            // Add click event listener
            if (_this.dom.controllers && _this.dom.controllers.length) {
                _this.dom.controllers.forEach(controller => {
                    controller.addEventListener('click', _this.controllersClickHandler);
                });
            }
        }
	},

    /**
     * Handle the controller button click event
     * @param {Event} evt 
     * @returns 
     */
    controllersClickHandler: function(evt) {
        evt.preventDefault();
  
        // Update slider
        _this.updateSlider(this.classList.contains('up') ? 'down' : 'up');
    },

    /**
     * Handle the touch move event
     * @param {Event} evt 
     * @returns 
     */
    touchMoveHandler: function(evt) {
        if (!_this.vars.yDown) {
          return;
        }
  
        // Update slider
        var yUp = evt.touches[0].clientY,
          yDiff = _this.vars.yDown - yUp;
        _this.updateSlider(yDiff > 0 ? 'up' : 'down');
  
        // Reset values
        _this.vars.yDown = null;
    },

    /**
     * Handle the touch event
     * @param {Event} evt 
     */
    touchStartHandler: function(evt) {
        const firstTouch = _this.getTouches(evt)[0];
        _this.vars.yDown = firstTouch.clientY;
    },

    /**
     * Handle the wheel event
     * @param {Event} evt 
     */
    wheelHandler: function(evt) {
        evt.preventDefault();
        let deltaY = evt.deltaY || evt.originalEvent.deltaY, 
        direction = deltaY > 0 ? 'up' : 'down';
        _this.updateSlider(direction);
    },

    /**
     * Handle the swipe event
     * @param {Event} evt 
     */
    swipeHandler: function(evt) {
        let direction = evt.direction;
        _this.updateSlider(direction);
    },

    /**
     * Handle the keydown event
     * @param {Event} evt 
     */
    keydownHandler: function(evt) {
        if (evt.key === 'ArrowUp' || evt.key === 'ArrowRight') {
        _this.updateSlider('up');
        } else if (evt.key === 'ArrowDown' || evt.key === 'ArrowLeft') {
        _this.updateSlider('down');
        }
    },

    /**
     * Return the event touch values
     * @param {Event} evt 
     * @returns 
     */
    getTouches: function(evt) {
        return (
        evt.touches ||
        evt.originalEvent.touches
        );
    },

    /**
     * Update the slider
     * @param {String} direction 
     * @returns 
     */
    updateSlider: function(direction) {

        // Check for update blocking
        if (_this.vars.flag) {
            return;
        }
    
        // Prevent re-updating for 960ms
        _this.vars.flag = true;
        setTimeout(function () {
            _this.vars.flag = false;
        }, 960);

        // Check the swipe direction
        if (direction === 'up' || direction === 'left') {
            if (_this.vars.i < _this.vars.itemsNo) {
                // Remove the prev class
                var prev =  _this.dom.wrapper.querySelector('.slider-item.prev');
                if (prev) {
                    prev.classList.remove('prev');
                    prev.classList.add('top');
                }
        
                // Remove the active class, and add prev/top classes to the active one
                var active =  _this.dom.wrapper.querySelector('.slider-item.active');
                if (active) {
                    active.classList.add('prev');
                    active.classList.add('top');
                    active.classList.remove('active');
                }
        
                // Activate the next item, and remove the next/bottom classes from it
                var next =  _this.dom.wrapper.querySelector('.slider-item.next');
                if (next) {
                    next.classList.add('active');
                    next.classList.remove('next');
                    next.classList.remove('bottom');
        
                    // Set the next/bottom classes
                    var newNext = next.nextElementSibling;
                    if (newNext) {
                        newNext.classList.add('next');
                    }
                }

                // Mark other siblings
                var items = _this.dom.wrapper.querySelectorAll('.slider-item:not(.active):not(.top)');
                if (items && items.length) {
                    items.forEach(function(item) {
                        item.classList.add('bottom');
                    });
                }
        
                // Increase the number "i"
                _this.vars.i++;
            }
        } else {
            if (_this.vars.i > 1) {
                // Remove the next class
                var next =  _this.dom.wrapper.querySelector('.slider-item.next');
                if (next) {
                    next.classList.remove('next');
                    next.classList.add('bottom');
                }
        
                // Remove the active class, and add next/bottom classes to the active one
                var active =  _this.dom.wrapper.querySelector('.slider-item.active');
                if (active) {
                    active.classList.add('next');
                    active.classList.add('bottom');
                    active.classList.remove('active');
                }
        
                // Activate the prev item, and remove the prev/top classes from it
                var prev =  _this.dom.wrapper.querySelector('.slider-item.prev');
                if (prev) {
                    prev.classList.add('active');
                    prev.classList.remove('prev');
                    prev.classList.remove('top');
        
                    // Set the prev/top classes
                    var newPrev = prev.previousElementSibling;
                    if (newPrev) {
                        newPrev.classList.add('prev');
                        newPrev.classList.add('top');
                    }
                }
        
                // Mark other siblings
                var items =  _this.dom.wrapper.querySelectorAll('.slider-item:not(.active):not(.bottom)');
                if (items && items.length) {
                    items.forEach(function(item) {
                        item.classList.add('top');
                    });
                }
        
                // Decrease the number "i"
                _this.vars.i--;
            }
        }
    },

    /**
     * Auto swipe functionality
     */
    autoSlide: function() {
        // Clear interval
        if (_this.vars.autoslide) {
            clearInterval(_this.vars.autoslide);
        }

        // Ser autoslide
        if (_this.vars.interval && _this.vars.interval > 1000) {
            if (_this.dom.items && _this.dom.items.length) {
                _this.vars.autoslide = setInterval(function() {
                    var active =  _this.dom.wrapper.querySelector('.slider-item.active');
                    _this.updateSlider(active.nextElementSibling ? 'down' : 'top');
                }, _this.vars.interval);
            }
        }
    }
});
