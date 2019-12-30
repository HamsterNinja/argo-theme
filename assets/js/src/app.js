$('.popular-slick').slick({
  infinite: true,
  slidesToShow: 3,
  slidesToScroll: 3,
  arrows: false,
});
$('.menu-slick').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: true,
});
$('.choice-button').click(function(event) {
  event.preventDefault();
  $('.choice-button').removeClass('active');
  $(this).addClass('active');
  
  var id=$(this).attr('data-id');
  if (id){
  	$('.single_menu-tabs-content:visible').fadeOut(0);
  	$('.single_menu-tabs').find('#'+id).fadeIn('slow');
	}
});