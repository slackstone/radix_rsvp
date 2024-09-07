/**
 * @file
 * Customization of navigation.
 */

((Drupal, once) => {
  // Define the behavior for the navigation
  Drupal.behaviors.oliveroNavigation = {
    attach(context, settings) {
      if (document.body.classList.contains('path-frontpage')) {
        console.log('navigation.js is loaded and running on the front page!');

        const navbar = document.querySelector('.navbar'); // Select the navbar element
        const targetElement = document.getElementById('target-element'); // Element to monitor
        const defaultLogo = document.getElementById('default-logo'); // Select the default logo (light logo)
        const alternativeLogo = document.getElementById('alternative-logo'); // Select the alternative logo (dark logo)
        const toolbar = document.getElementById('toolbar-bar'); // Select the admin toolbar

        /**
         * Toggles the 'scrolled' class on the navbar and swaps the logos based on the scroll position.
         */
        function handleScroll() {
          if (targetElement) {
            const toolbarHeight = toolbar ? toolbar.offsetHeight : 0;
            const elementTop = targetElement.getBoundingClientRect().top - toolbarHeight;

            if (elementTop <= 0) {
              navbar.classList.add('scrolled'); // Add 'scrolled' class
              if (defaultLogo && alternativeLogo) {
                defaultLogo.style.display = 'none';
                alternativeLogo.style.display = 'block';
              }
            } else {
              navbar.classList.remove('scrolled'); // Remove 'scrolled' class
              if (defaultLogo && alternativeLogo) {
                defaultLogo.style.display = 'block';
                alternativeLogo.style.display = 'none';
              }
            }
          } else {
            console.log('Target element not found!');
          }
        }

        /**
         * Adjusts the top position of the navbar to compensate for the admin toolbar.
         */
        function adjustNavbarPosition() {
          const toolbarHeight = toolbar ? toolbar.offsetHeight : 0;
          navbar.style.top = toolbarHeight + 'px';
        }

        // Wait until the document is fully loaded to calculate heights
        document.addEventListener('DOMContentLoaded', () => {
          adjustNavbarPosition(); // Initial check on page load
        });

        // Attach the scroll event listener and perform the initial check
        console.log('Setting up scroll event listener...');
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Initial check on page load

        // Adjust the navbar position if the admin toolbar is present
        console.log('Adjusting navbar position for admin toolbar...');
        adjustNavbarPosition(); // Initial check on page load
        window.addEventListener('resize', adjustNavbarPosition); // Adjust on window resize
      }
    }
  };
})(Drupal, once);
