webpackHotUpdate(0,[
/* 0 */,
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$('.popular-slick').slick({
  infinite: true,
  slidesToShow: 3,
  slidesToScroll: 3,
  arrows: false
});
$('.menu-slick').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true
});
$('.choice-button').click(function (event) {
  event.preventDefault();
  $('.choice-button').removeClass('active');
  $(this).addClass('active');

  var id = $(this).attr('data-id');
  if (id) {
    $('.doors-content-inner:visible').fadeOut(0);
    $('.doors-content').find('#' + id).fadeIn('slow');
  }
});

/***/ })
])