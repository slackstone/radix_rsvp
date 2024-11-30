/**
 * @file
 * Customization of navigation for RSVP theme.
 */

((Drupal, once) => {
  Drupal.behaviors.rsvpNavigation = {
    attach(context, settings) {
      if (document.body.classList.contains('path-frontpage')) {
        console.log('navigation.js is loaded and running on the front page!');

        // Get elements
        const triggerElement = document.getElementById('scroll-trigger'); // New trigger element
        const invertLogo = document.querySelector('#rsvp_logo_invert');
        const siteNameContainer = document.getElementById('rsvp_site_name_container');
        const stickyHeader = document.getElementById('sticky-header');
        const defaultLogo = document.getElementById('rsvp_logo_default');
        const defaultSiteName = document.getElementById('rsvp_site_name_default');
        const invertSiteName = document.getElementById('rsvp_site_name_invert');

        // Debugging to check if each element is found
        if (!triggerElement || !invertLogo || !defaultLogo || !siteNameContainer || !stickyHeader || !defaultSiteName || !invertSiteName) {
          console.error('One or more elements are missing from the page.');
          return;
        }

        // Initially, show only the default logo and site name, and set opacity for smooth transitions
        invertLogo.style.opacity = '0';
        defaultLogo.style.opacity = '1';
        invertSiteName.style.opacity = '0';
        defaultSiteName.style.opacity = '1';

        function handleScroll() {
          const triggerTop = triggerElement.getBoundingClientRect().top;

          // Check if the trigger element has reached the top of the viewport
          if (triggerTop <= 0) {
            // Activate sticky behavior
            stickyHeader.classList.add('sticky-active'); // Apply sticky class for behavior

            // Smoothly fade out the default logo and fade in the inverted logo
            defaultLogo.style.opacity = '0';
            invertLogo.style.opacity = '1';

            // Smoothly fade out the default site name and fade in the inverted site name
            defaultSiteName.style.opacity = '0';
            invertSiteName.style.opacity = '1';
            // Set background color to 'rgba(0, 44, 0, 0.3)'
            // defaultSiteName.style.backgroundColor = 'rgba(0, 44, 0, 0.3)';
            // invertSiteName.style.backgroundColor = 'rgba(0, 44, 0, 0.3)';

          } else {
            // Deactivate sticky behavior
            stickyHeader.classList.remove('sticky-active'); // Remove sticky class

            // Smoothly fade out the inverted logo and fade in the default logo
            defaultLogo.style.opacity = '1';
            invertLogo.style.opacity = '0';

            // Smoothly fade out the inverted site name and fade in the default site name
            defaultSiteName.style.opacity = '1';
            invertSiteName.style.opacity = '0';
          }
        }

        // Attach the scroll event listener
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Initial check when the page loads
      }
    }
  };
})(Drupal, once);
