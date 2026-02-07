// Example class
let _this = (module.exports = {

    // Set the dom elements
    dom: {
        accordions: document.querySelectorAll('.accordions')
    },

    // Set variables
    vars: {
        flag: false,
	},

    /**
     * Init functions
     */
    init: function() {
        if (_this.dom.accordions && _this.dom.accordions.length > 0) {
            _this.dom.accordions.forEach(function(accordionsection) {
                const articles = accordionsection.querySelectorAll('.article');
                if (articles && articles.length > 0) {
                    articles.forEach(function(article) {
                        article.addEventListener('click', function() {
                            if (this.classList.contains('active')) {
                                this.classList.remove('active');
                            } else {
                                this.classList.add('active');
                            }
                        });
                    });
                }
            });
        }
    }
});
