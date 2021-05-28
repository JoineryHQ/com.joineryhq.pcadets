CRM.$(function($) {
  $(document).ready(function(){
    $('.pcadets-listing-donors-viewmore').click(function(e){
      e.preventDefault();
      if (!$(this).hasClass('is-active')) {
        $(this).addClass('is-active').find('span').text('Hide Sponsors');
        $(this).prev().slideDown();
      } else {
        $(this).removeClass('is-active').find('span').text('View Sponsors');
        $(this).prev().slideUp();
      }
    });
  });
});
