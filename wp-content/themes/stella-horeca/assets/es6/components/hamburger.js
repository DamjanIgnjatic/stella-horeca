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
    const navItems = document.querySelectorAll(".theme-menu-content");
    const socialIcons = document.querySelectorAll(".social-media-icons");
    const body = document.body;

    console.log(hamburger);

    hamburger.addEventListener("click", () => {
      body.classList.toggle("menu-open");
    });

    navItems.forEach((item) => {
      item.addEventListener("click", () => {
        body.classList.remove("menu-open");
      });
    });

    socialIcons.forEach((icon) => {
      icon.addEventListener("click", () => {
        body.classList.remove("menu-open");
      });
    });
  },

  /**
   * Init functions
   */
  init: function () {
    this.hamburger();
  },
});
