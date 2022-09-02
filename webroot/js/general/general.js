$('.open-menu').on('click', function () {
    $('.menu-column').addClass('showUp');
});
$('.close-menu').on('click', function () {
    $('.menu-column').removeClass('showUp');

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()

})
