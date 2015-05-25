<?php
/*
Template Name: Evenemang
*/

get_header();

// Get all AdministrationUnitID -> so we can
$administration_unit_ids = $_GET['q'];
if(!$administration_unit_ids) { $administration_unit_ids = 0; }

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
?>

<div class="article-page-layout row">
    <div class="main-area large-9 columns">
        <div class="main-content row">

            <?php get_template_part('templates/partials/sidebar-left'); ?>

            <div class="large-8 medium-8 columns">
                <div class="alert row"></div>
                <?php get_template_part('templates/partials/header','image'); ?>

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div>

                <?php the_breadcrumb(); ?>

                <?php /* Start loop */ ?>
                <?php while (have_posts()) : the_post(); ?>
                <article class="article" id="article">
                    <header>
                        <?php get_template_part('templates/partials/accessability','menu'); ?>
                        <h1 class="article-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (!empty($content)) : ?>
                        <div class="ingress">
                            <?php echo apply_filters('the_content', $main); ?>
                        </div>
                    <?php endif; ?>

                    <div class="article-body">
                        <?php
                            if (!empty($content)) {
                                echo apply_filters('the_content', $content);
                            } else {
                                echo apply_filters('the_content', $main);
                            }
                        ?>
                    </div>

                    <footer>
                        <?php get_template_part('templates/partials/share'); ?>
                    </footer>
                </article>
                <?php endwhile; ?>

                <div class="form-container">
                    <form class="list-form">
                        <!-- ko foreach: filter.filters -->
                            <!-- ko if: (Type == 'select') -->
                            <div class="input-column">
                                <div>
                                    <span data-bind="text: Name"></span>:
                                    <select id="municipality_multiselect" data-bind="options: Options, optionsText: 'Name', optionsValue: 'ID', value='CurrentOption'"></select>
                                </div>
                            </div>
                            <!-- /ko -->

                            <!-- ko if: (Type == 'text') -->
                            <div class="input-column">
                                <span data-bind="text: Name"></span>:
                                <input type="text" class="input-text" data-bind="value: Value, valueUpdate: 'afterkeydown'" />
                            </div>
                            <!-- /ko -->

                            <!-- ko if: (Type == 'calendar') -->
                            <div class="input-column-container">
                                <div class="input-column input-column-half">
                                    <span data-bind="text: Name"></span>
                                    <input type="text" class="input-calendar" data-bind="value: Value, valueUpdate: 'afterkeydown', attr: {id: CalendarID}" />
                                </div>
                            </div>
                            <!-- /ko -->
                        <!-- /ko -->

                        <div class="input-column" style="padding-top: 1rem;">
                            <input type="checkbox" onclick="updateEvents(this)"></input>
                            <span>Alla Helsingborgs evenemang</span>
                        </div>

                        <input type="text" id="selectedTypes" style="display: none;" data-bind="textInput: selectedEventTypes"/>
                    </form>
                </div>

                <div class="list-container">
                    <h2 class="section-title">Din sökning</h2>
                    <div class="divider fade">
                        <div class="upper-divider"></div>
                        <div class="lower-divider"></div>
                    </div>

                    <div class="Pager" id="event-pager-top">
                        <!-- ko if: pager.maxPageIndex() > 0 -->
                        <ul class="pagination" role="menubar" aria-label="Pagination">
                            <li class="arrow"><a href="#" data-bind="click: pager.movePrevious, enable: pager.currentPageIndex() > 0">&laquo; Föregående</a></li>

                            <!-- ko foreach: pager.pagerPages() -->
                            <li data-bind="css: $parent.pager.currentStatus($data-1), visible: $parent.pager.isHidden($index())">
                                <a href="#" data-bind="text: ($data), click: function(data, event) { $parent.pager.changePageIndex($data-1) }"></a>
                            </li>
                            <!-- /ko -->

                            <li class="arrow"><a href="#" data-bind="click: pager.moveNext, enable: pager.currentPageIndex() < pager.maxPageIndex()">Nästa &raquo;</a></li>
                        </ul>
                        <!-- /ko -->
                    </div>
                    <div class="event-list-loader" id="loading-event" style="margin-top:10px;position:relative;"></div>
                    <div class="NoEvents" id="no-event"></div>

                    <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageEvents}" class="event-list block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>

                    <div class="Pager" id="event-pager-bottom">
                        <!-- ko if: pager.maxPageIndex() > 0 -->
                        <ul class="pagination" role="menubar" aria-label="Pagination">
                            <li class="arrow"><a href="#" data-bind="click: pager.movePrevious, enable: pager.currentPageIndex() > 0">&laquo; Föregående</a></li>

                            <!-- ko foreach: pager.pagerPages() -->
                            <li data-bind="css: $parent.pager.currentStatus($data-1), visible: $parent.pager.isHidden($index())">
                                <a href="#" data-bind="text: ($data), click: function(data, event) { $parent.pager.changePageIndex($data-1) }"></a>
                            </li>
                            <!-- /ko -->

                            <li class="arrow"><a href="#" data-bind="click: pager.moveNext, enable: pager.currentPageIndex() < pager.maxPageIndex()">Nästa &raquo;</a></li>
                        </ul>
                        <!-- /ko -->
                    </div>

                    <!-- MODAL TEMPLATE -->
                    <div id="eventModal" class="reveal-modal modal" data-reveal>
                        <img class="modal-image"/>

                        <div class="row">
                            <div class="modal-event-info large-12 columns">
                            <h2 class="modal-title"></h2>
                            <p class="modal-description"></p>
                            <p class="modal-link-url"></p>
                            <!--<p class="modal-date"></p>-->
                            </div>
                        </div>

                        <!-- IF arrangör exist -->
                        <div class="row">
                            <div class="large-6 columns" id="event-times">
                                <h2 class="section-title">Datum, tid och plats</h2>

                                <div class="divider fade">
                                    <div class="upper-divider"></div>
                                    <div class="lower-divider"></div>
                                </div>

                                <ul class="modal-list" id="time-modal"></ul>
                            </div>

                            <div class="large-6 columns" id="event-organizers">
                                <h2 class="section-title">Arrangör</h2>

                                <div class="divider fade">
                                    <div class="upper-divider"></div>
                                    <div class="lower-divider"></div>
                                </div>

                                <ul class="modal-list" id="organizer-modal"></ul>
                            </div>
                        </div>

                        <a class="close-reveal-modal">&#215;</a>
                    </div>
                    <!-- END MODAL -->

                    <script type="text/html" id="eventTemplate">
                        <li>
                            <a class="modal-link" href="#" data-bind="attr: {id: EventID}" data-reveal-id="eventModal" desc="link-desc">
                                <!-- ko if: ImagePath -->
                                    <img data-bind="attr: {src: ImagePath}" alt="alt-text"/>
                                <!-- /ko -->

                                <!-- ko if: ImagePath == null -->
                                <img  alt="alt-text" src="<?php echo get_template_directory_uri() ; ?>/assets/img/images/event-default.jpg"/>
                                <!-- /ko -->

                                <div class="list-content-container">
                                    <p data-bind="text: Location" style="display: none;"></p>
                                    <p data-bind="text: EventTypesName" style="display: none;"></p>
                                    <h2 data-bind="text: Name" class="list-title"></h2>
                                    <span data-bind="text: Date" class="list-date"></span>
                                    <div data-bind="trimText: Description" class="list-content"></div>
                                </div>
                            </a>
                        </li>
                    </script>

                    <script type="text/javascript">
                        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                        var adminIDs = '<?php echo $administration_unit_ids; ?>';
                    </script>
                    <script src="<?php echo get_template_directory_uri() ; ?>/js/event-list-page.js"></script>
                    <?php
                        if ( (is_active_sidebar('content-area') == TRUE) ) {
                            dynamic_sidebar("content-area");
                        }
                    ?>

                </div><!-- .event-list-container -->
                <!-- END LIST + BLOCK puffs :-) -->

            </div>
        </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
                <div class="row">

                </div><!-- /.row -->
            </div><!-- /.sidebar -->

            <?php
                if ((is_active_sidebar('content-area-bottom') == TRUE)) {
                    dynamic_sidebar("content-area-bottom");
                }
            ?>

        </div><!-- /.lower-content -->
    </div>  <!-- /.main-area -->

    <?php get_template_part('templates/partials/sidebar-right'); ?>

</div><!-- /.article-page-layout -->

<?php get_footer(); ?>
