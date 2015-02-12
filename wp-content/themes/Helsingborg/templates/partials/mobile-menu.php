<?php /* Generates the page tree, skips drafted pages */
require_once(get_template_directory() . '/library/helsingborg-walker.php');
$walker_page = new Helsingborg_Walker(); ?>
<aside class="left-off-canvas-menu">
  <ul class="mobile-nav-list" role="navigation">
    <li class="nav-home"><a href="<?php echo get_site_url();?>">Hem</a></li>
    <?php wp_list_pages( array('title_li' => '', 'walker' => $walker_page, 'child_of' => get_option('page_on_front') )); ?>
  </ul>
</aside>
