(function() {
    // Check for theme switcher cookie and change the theme's main CSS depending on its value
    var cookieName = 'theme_style';
    var style = getCookie(cookieName) ? getCookie(cookieName) : 'light';
    if (style) {
        changeTheme(style);
        changeMetaColor(style);

        // On DOM Document loaded
        document.addEventListener('DOMContentLoaded', function() {
            changeSwitcherButton(style == 'dark' ? 'light' : 'dark');

            // Remove default meta color
            var metaColor = document.querySelector('#meta-color');
            if (metaColor && typeof metaColor == 'object') {
                metaColor.remove();
            }

            // Remove default windows meta color
            var windowsMetaColor = document.querySelector('#windows-meta-color');
            if (windowsMetaColor && typeof windowsMetaColor == 'object') {
                windowsMetaColor.remove();
            }

            // Remove default safari meta color
            var safariMetaColor = document.querySelector('#safari-meta-color');
            if (safariMetaColor && typeof safariMetaColor == 'object') {
                safariMetaColor.remove();
            }
        });
    }

    /**
     * Change the theme switcher button
     * @param {String} style 
     */
    function changeSwitcherButton(style) {
        // Create HTML Dome elements
        var btn = document.createElement('button');
            btn.setAttribute('class', 'switcher-button');
            btn.setAttribute('title', 'Switch the theme style');
            btn.addEventListener('click', function() {
                changeSwitcherButton(style == 'dark' ? 'light' : 'dark');
                changeTheme(style);
                changeMetaColor(style);
                setCookie(cookieName, style, 365);
            });
        var icon = document.createElement('i');
            icon.setAttribute('class', 'fas fa-lightbulb switcher-icon');

        var btnContainer = document.querySelector('#switcher-button-container');
        if (btnContainer && typeof btnContainer == 'object') {
            switch (style) {
                case 'dark':
                    icon.setAttribute('class', 'fas fa-moon switcher-icon');
                    break;
            }
            btn.appendChild(icon);
            var oldChild = btnContainer.querySelector('button');
            if (oldChild) {
                btnContainer.replaceChild(btn, oldChild);
            } else {
                btnContainer.appendChild(btn);

            }
        }
    }

    /**
     * Change the sites style by the style variable value
     * @param {String} style 
     */
    function changeTheme(style) {
        document.documentElement.setAttribute('data-theme', style == 'dark' ? 'dark' : '');
    }

    /**
     * Change meta color by variable value
     * @param {String} style 
     */
    function changeMetaColor(style) {
        var themeColor = style == 'dark' ? '#222222' : '#f9f9f9';
        var insert = false;

        // Chrome, Firefox OS and Opera
        var metaColor = document.querySelector('#metaColor');
        if (metaColor && typeof MetaColor == 'object') {
            metaColor.setAttribute('content', themeColor);
        } else {
            metaColor = document.createElement('meta');
            metaColor.setAttribute('name', 'theme-color');
            metaColor.setAttribute('content', themeColor);
            metaColor.setAttribute('id', 'metaColor');
            insert = true;
        }

        // Windows Phone
        var windowsMetaColor = document.querySelector('meta[name="msapplication-navbutton-color"]');
        if (windowsMetaColor && typeof windowsMetaColor == 'object') {
            windowsMetaColor.setAttribute('content', themeColor);
        } else {
            windowsMetaColor = document.createElement('meta');
            windowsMetaColor.setAttribute('name', 'msapplication-navbutton-color');
            windowsMetaColor.setAttribute('content', themeColor);
            windowsMetaColor.setAttribute('id', 'windowsMetaColor');
            insert = true;
        }

        // iOS Safari
        var safariMetaColor = document.querySelector('meta[name="apple-mobile-web-app-status-bar-style"]');
        if (safariMetaColor && typeof safariMetaColor == 'object') {
            safariMetaColor.setAttribute('content', themeColor);
        } else {
            safariMetaColor = document.createElement('meta');
            safariMetaColor.setAttribute('name', 'apple-mobile-web-app-status-bar-style');
            safariMetaColor.setAttribute('content', themeColor);
            safariMetaColor.setAttribute('id', 'safariMetaColor');
            insert = true;
        }

        // Insert meta tag
        if (insert) {
            var head = document.querySelector('head');
            if (head && typeof head == 'object') {
                head.appendChild(metaColor);
                head.appendChild(windowsMetaColor);
                head.appendChild(safariMetaColor);
            }
        }
    }

    /**
     * Chack for cookie by name and return its value or false
     * @param {String} name 
     */
    function getCookie(name) {
        var b = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
        return b ? b.pop() : false;
    }

    /**
     * Set cookie function
     * @param {String} cname 
     * @param {String} cvalue 
     * @param {Integer} exdays 
     */
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
})()
