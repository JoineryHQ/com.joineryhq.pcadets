CRM.$(function($) {
  $('.pcadets-listing-donors-viewmore').click(function(e){
    e.preventDefault();
    if (!$(this).hasClass('is-active')) {
      $(this).addClass('is-active').find('span').text(ts('Hide Sponsors'));
      $(this).prev().slideDown();
    } else {
      $(this).removeClass('is-active').find('span').text(ts('View Sponsors'));
      $(this).prev().slideUp();
    }
  });

  $('input#pcadets-hide-previous-dates').click(function(e) {
    if ($(this).prop('checked')) {
      $('table#pcadets-listing-table').addClass('pcadets-hide-past-dates');
    }
    else {
      $('table#pcadets-listing-table').removeClass('pcadets-hide-past-dates');
    }
  });
});
