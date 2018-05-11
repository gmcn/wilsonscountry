( function($) {

  /**
   * Match Height (Including Safari onload fix)
   */
  function startMatchHeight() {
    $('.footermatch').matchHeight();
  }
  window.onload = startMatchHeight;

} ) (jQuery);
