// Include vertical slider
const BackgroundImage = require('./components/background-image');
BackgroundImage.init();

// Include modal functionality
const Modal = require('./components/modal');
Modal.init();

// // Example modal call
// document.addEventListener('DOMContentLoaded', function() {
// 	Modal.fill('Test title', '<u>Test body text<br/>...</b>');
// 	Modal.show();
// });

// Include vertical slider
const Accordions = require('./components/accordions');
Accordions.init();

// Image gallery
const ImageGallery = require('./components/image-gallery');
ImageGallery.init();

/**
 * On content loaded
 */
document.addEventListener('DOMContentLoaded', function() {
	// Menu toggle
	var menuToggle = document.getElementById('menuToggle');
	if (menuToggle) {
		menuToggle.addEventListener('click', function() {
			var menu = document.getElementById('navbar');
			if (menu) {
				// Toggle class
				if (menu.classList.contains('open')) {
					menu.classList.remove('open');
				} else {
					menu.classList.add('open');
				}

				// Add click lisrtener to the menu-toggle button
				menu.removeEventListener('click', toggleNavigation, true);
				menu.addEventListener('click', toggleNavigation, true);

				function toggleNavigation(evt) {
					if (evt.target.classList.contains('theme-menu-wrapper')) {
						menu.classList.remove('open');
					}
				}
			}
		});
	}

	// Add smooth scrolling when clicking any anchor link
	document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
		anchor.addEventListener('click', function (evt) {
			evt.preventDefault();
			var href = this.getAttribute('href');
			if (href && href != '#') {
				document.querySelector(href).scrollIntoView({
					behavior: 'smooth'
				});
			}
		});
	});

	// Dropdown menu
	var menuItems = document.querySelectorAll('.theme-menu-wrapper .menu-item-has-children, .theme-menu-wrapper .menu-item-has-children > a');
	if (menuItems && menuItems.length > 0) {
		menuItems.forEach(function(menuItem) {
			menuItem.addEventListener('click', function(evt) {
				switch (this.tagName) {
					case 'A':
						evt.preventDefault();
						break;
				
					case 'LI':

						if (this.classList.contains('open')) {
							this.classList.remove('open');
						} else {

							// // Hide all others
							// var opens = document.querySelectorAll('.menu-item-has-children.open');
							// if (opens && opens.length > 0) {
							// 	opens.forEach(function(open) {
							// 		open.classList.remove('open');
							// 	});
							// }
							this.classList.add('open');
						}
						break;
				}
			});
		});
	}

	// Dropdown button
	var dropdowns = document.querySelectorAll('.dropdown');
	if (dropdowns && dropdowns.length) {
		dropdowns.forEach(dropdown => {
			var btn = dropdown.querySelector('.dropdown-toggle'),
				menu = dropdown.querySelector('.dropdown-menu');
			if (btn && menu) {
				btn.addEventListener('click', function() {
					menu.classList.toggle('open');
				})
			}
		});

		// Close the menu if someone clicked outside
		document.addEventListener('click', function(evt) {
			if (evt.target) {
				if (!evt.target.classList.contains('dropdown-toggle')) {
					var menus = document.querySelectorAll('.dropdown-menu.open');
					if (menus && menus.length) {
						menus.forEach(menu => {
							menu.classList.remove('open');
						});
					}
				}
			}
		})
	}

	// Close dropdown menu on window resize
	document.addEventListener('resize', function() {
		var menuItems = document.querySelectorAll('.menu-item-has-children ul');
		if (menuItems && menuItems.length > 0) {
			menuItems.forEach(function (menuItem) {
				menuItem.removeAttribute('style');
			});
		}
	});

	// Close dropdown menu on window resize
	document.addEventListener('resize', function() {
		var menuItems = document.querySelectorAll('.menu-item-has-children ul');
		if (menuItems && menuItems.length > 0) {
			menuItems.forEach(function (menuItem) {
				menuItem.removeAttribute('style');
			});
		}
	});

	// On page scroll show/hide the navbar actions and the scroll to top button
	var navbar = document.getElementById('navbar'),
		btn = document.getElementById('scrollToTop'),
		lastScrollTop = 0;
	window.onscroll = function() {
		var st = document.documentElement.scrollTop || document.body.scrollTop;
		
		// Show/hide the scroll to top button
		if (btn) {
			if (st > 450) {
				btn.classList.add('d-flex');
				btn.classList.remove('d-none');
			} else {
				btn.classList.remove('d-flex');
				btn.classList.add('d-none');
			}
		}

		// Show/hide the navbar
		if (navbar) {

            // Add / remove transparency
            if (st < 15) {
				navbar.classList.remove('active');
            } else {
				navbar.classList.add('active');
            }

			// Check if the scroll top bigger than x
			if (st < 450) {
				navbar.classList.remove('hide');
				navbar.classList.add('show');
			} else {
				
				// Check the scroll direction
				if (st > lastScrollTop) {
					// Down scroll
					navbar.classList.remove('show');
					navbar.classList.add('hide');
				} else {
					// Upscroll
					navbar.classList.remove('hide');
					navbar.classList.add('show');
				}

				// For Mobile or negative scrolling
			 	lastScrollTop = st <= 0 ? 0 : st;
			}
		}
	}

	// Scroll to top function
	var scrollToTop = document.getElementById('scrollToTop');
	if (scrollToTop) {
		scrollToTop.addEventListener('click', function(evt) {
			evt.preventDefault();

			// Animated scroll
			scroll({
				top: 0,
				left: 0,
				behavior: 'smooth'
				});
		});
	}

	// Show/hide the password
	pwds = document.querySelectorAll('.js-show-hide');
	if (pwds && pwds.length) {
		pwds.forEach(pwd => {
			pwd.addEventListener('click', function() {
				var input = this.parentNode.querySelector('input');
				if (input) {
					var type = input.getAttribute('type');
					input.setAttribute('type', type == 'password' ? 'text' : 'password');
				}
			});
		});
	}
});
