// Include Cookies
const Cookies = require('./components/cookies');

/**
 * Show the modal on the spectific scroll position
 * @param {DOMContent} modal
 * @param {Integer} precentage
 */
function showModalOnScrollPosition(modal, value)
{
    if (value > 99) {
        value = 99;
    }

    document.addEventListener('scroll', function() {
        // Calculate the percentage of the page that has been scrolled.
        var scrollPercent = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight) * 100;

        // If the user has scrolled past the threshold, execute the function.
        if (scrollPercent > value && !modal.classList.contains('disallow')) {
            var id = modal.id,
                cookieVal = Cookies.getCookie(id),
                displayed = isNaN(parseInt(cookieVal)) ? 0 : parseInt(cookieVal);

            // Set cookie
            Cookies.setCookie(id, (displayed + 1));

            // Show the modal
            modal.classList.add('disallow');
            modal.classList.add('show');
        }
    });
}

/**
 * Show the modal after seconds
 * @param {DOMContent} modal
 * @param {Integer} value
 */
function showModalAfterSeconds(modal, value)
{
    setTimeout(() => {
        if (!modal.classList.contains('disallow')) {
            var id = modal.id,
                cookieVal = Cookies.getCookie(id),
                displayed = isNaN(parseInt(cookieVal)) ? 0 : parseInt(cookieVal);

            // Set cookie
            Cookies.setCookie(id, (displayed + 1));
    
            // Show the modal
            modal.classList.add('disallow');
            modal.classList.add('show');
        }
    }, value*1000);
}

// On document load event
document.addEventListener('DOMContentLoaded', function() {

	// Detect the modals
	var modals = document.querySelectorAll('.js-modals');
	if (modals && modals.length) {
		modals.forEach(function(modal) {
            var type = modal.getAttribute('data-type'),
                value = modal.getAttribute('data-value'),
                displayCount = modal.getAttribute('data-display-count'),
                id = modal.id,
                cookieVal = Cookies.getCookie(id),
                displayed = isNaN(parseInt(cookieVal)) ? 0 : parseInt(cookieVal),
                disallow = displayCount > displayed ? false : true
            ;

            if (!disallow) {
                switch (type) {
                    case 'seconds':
                        showModalAfterSeconds(modal, value)
                        break;

                    case 'position':
                        showModalOnScrollPosition(modal, value)
                        break;
                }
            }
		});
	}
});
