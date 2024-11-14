jQuery(document).ready(function($) {
    let $originalNavItems = null;
    let splitIndex = 0;

    function saveOriginalNavItems() {
        const $rightNav = $('.right-navigation ul:not(:has(ul))');
        if ($rightNav.length && !$originalNavItems) {
            $originalNavItems = $rightNav.children().clone();
            splitIndex = Math.ceil($originalNavItems.length / 2);
        }
    }

    function splitNavigation() {
        saveOriginalNavItems();

        const $leftNav = $('.left-navigation ul:not(:has(ul))');
        const $rightNav = $('.right-navigation ul:not(:has(ul))');

        if ($originalNavItems && $leftNav.length && $rightNav.length) {
            const windowWidth = window.innerWidth;

            // Clear both navigations
            $leftNav.empty();
            $rightNav.empty();

            if (windowWidth > 781) {
                // Append the first half to the left navigation
                $originalNavItems.slice(0, splitIndex).appendTo($leftNav);

                // Append the second half to the right navigation
                $originalNavItems.slice(splitIndex).appendTo($rightNav);
            } else {
                // Append all items to the right navigation
                $originalNavItems.appendTo($rightNav);
            }

            // Check the number of original navigation items and add class if there are 6 items
            if ($originalNavItems.length === 6) {
                $('body').addClass('nav-six');
            } else {
                $('body').removeClass('nav-six');
            }

            // Show the body once the header is set up correctly
            $('body').addClass('body-visible');
        }
    }

    setTimeout(splitNavigation, 200);

    // Also trigger splitNavigation on resize
    $(window).resize(splitNavigation);
});
