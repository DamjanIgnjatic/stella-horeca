// Background image functions
let _this = (module.exports = {

    // Set the dom elements
    dom: {
        bgs: document.querySelectorAll('.responsive-bg')
    },

    // Set variables
    vars: {
        flag: {},
	},

    /**
     * Init functions
     */
    init: function() {

        // Change the bacground image by the screen resolution
        _this.changeBgImgByScreenResolution();

        // Add resize event listener
        window.addEventListener('resize', _this.resizeHandler);
    },

    /**
     * Throttle function
     * @param {Function} fn 
     * @param {Int} wait 
     * @param {String} key 
     * @returns 
     */
    throttle: function(fn, wait, key = 'default') {
        if (!_this.vars.flag[key]) {
            fn();
            _this.vars.flag[key] = true;

            // Set throttle time
            setTimeout(() => {
                _this.vars.flag[key] = false;
            }, wait);
        }
    },

    /**
     * Resize event handler
     * @param {Event} evt 
     * @returns 
     */
    resizeHandler: function() {
        // Change the bacground image by the screen resolution
        _this.changeBgImgByScreenResolution();
    },

    /**
     * Show the desktop/mobile image by the screen resolution
     */
    changeBgImgByScreenResolution: function() {
        if (_this.dom.bgs && _this.dom.bgs.length) {
            _this.dom.bgs.forEach(function(bg) {
                var mobileBg = bg.getAttribute('data-mobile-image');
                if (mobileBg) {
                    var imgUrl = bg.getAttribute(window.innerWidth > 720 ? 'data-desktop-image' : 'data-mobile-image');
                    bg.style.backgroundImage = 'url("' + imgUrl + '")';
                }
            });
        }
    },
});
