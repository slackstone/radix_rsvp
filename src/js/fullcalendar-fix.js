(function ($, Drupal) {
  // Log a message to the console when the script is loaded
  console.log('fullcalendar-fix.js is loaded and running!');

  Drupal.behaviors.fullcalendarFix = {
    attach: function (context, settings) {
      // Wait for the DOM to be ready
      $(document).ready(function () {
        console.log('Document is ready');

        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
          console.log('Calendar element found, initializing FullCalendar...');

          // Initialize FullCalendar
          var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: drupalSettings.calendarEvents, // Assuming events are passed via drupalSettings

            // Ensure FullCalendar re-renders on window resize
            windowResize: function(view) {
              console.log('Window resized, re-rendering FullCalendar');
              calendar.render();
            }
          });

          // Render the calendar for the first time
          calendar.render();
          console.log('FullCalendar is rendered for the first time');

          // Listen for tab clicks (even if tabsactivate doesn't fire)
          $('a.ui-tabs-anchor').on('click', function() {
            console.log('Tab clicked: ', $(this).attr('href'));
          });

          // Handle jQuery UI tabs: detect when the tab is activated
          $('#wall-calendar').closest('.ui-tabs').on('tabsactivate', function(event, ui) {
            console.log('Tabs activated');
            var newPanel = ui.newPanel.attr('id');  // Get the newly activated tab's panel ID

            if (newPanel === 'wall-calendar') {
              console.log('Wall Calendar tab is activated, re-rendering FullCalendar');
              calendar.render();  // Re-render FullCalendar when the "Wall Calendar" tab is activated
            }
          });

          // Extra fix for mobile: re-render FullCalendar after scrolling
          $(window).on('scroll', function () {
            if (window.location.hash === '#wall-calendar') {
              console.log('Scroll detected: re-rendering calendar');
              calendar.render();
            }
          });
        } else {
          console.log('Calendar element not found.');
        }
      });
    }
  };
})(jQuery, Drupal);
