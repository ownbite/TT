<?php /* Generates the page tree, skips drafted pages */
require_once(get_template_directory() . '/library/helsingborg-walker.php');
$walker_page = new Helsingborg_Walker(); ?>
<nav class="main-nav large-12 columns show-for-medium-up">
  <ul class="main-nav-list">
    <li class="nav-home"><a href="<?php echo get_site_url();?>">Hem</a></li>
    <?php wp_list_pages( array('title_li' => '', 'walker' => $walker_page, 'child_of' => get_option('page_on_front') )); ?>
  </ul>
</nav>
