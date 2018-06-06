( function($) {

  /**
   * Match Height (Including Safari onload fix)
   */
  function startMatchHeight() {
    $('.footermatch').matchHeight();
    $('.slide').matchHeight();
    $('.matchheight').matchHeight();
    $('.contactformheight').matchHeight();
    $('.contact-detailsheight').matchHeight();
  }
  window.onload = startMatchHeight;

  $('.slider_main').bxSlider({
  infiniteLoop: true,
  controls: false,
  minSlides: 1,
  maxSlides: 1,
  auto: true,
  speed: 1500,
  pause: 5000,
  });

  $('.secondary_slider').bxSlider({
  infiniteLoop: true,
  controls: false,
  minSlides: 1,
  maxSlides: 1,
  auto: false,
  speed: 1500,
  pause: 5000,
  });

} ) (jQuery);

function openNav() {
    document.getElementById("myNav").style.height = "100%";
}

function closeNav() {
    document.getElementById("myNav").style.height = "0%";
}
