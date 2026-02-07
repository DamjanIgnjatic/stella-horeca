// Example class
let _this = (module.exports = {

    // Set the dom elements
    dom: {},

    // Set variables
    vars: {},

    /**
     * TInit functions
     */
    init: function() {
        _this = this;
    },

    /**
     * Set cookie
     * @param {String} name 
     * @param {String} value 
     * @param {Integer} days 
     */
    setCookie: function(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }

        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    },

    /**
     * Get cookie by name
     * @param {String} name 
     * @returns mixed
     */
    getCookie: function(name) {
        var nameEQ = name + '=',
            ca = document.cookie.split(';');

        for (var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }

        return null;
    },

    /**
     * Erase cookie by name
     * @param {String} name 
     */
    eraseCookie: function(name) {   
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
});
