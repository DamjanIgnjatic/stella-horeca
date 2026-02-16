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
    const body = document.body;
    

    hamburger.addEventListener("click", () => {
      body.classList.toggle("menu-open");
    });

    

    socialIcons.forEach((icon) => {
      icon.addEventListener("click", () => {
        body.classList.remove("menu-open");
      });
    });
  },

  submenu: function(){
    const openSubmenu=document.querySelector(".menu-item-has-children");

    openSubmenu.addEventListener("click", () =>{
      openSubmenu.classList.toggle("submenu-open");


    })

  },

  /**
   * Init functions
   */
  init: function () {
    this.hamburger();
    this.submenu();
  },


});
