// Include Cookies
const Cookies = require('./components/cookies');

// Define dataLayer and the gtag function.
window.dataLayer = window.dataLayer || [];
function gtag() {
	dataLayer.push(arguments);
}

/**
 * Initialize Google Analytics Cookie consent v2
 */
function gaInit () {
	// Set default consent to 'denied' as a placeholder
	if (typeof gtag === 'function') {
		// Determine actual values based on your own requirements
		gtag('consent', 'default', {
			'ad_storage': 'denied',
			'ad_user_data': 'denied',
			'ad_personalization': 'denied',
			'analytics_storage': 'denied'
		});
	}
}
gaInit();

/**
 * Cookie consent v2 update
 * @param {Object} scripts 
 */
function cookieConsentUpdate(scripts) {

	// Check for scripts
	if (scripts && typeof scripts == 'object') {

		// Update the consent
		if (typeof gtag === 'function') {
			var consentData = {};
			for (const [name, value] of Object.entries(scripts)) {
				switch (name) {
					case 'necessary':
						break;

					case 'preferences':
						consentData.ad_user_data = value > 0 ? 'granted' : 'denied';
						consentData.analytics_storage = value > 0 ? 'granted' : 'denied';
						break;

					case 'marketing':
						consentData.ad_storage = value > 0 ? 'granted' : 'denied';
						consentData.ad_personalization = value > 0 ? 'granted' : 'denied';
						break;
				}
			}
			gtag('consent', 'update', consentData);
		}
	}
}
/**
 * Allow scripts
 */
function allowScripts() {
    var cookiePolicy = Cookies.getCookie('cookie_policy');
	if (cookiePolicy && cookiePolicy.length) {
		var scripts = JSON.parse(cookiePolicy);

		// Check for scripts
		if (scripts && typeof scripts == 'object') {

			// Cookie consent update
			cookieConsentUpdate(scripts);

			// Set form data
			var formDdata = new FormData();
			formDdata.append("cookie-consent-scripts", "df54s69r7e8tKH");
			
			var xhr = new XMLHttpRequest();
			xhr.withCredentials = true;
			
			// Response handler
			xhr.addEventListener("readystatechange", function() {
			  if (this.readyState === 4) {
				var result = JSON.parse(this.responseText);
					if (result.data) {
						var data = result.data;
						for (const [type, content] of Object.entries(data)) {
							if (scripts[type]) {
								if (content && content.length) {
									for (const [k, val] of Object.entries(content)) {
										var script = document.createElement('script');
											script.setAttribute('class', 'gdpr-'+k+'-'+type);
											script.setAttribute('src', val.url);
										document.head.appendChild(script);
									}
								}
							}
						}
					}
				}
			});
			
			// Send a POST request
			xhr.open('POST', '/');
			xhr.send(formDdata);
		}
	}
}

/**
 * Accept all cookies
 * @param {DOMContent} cookieConsent 
 */
function acceptAllCookies(cookieConsent, smallCconsent = null) {
	var cookieData = {};
	var inputs = cookieConsent.querySelectorAll('input[type="checkbox"]');
	inputs.forEach(function(input) {
		cookieData[input.getAttribute('name')] = 1;
	});
	Cookies.setCookie('cookie_policy', JSON.stringify(cookieData), 365);
	cookieConsent.classList.remove('d-flex');
	cookieConsent.classList.add('d-none');

	// Remove small cookie consent
	if (smallCconsent) {
		smallCconsent.classList.add('d-none');
	}

	// Remove body freez class
	var body = document.querySelector('body');
	if (body) {
		body.classList.remove('freez');
	}

	allowScripts();
}

/**
 * Reject all cookies
 * @param {DOMContent} cookieConsent 
 * @param {DOMContent} smallCconsent 
 */
