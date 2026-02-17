// Example class
let _this = (module.exports = {
  // Set the dom elements
  dom: {
    body: document.querySelector("body"),
  },

  // Set variables
  vars: {
    flag: false,
  },

  hamburger: function () {
    const hamburger = document.querySelector(".hamburger");
    const socialIcons = document.querySelectorAll(".social-media-icons");
    const openSubmenu = document.querySelector(".menu-item-has-children");
    const body = document.body;

    if (!hamburger) return;

    hamburger.addEventListener("click", () => {
      const isOpen = body.classList.toggle("menu-open");

      if (!isOpen && openSubmenu) {
        openSubmenu.classList.remove("submenu-open");
      }
    });

    socialIcons.forEach((icon) => {
      icon.addEventListener("click", () => {
        body.classList.remove("menu-open");
        if (openSubmenu) {
          openSubmenu.classList.remove("submenu-open");
        }
      });
    });
  },

  submenu: function () {
    const openSubmenu = document.querySelector(".menu-item-has-children");
    if (!openSubmenu) return;

    openSubmenu.addEventListener("click", (e) => {
      e.stopPropagation();
      openSubmenu.classList.toggle("submenu-open");
    });
  },

  /**
   * Init functions
   */
  init: function () {
    this.hamburger();
    this.submenu();
  },
});
