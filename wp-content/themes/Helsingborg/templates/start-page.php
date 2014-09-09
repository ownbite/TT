<?php
/*
Template Name: Startsida
*/
get_header(); ?>
<div class="row">
  <div class="small-12 large-12 columns" role="main">
    <div class="small-8 large-8 columns" role="main">
      <div class="small-12 large-12 columns" role="main">
          <ul id="featured1" data-orbit data-options="timer_speed:5000;">
            <li>
              <img src="http://foundation.zurb.com/docs/assets/img/examples/satelite-orbit.jpg" alt=""/>
              <div class="orbit-caption">
                Caption One. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
              </div>
            </li>
            <li>
              <img src="http://foundation.zurb.com/docs/assets/img/examples/andromeda-orbit.jpg" alt=""/>
              <div class="orbit-caption">
                Caption Two. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
              </div>
            </li>
            <li>
              <img src="http://foundation.zurb.com/docs/assets/img/examples/launch-orbit.jpg" alt=""/>
              <div class="orbit-caption">
                Caption Three. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
              </div>
            </li>
          </ul>
        </div>
      <div class="small-12 large-12 columns" role="main">
          <div class="small-8 large-8 columns" role="main">
            <?php dynamic_sidebar("news-listing-start-page"); ?>
          </div>
          <div class="small-4 large-4 columns" role="main">
              <?php dynamic_sidebar("sidebar-widgets"); ?>
          </div>
      </div>
    </div>
    <div class="small-4 large-4 columns" role="main">
      <?php dynamic_sidebar("sidebar-widgets"); ?>
    </div>
  </div>

  </div>
</div>

<?php get_footer(); ?>
