$('.minus, .plus').click(function (e) {
    e.preventDefault();    
    var $input = $(this).siblings('.value');
    var val = parseInt($input.val(), 10);
    $input.val(val + ($(this).hasClass('minus') ? -1 : 1));
});