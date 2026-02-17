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

  onSectionInView(selector, callback, options = {}) {
    const elements = document.querySelectorAll(selector);
    if (!elements.length) return;

    const observerOptions = {
      root: null, // viewport
      threshold: 0,
      rootMargin: "0px 0px -20% 0px",
      ...options,
    };

    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          callback(entry.target, entry);

          // If you only want to trigger once per element, uncomment:
          obs.unobserve(entry.target);
        }
      });
    }, observerOptions);

    elements.forEach((el) => observer.observe(el));
    return observer;
  },

  /**
   * Init functions
   */
  init: function () {
    _this.onSectionInView(".animated", (el) => {
      const className = "in-view";

      if (!el.classList.contains(className)) {
        el.classList.add(className);
      }
    });
  },
});
