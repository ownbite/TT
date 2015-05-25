<?php
/*
Template Name: Alarm-sök
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
                <?php endwhile; // End the loop ?>

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
                        <div class="clearfix"></div>
                    </form><!-- /.event-list-form -->
                </div><!-- /.form-container -->

                <div class="list-container">
                    <h2 class="section-title">Din sökning</h2>
                    <div class="divider fade">
                        <div class="upper-divider"></div>
                        <div class="lower-divider"></div>
                    </div>

                    <div class="Pager" id="alarm-pager-top">
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
                    <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageEvents}" class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>

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
                    <div id="alarmModal" class="reveal-modal modal modal-alarm" data-reveal>
                        <h2 class="section-title">Alarm</h2>

                        <div class="divider fade">
                            <div class="upper-divider"></div>
                            <div class="lower-divider"></div>
                        </div>

                        <h1 class="main-title">Brand i byggnad Flerfamiljshus Lägenhet</h1>

                        <div class="row">
                            <div class="small-12">
                                <ul class="modal-item-list">
                                    <li>
                                        <span class="item-label modalDateHeader">Tidpunkt:</span>
                                        <span class="item-value modalDate">2014-12-01 18:52:53</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalEventHeader">Händelse:</span>
                                        <span class="item-value modalEvent">Brand i byggnad Flerfamiljshus Lägenhet</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalStationHeader">Station:</span>
                                        <span class="item-value modalStation">M220Contal;</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalIDHeader">Ärendeid:</span>
                                        <span class="item-value modalID">141201C0025</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalStateHeader">Larmnivå:</span>
                                        <span class="item-value modalState">Larm, Nivå 1</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalAddressHeader">Adress:</span>
                                        <span class="item-value modalAddress">Kopparmöllegatan 26, 2</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalLocationHeader">Plats:</span>
                                        <span class="item-value modalLocation">Helsingborg</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalAreaHeader">Insatsområde:</span>
                                        <span class="item-value modalArea">M21M</span>
                                    </li>
                                    <li>
                                        <span class="item-label modalMoreInfoHeader">Kompletterande information:</span>
                                        <span class="item-value modalMoreInfo"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <a class="close-reveal-modal">&#215;</a>
                    </div>
                    <!-- END MODAL -->

                    <script type="text/javascript">
                        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                        var adminIDs = '<?php echo $administration_unit_ids; ?>';
                    </script>

                    <script type="text/html" id="eventTemplate">
                        <li>
                            <ul class="mini-item-list">
                                <li>
                                    <span class="item-label">Datum och tid</span><span class="item-value" data-bind="text: SentTime">2014-12-11 08:15</span>
                                </li>
                                <li>
                                    <span class="item-label">H&auml;ndelse</span><a class="item-value modal-link" title="link-title" data-bind="attr: {id: IDnr}, text: HtText" data-reveal-id="alarmModal" desc="link-desc">Trafikolycka - singel Personbil Övrigt</a>
                                </li>
                                <li>
                                    <span class="item-label">Adress</span><span class="item-value" title="link-title" data-bind="text: Address">Djurhagsvägen</span>
                                </li>
                                <li>
                                    <span class="item-label">Station</span><span class="item-value" title="link-title" data-bind="text: Station">Bårslöv</span>
                                </li>
                                <li>
                                    <span class="item-label">Kommun</span><span class="item-value" title="link-title" data-bind="text: Place">M85T</span>
                                </li>
                            </ul>
                        </li>
                    </script>

                    <script src="<?php echo get_template_directory_uri() ; ?>/js/alarm-list-page.js"></script>

                    <?php
                        if ((is_active_sidebar('content-area') == TRUE)) {
                            dynamic_sidebar("content-area");
                        }
                    ?>

                </div>
                <!-- END LIST + BLOCK puffs :-) -->
            </div><!-- /.columns -->
        </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
                <div class="row">
                    <?php
                        if ((is_active_sidebar('left-sidebar-bottom') == TRUE)) {
                            dynamic_sidebar("left-sidebar-bottom");
                        }
                    ?>
                </div>
            </div>

            <?php
                if ( (is_active_sidebar('content-area-bottom') == TRUE)) {
                    dynamic_sidebar("content-area-bottom");
                }
            ?>
        </div>
    </div> <!-- /.main-area -->

    <?php get_template_part('templates/partials/sidebar-right'); ?>

</div><!-- /.article-page-layout -->

<?php get_footer(); ?>
