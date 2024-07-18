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

            // Show the body once the header is set up correctly
            $('body').addClass('body-visible');
        }
    }

    // Set up a Mutation Observer to watch for changes in the deepest right navigation ul elements
    const observer = new MutationObserver(function(mutationsList, observer) {
        for (const mutation of mutationsList) {
            if (mutation.type === 'childList') {
                // Run splitNavigation once navigation items are detected
                splitNavigation();
                // Disconnect the observer once the initial items are loaded
                observer.disconnect();
                break;
            }
        }
    });

    // Select the deepest ul elements within right-navigation
    const rightNavContainers = document.querySelectorAll('.right-navigation ul:not(:has(ul))');
    rightNavContainers.forEach(container => {
        observer.observe(container, { childList: true });
    });

    // Also trigger splitNavigation on resize
    $(window).resize(splitNavigation);

    // Try initial split on document ready
    splitNavigation();
});
