(function ($) {
  $(document).ready(function () {
    $('html, body').addClass('flat_preloader')
  });

  $(window).load(function () {
    $('#th_preloader').fadeOut();
    $('html, body').removeClass('flat_preloader')
  })
})(jQuery)