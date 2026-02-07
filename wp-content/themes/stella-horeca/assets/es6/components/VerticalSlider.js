class VerticalSlider {
    constructor(section) {
        // Set variables
        this.dom = {
            body: document.querySelector('body'),
            wrapper: false,
            items: false,
            controllers: false
        };
        this.vars = {
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
        };

        // Initialize the slider
        this.init(section);
    }

    /**
     * Testimonials slider init functions
     * @param {DOM Object} section
     */
    init(section) {
        // Set variables
        if (section && section.querySelector('.items-wrapper')) {
            this.dom.wrapper = section.querySelector('.items-wrapper');
            this.dom.items = section.querySelectorAll('.items-wrapper .slider-item');
            this.dom.controllers = section.querySelectorAll('.controllers .controller');
            this.vars.interval = parseInt(section.getAttribute('data-interval'));
            this.vars.itemsNo = (this.dom.items && this.dom.items.length) ? this.dom.items.length : 0;
            this.bind();
        }
    }

    /**
     * Bind the event listeners
     */
    bind() {
        // Check for the slider
        if (this.dom.wrapper) {
            // Disable scroll
            this.dom.body.classList.add('freez');

            // Add touchmove event listener
            document.addEventListener('touchmove', this.touchMoveHandler.bind(this), false);

            // Add touchstart event listener
            document.addEventListener('touchstart', this.touchStartHandler.bind(this), false);

            // Add wheel event listener to the wrapper
            this.dom.wrapper.addEventListener('wheel', this.wheelHandler.bind(this));

            // Add swipe event listener to the wrapper
            this.dom.wrapper.addEventListener('swipe', this.swipeHandler.bind(this));

            // Add keydown event listener
            document.addEventListener('keydown', this.keydownHandler.bind(this));

            // Detect auto slide
            this.vars.autoslide = this.autoSlide();

            // Add click event listener
            if (this.dom.controllers && this.dom.controllers.length) {
                this.dom.controllers.forEach(controller => {
                    controller.addEventListener('click', this.controllersClickHandler.bind(this));
                });
            }
        }
    }

    /**
     * Handle the controller button click event
     * @param {Event} evt 
     * @returns 
     */
    controllersClickHandler(evt) {
        evt.preventDefault();
        // Update slider
        this.updateSlider(this.classList.contains('up') ? 'down' : 'up');
    }

    /**
     * Handle the touch move event
     * @param {Event} evt 
     * @returns 
     */
    touchMoveHandler(evt) {
        if (!this.vars.yDown) {
            return;
        }
        // Update slider
        var yUp = evt.touches[0].clientY,
            yDiff = this.vars.yDown - yUp;
        this.updateSlider(yDiff > 0 ? 'up' : 'down');
        // Reset values
        this.vars.yDown = null;
    }

    /**
     * Handle the touch event
     * @param {Event} evt 
     */
    touchStartHandler(evt) {
        const firstTouch = this.getTouches(evt)[0];
        this.vars.yDown = firstTouch.clientY;
    }

    /**
     * Handle the wheel event
     * @param {Event} evt 
     */
    wheelHandler(evt) {
        evt.preventDefault();
        let deltaY = evt.deltaY || evt.originalEvent.deltaY,
            direction = deltaY > 0 ? 'up' : 'down';
        this.updateSlider(direction);
    }

    /**
     * Handle the swipe event
     * @param {Event} evt 
     */
    swipeHandler(evt) {
        let direction = evt.direction;
        this.updateSlider(direction);
    }

    /**
     * Handle the keydown event
     * @param {Event} evt 
     */
    keydownHandler(evt) {
        if (evt.key === 'ArrowUp' || evt.key === 'ArrowRight') {
            this.updateSlider('up');
        } else if (evt.key === 'ArrowDown' || evt.key === 'ArrowLeft') {
            this.updateSlider('down');
        }
    }

    /**
     * Return the event touch values
     * @param {Event} evt 
     * @returns 
     */
    getTouches(evt) {
        return (
            evt.touches ||
            evt.originalEvent.touches
        );
    }

    /**
     * Update the slider
     * @param {String} direction 
     * @returns 
     */
    updateSlider(direction) {
        // Check for update blocking
        if (this.vars.flag) {
            return;
        }

        // Prevent re-updating for 960ms
        this.vars.flag = true;
        setTimeout(() => {
            this.vars.flag = false;
        }, 960);

        // Check the swipe direction
        if (direction === 'up' || direction === 'left') {
            if (this.vars.i < this.vars.itemsNo) {
                // Remove the prev class
                var prev = this.dom.wrapper.querySelector('.slider-item.prev');
                if (prev) {
                    prev.classList.remove('prev');
                    prev.classList.add('top');
                }

                // Remove the active class, and add prev/top classes to the active one
                var active = this.dom.wrapper.querySelector('.slider-item.active');
                if (active) {
                    active.classList.add('prev');
                    active.classList.add('top');
                    active.classList.remove('active');
                }

                // Activate the next item, and remove the next/bottom classes from it
                var next = this.dom.wrapper.querySelector('.slider-item.next');
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
                var items = this.dom.wrapper.querySelectorAll('.slider-item:not(.active):not(.top)');
                if (items && items.length) {
                    items.forEach(function (item) {
                        item.classList.add('bottom');
                    });
                }

                // Increase the number "i"
                this.vars.i++;
            }
        } else {
            if (this.vars.i > 1) {
                // Remove the next class
                var next = this.dom.wrapper.querySelector('.slider-item.next');
                if (next) {
                    next.classList.remove('next');
                    next.classList.add('bottom');
                }

                // Remove the active class, and add next/bottom classes to the active one
                var active = this.dom.wrapper.querySelector('.slider-item.active');
                if (active) {
                    active.classList.add('next');
                    active.classList.add('bottom');
                    active.classList.remove('active');
                }

                // Activate the prev item, and remove the prev/top classes from it
                var prev = this.dom.wrapper.querySelector('.slider-item.prev');
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
                var items = this.dom.wrapper.querySelectorAll('.slider-item:not(.active):not(.bottom)');
                if (items && items.length) {
                    items.forEach(function (item) {
                        item.classList.add('top');
                    });
                }

                // Decrease the number "i"
                this.vars.i--;
            }
        }
    }

    /**
     * Auto swipe functionality
     */
    autoSlide() {
        // Clear interval
        if (this.vars.autoslide) {
            clearInterval(this.vars.autoslide);
        }

        // Set autoslide
        if (this.vars.interval && this.vars.interval > 1000) {
            if (this.dom.items && this.dom.items.length) {
                this.vars.autoslide = setInterval(() => {
                    var active = this.dom.wrapper.querySelector('.slider-item.active');
                    this.updateSlider(active.nextElementSibling ? 'down' : 'top');
                }, this.vars.interval);
            }
        }
    }
}

module.exports = VerticalSlider;
