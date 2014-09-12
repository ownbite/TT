/* HBG dev JS - To merged into app.js, minified with grunt */
$(document).foundation({
"magellan-expedition": {
  active_class: 'active', // specify the class used for active sections
  threshold: 0, // how many pixels until the magellan bar sticks, 0 = auto
  destination_threshold: 20, // pixels from the top of destination for it to be considered active
  throttle_delay: 50, // calculation throttling to increase framerate
  fixed_top: 0, // top distance in pixels assigned to the fixed element on scroll
}
});


$(document).ready(function(){

  $('.show-support-nav').bind('click', function(){
      $('.support-nav-list').toggle();
  });

  $('.show-mobile-nav').bind('click', function(){
  		$('.mobile-nav-list').toggle();
      $(this).toggleClass('active');
  });
  $('.show-mobile-search').bind('click', function(){
  		$('.mobile-search').toggle();
      $(this).toggleClass('active');
  });

});