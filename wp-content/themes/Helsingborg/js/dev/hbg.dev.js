/* HBG dev JS - To merged into app.js, minified with grunt */
$(document).foundation({

});


$(document).ready(function(){



  $('.show-support-nav').bind('click', function(){
      $('.support-nav-list').toggle();
      $(this).toggleClass('active');
  });

  $('.show-mobile-nav').bind('click', function(){
  		$('.mobile-nav-list').toggle();
      $(this).toggleClass('active');
  });
  $('.show-mobile-search').bind('click', function(){
  		$('.mobile-search').toggle();
      $(this).toggleClass('active');
  });


   if($('.table-list').length > 0) {
     
      $('.table-item').bind('click', function(){
          if($(this).not('active')) {
              $('.table-item').removeClass('active');
              $('.table-content').removeClass('open');
              $(this).addClass('active');
              $(this).next('.table-content').addClass('open');
          } else if($(this).hasClass('active')){
              $('.table-item').removeClass('active');
              $('.table-content').removeClass('open');
          }
      });

      $('.table-list tr td:last-child').append('<span class="icon"></span>');
      $('.table-list .table-item:odd').addClass('odd');
      $('.table-list').tablesorter();
    }

});

