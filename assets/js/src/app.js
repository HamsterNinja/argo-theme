$('.btn-hamburger').click(function() {    
        $('.mobile-menu').addClass('active');
    });
    $('.btn-hamburger.active').click(function() {    
        $('.mobile-menu').removeClass('active');
    });
$('.popular-slick').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 3,
    arrows: false,
});
$('.menu-slick').not('.slick-initialized').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
});
$('.choice-button').click(function (event) {
    event.preventDefault();
    $('.choice-button').removeClass('active');
    $(this).addClass('active');

    var id = $(this).attr('data-id');
    if (id) {
        $('.single_menu-tabs-content:visible').fadeOut(0, function () {
            $('.single_menu-tabs').find('#' + id).fadeIn('slow', function () {
                $('.menu-slick').slick('reinit');
            });
        });
    }
});