<?php

if (!class_exists('HbgScheduledAlarmsDisturbance')) {

    class HbgScheduledAlarmsDisturbance {

        /**
         * Creates pages for all samll and large disturbances
         * @return [type] [description]
         */
        public function createAlarmPages() {
            $this->createAlarmPagesSmall();
            $this->createAlarmPagesBig();
        }

        /**
         * Create page for small disturbance
         * @return void
         */
        public function createAlarmPagesSmall() {
            global $wpdb;
            /**
             * Get small disturbances and news directory page
             */
            $smallDisturbances = $this->getSmallDisturbances();
            $newsDir = get_page_by_title('nyhetskatalog');
            $pageId = null;

            /**
             * Loop disturbances and create articles
             */
            foreach ($smallDisturbances as $disturbance) {
                // Check if already exists
                $post = get_page_by_title($disturbance->HtText);

                // Set the pages parameters
                $page = array(
                    'post_content'   => $this->formatPageContent($disturbance->MoreInfo, $disturbance->Comment),
                    'post_name'      => sanitize_title('alarm-' . $disturbance->HtText),
                    'post_title'     => $disturbance->HtText,
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'post_author'    => 1,
                    'post_parent'    => $newsDir->ID,
                    'post_excerpt'   => $disturbance->MoreInfo,
                    'comment_status' => 'closed'
                );

                // If page already exist, add ID to update
                if ($post->ID > 0 && $post->post_parent == $newsDir->ID) {
                    $page['ID'] = $post->ID;
                }

                // Create/update page
                $pageId = wp_insert_post($page, true);

                if (!$post) {
                    /**
                     * Add to news list widget
                     */

                    // Get startpage id and widget details
                    $startpage = get_page_by_title('startsida');
                    $widgets = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_sidebars_widgets' AND post_id = $startpage->ID", OBJECT);
                    $widgets = unserialize($widgets->meta_value);
                    $linksWidgetID = null;

                    // Find correct widget
                    foreach ($widgets['content-area'] as $key => $value) {
                        if (strpos($value, 'simplelinklistwidget') > -1) {
                            $linksWidgetID = $value;
                            break;
                        }
                    }

                    $linksWidgetID = explode('-', $linksWidgetID)[1];

                    $pageWidgetIdentifier = "widget_{$startpage->ID}_simplelinklistwidget";

                    $pageWidgets = get_option($pageWidgetIdentifier);
                    $linkListWidget = $pageWidgets[$linksWidgetID];

                    $nextKey = array_intersect_key($linkListWidget, array_flip(preg_grep('/^item_link/', array_keys($linkListWidget))));
                    $nextKey = count($nextKey) + 1;

                    $linkListWidget['item' . $nextKey] = $disturbance->HtText;
                    $linkListWidget['item_link' . $nextKey] = get_permalink($pageId);
                    $linkListWidget['item_class' . $nextKey] = "";
                    $linkListWidget['item_target' . $nextKey] = "";
                    $linkListWidget['item_warning' . $nextKey] = "on";
                    $linkListWidget['item_info' . $nextKey] = "";
                    $linkListWidget['item_id' . $nextKey] = $pageId;
                    $linkListWidget['item_date' . $nextKey] = "";
                    $linkListWidget['amount'] = $nextKey;

                    unset($pageWidgets[$linksWidgetID]);
                    $pageWidgets[$linksWidgetID] = $linkListWidget;

                    update_option($pageWidgetIdentifier, $pageWidgets);
                }
            }
        }

        /**
         * Create page for big disturbance
         * @return void
         */
        public function createAlarmPagesBig() {
            global $wpdb;
            /**
             * Get big disturbances, news directory page and disturbance page
             */
            $bigDisturbances = $this->getBigDisturbances();
            $newsDir = get_page_by_title('nyhetskatalog');
            $disturbanceDir = get_page_by_title('storstörning');

            /**
             * Loop disturbances and create articles that we need
             */
            foreach ($bigDisturbances as $disturbance) {

                // Check if post with same name and parent already exist
                $post = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent = $newsDir->ID AND post_title = '$disturbance->HtText' AND post_status = 'publish'", OBJECT);
                $post = (isset($post[0])) ? $post[0] : NULL;

                // Set the pages parameters
                $page = array(
                    'post_content'   => $this->formatPageContent($disturbance->MoreInfo, $disturbance->Comment),
                    'post_title'     => $disturbance->HtText,
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'post_author'    => 1,
                    'post_parent'    => $newsDir->ID,
                    'post_excerpt'   => $disturbance->MoreInfo,
                    'comment_status' => 'closed'
                );

                // If page already exist, add ID to update
                if (isset($post) && $post->ID > 0) $page['ID'] = $post->ID;

                // Create/update page
                $pageId = wp_insert_post($page);

                // Check if post with same name and parent already exist
                $post = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_parent = $disturbanceDir->ID AND post_title = '$disturbance->HtText' AND post_status = 'publish'", OBJECT);
                $post = (isset($post[0])) ? $post[0] : NULL;

                // If no page exists since earlier create one (storstörning)
                if (!$post) {
                    $page = array(
                        'post_content'   => '<p><a href="' . get_permalink($pageId) . '">Läs mer</a></p>',
                        'post_title'     => $disturbance->HtText,
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'post_author'    => 1,
                        'post_parent'    => $disturbanceDir->ID,
                        'post_excerpt'   => '<a href="' . get_permalink($pageId) . '">Läs mer</a>',
                        'comment_status' => 'closed'
                    );

                    wp_insert_post($page);
                }
            }
        }

        /**
         * Formats the html content of the page
         * @param  string $moreInfo More info content
         * @param  string $comment  Comment content
         * @return string           Formatted content
         */
        public function formatPageContent($moreInfo = null, $comment = null) {
            $content = '';

            if (strlen($moreInfo)) $content .= '<p>' . nl2br($moreInfo) . '</p>';
            if (strlen($comment)) $content .= '<p>' . nl2br($comment) . '</p>';

            return $content;
        }

        /**
         * Gets all small disturbances from the database
         * @return object
         */
        public function getSmallDisturbances() {
            global $wpdb;
            return $wpdb->get_results("
                SELECT DISTINCT
                    a.CaseId,
                    a.IDnr,
                    a.SentTime,
                    a.PresGrp,
                    a.HtText,
                    a.Address,
                    a.AddressDescription,
                    a.Name,
                    a.Zone,
                    a.Position,
                    a.Comment,
                    a.MoreInfo,
                    a.Place,
                    a.BigDisturbance,
                    a.SmallDisturbance,
                    a.ChangeDate,
                    a.Station,
                    a.Cities
                FROM
                    alarm_alarms a
                WHERE
                    a.SmallDisturbance = 'true'
                ORDER BY a.SentTime DESC
            ", OBJECT);
        }

        /**
         * Gets all big disturbances from the database
         * @return object
         */
        public function getBigDisturbances() {
            global $wpdb;
            return $wpdb->get_results("
                SELECT DISTINCT
                    a.CaseId,
                    a.IDnr,
                    a.SentTime,
                    a.PresGrp,
                    a.HtText,
                    a.Address,
                    a.AddressDescription,
                    a.Name,
                    a.Zone,
                    a.Position,
                    a.Comment,
                    a.MoreInfo,
                    a.Place,
                    a.BigDisturbance,
                    a.SmallDisturbance,
                    a.ChangeDate,
                    a.Station,
                    a.Cities
                FROM
                    alarm_alarms a
                WHERE
                    a.BigDisturbance = 'true'
                ORDER BY a.SentTime DESC
            ", OBJECT);
        }

    }

}