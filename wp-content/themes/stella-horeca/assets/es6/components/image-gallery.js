// Example class
let _this = (module.exports = {

    // Set the dom elements
    dom: {
        main: document.querySelector('.image-gallery .main-image .image'),
        images: document.querySelectorAll('.image-gallery .gallery-images .small-image')
    },

    // Set variables
    vars: {
        flag: false,
	},

    /**
     * Init functions
     */
    init: function() {
        console.log(_this.dom)
        if (_this.dom.images && _this.dom.main && _this.dom.images.length > 0) {
            _this.dom.images.forEach(function(image) {
                image.addEventListener('click', function() {
                    var src = this.getAttribute('data-src');
                    if (src) {
                        _this.dom.main.src = src;
                        _this.dom.main.srcset = src;
                    }
                });
            });
        }
    }
});
