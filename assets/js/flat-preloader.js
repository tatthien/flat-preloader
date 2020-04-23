(function ($) {
  const $overlay = $('#flat-preloader-overlay');

  $(window).load(function () {
    let delayTime = flatPreloader.delayTime
    $overlay.delay(delayTime).fadeOut();

    setTimeout(function() {
      $('html, body').removeClass('flat-preloader-active')
    }, delayTime);

    setTimeout(function() {
      $overlay.remove()
    }, 4000);
  });
})(jQuery)