function rejectAllCookies(cookieConsent = null, smallCconsent = null) {
	var cookieData = {};
	Cookies.setCookie('cookie_policy', JSON.stringify(cookieData), 365);

	// Remove cookie consent
	if (cookieConsent) {
		cookieConsent.classList.remove('d-flex');
		cookieConsent.classList.add('d-none');
	}

	// Remove small cookie consent
	if (smallCconsent) {
		smallCconsent.classList.add('d-none');
	}

	// Remove body freez class
	var body = document.querySelector('body');
	if (body) {
		body.classList.remove('freez');
	}
}

// On document load event
document.addEventListener('DOMContentLoaded', function() {

	// Detect manage button click
	var mngBtns = document.querySelectorAll('.js-manage-cookies');
	if (mngBtns && mngBtns.length) {
		mngBtns.forEach(function(mngBtn) {
			mngBtn.addEventListener('click', function() {
				var consent = document.getElementById('cookieConsent');
				if (consent) {
					consent.classList.remove('d-none');
					consent.classList.add('d-flex');

					// Remove small cookie consent
					var smallCconsent = document.getElementById('smallCookieConsent');
					if (smallCconsent) {
						smallCconsent.classList.add('d-none');
					}

					// Add body freez class
					var body = document.querySelector('body');
					if (body) {
						body.classList.add('freez');
					}
				}
			});
		});
	}

	// Detect accept button click
	var acpBtns = document.querySelectorAll('.js-accept-cookies');
	if (acpBtns && acpBtns.length) {
		acpBtns.forEach(function(acpBtn) {
			acpBtn.addEventListener('click', function() {
				var cookieConsent = document.getElementById('cookieConsent');
				var smallCconsent = document.getElementById('smallCookieConsent');
				acceptAllCookies(cookieConsent, smallCconsent);
			});
		});
	}

	// Detect reject button click
	var rjtBtns = document.querySelectorAll('.js-reject-cookies');
	if (rjtBtns && rjtBtns.length) {
		rjtBtns.forEach(function(rjtBtn) {
			rjtBtn.addEventListener('click', function() {
				var cookieConsent = document.getElementById('cookieConsent');
				var smallCconsent = document.getElementById('smallCookieConsent');
				rejectAllCookies(cookieConsent, smallCconsent);
			});
		});
	}

	// Cookie popup toggle description
	var labels = document.querySelectorAll('.cookie-consent .item .arrow');
	if (labels && labels.length) {
		labels.forEach(function(label) {
			label.addEventListener('click', function(evt) {
				evt.preventDefault();
				var parent = this.parentNode.parentNode.parentNode;
				parent.classList.toggle('extended')
			});
		});
	}

	// Show the small cookie consent
    var accepted = Cookies.getCookie('cookie_policy');
    if (!accepted) {
		var smallCconsent = document.getElementById('smallCookieConsent');
		if (smallCconsent) {
			smallCconsent.classList.remove('d-none');

			// Add body freez class
			var body = document.querySelector('body');
			if (body) {
				body.classList.add('freez');
			}
		}
    }

    // Approove selected cookies
	var cookieConsent = document.getElementById('cookieConsent');
	if (cookieConsent) {
		var btns = cookieConsent.querySelectorAll('.js-selected-cookies');
		btns.forEach(function (btn) {
			if (btn) {
				btn.addEventListener('click', function(evt) {
					evt.preventDefault();
					var cookieData = {};

					// Get values
					var chckInputs = cookieConsent.querySelectorAll('input[type="checkbox"]:checked');
					chckInputs.forEach(function (chckInput) {
						if (chckInput) {
							var name = chckInput.getAttribute('name');
							cookieData[name] = 1;
						}
					});
					Cookies.setCookie('cookie_policy', JSON.stringify(cookieData), 365);
					cookieConsent.classList.add('d-none');

					// Remove body freez class
					var body = document.querySelector('body');
					if (body) {
						body.classList.remove('freez');
					}

					allowScripts();
				});
			}
		});
	}

    // Allowcookie_policy
	allowScripts();
});
