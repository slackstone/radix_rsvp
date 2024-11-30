document.addEventListener('DOMContentLoaded', function() {
    // Get the toggler button and the text span
    const navbarToggler = document.getElementById('navbar-toggler');
    const togglerText = navbarToggler.querySelector('.navbar-toggler-text');

    // Get the collapsible menu element
    const navbarMenu = document.getElementById('navbar-menu');

    // Add event listeners to handle menu open/close events
    navbarMenu.addEventListener('show.bs.collapse', function() {
      // When the menu is expanded, set text to "Close"
      togglerText.textContent = 'Close';
    });

    navbarMenu.addEventListener('hide.bs.collapse', function() {
      // When the menu is collapsed, set text back to "Menu"
      togglerText.textContent = 'Menu';
    });
  });
