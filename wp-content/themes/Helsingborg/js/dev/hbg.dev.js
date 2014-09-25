/* HBG dev JS - To merged into app.js, minified with grunt */
jQuery(document).foundation({

});


jQuery(document).ready(function(){

 $('.show-support-nav').bind('click', function(){
      $('.support-nav-list').toggle();
      $(this).toggleClass('active');
  });

  $('.show-mobile-nav').bind('click', function(){
  	 $(this).toggleClass('active');
  });
  $('.exit-off-canvas').bind('click', function(){
      if($('.show-mobile-nav').hasClass('active')) {
        $('.show-mobile-nav').removeClass('active');
      }
  });

  $('.show-mobile-search').bind('click', function(e){
  		$('.mobile-search').toggle();
      e.preventDefault();
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
      //$('.table-list').tablesorter();
    }

});
