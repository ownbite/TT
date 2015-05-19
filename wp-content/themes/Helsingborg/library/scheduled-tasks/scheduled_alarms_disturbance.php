<?php

if (!class_exists('HbgScheduledAlarmsDisturbance')) {

    class HbgScheduledAlarmsDisturbance {

        public $existingDisturbances;
        public $modifiedDisturbances = array();
        public $newsDir;
        public $disturbanceDir;

        /**
         * Creates pages for all samll and large disturbances
         * @return [type] [description]
         */
        public function createAlarmPages() {
            // Get existing alarms, disturbance folder and news folder
            $this->getExistingAlarms();
            $this->getDisturbanceFolders();

            // Create or update alarm pages
            $this->createAlarmPagesSmall();
            $this->createAlarmPagesBig();

            // Remove unwanted pages
            $this->removeTurnedOffAlarms();
        }

        /**
         * Gets the existing alarms from db and attaches the result to $this->exstingDisturbances
         * @return void
         */
        public function getExistingAlarms() {
            /**
             * Get existing disturbances
             */
            $queryArgs = array(
                'post_type' => 'any',
                'meta_query' => array(
                    array(
                        'key' => 'alarm_id',
                        'value' => '0',
                        'compare' => '>'
                    )
                )
            );

            $disturbances = new WP_Query($queryArgs);
            $disturbances = $disturbances->posts ?: array();
            $arrDisturbances = array();

            foreach ($disturbances as $disturbance) {
                $alarm_id = get_post_meta($disturbance->ID, 'alarm_id')[0];
                $arrDisturbances[$alarm_id] = $disturbance;
            }

            $this->existingDisturbances = $arrDisturbances;
        }

        /**
         * Gets the pages/posts which represent the folders of news and disturbances
         * @return void
         */
        public function getDisturbanceFolders() {
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>HÄMTA KATALOGER</strong><br>";
            /**
             * Get news dir
             */
            $newsDir = get_option('helsingborg_news_root');
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Katalog-id för nyhetskatalog: " . $newsDir . "<br>";

            if ($newsDir == 0) echo "Nyhetskatalog hittades inte<br>";
            $this->newsDir = get_post($newsDir);

            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Katalog-namn för nyhetskatalog: " . $this->newsDir->post_title . "<br>";

            /**
             * Get disturbance dir
             */
            $disturbanceDir = get_option('helsingborg_big_disturbance_root');
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Katalog-id för storstörning: " . $disturbanceDir . "<br>";

            // If no disturbance dir was found, return
            if ($disturbanceDir == 0) echo "Katalog för storstörning hittades inte<br>";

            $this->disturbanceDir = get_post($disturbanceDir);
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Katalog-namn för storstörning: " . $this->disturbanceDir->post_title . "<br>";

            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>HÄMTA KATALOGER SLUT</strong><br><br>";
        }

        /**
         * Create page for small disturbance
         * @return void
         */
        public function createAlarmPagesSmall() {
            global $wpdb;
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>LILLSTÖRNINGAR</strong><br>";

            /**
             * Get small disturbances and news directory page
             */
            $smallDisturbances = $this->getSmallDisturbances();
            $pageId = null;

            /**
             * Loop disturbances and create articles
             */
            if (count($smallDisturbances) > 0) {
                foreach ($smallDisturbances as $disturbance) {
                    // Check if already exists

                    $exists = array_key_exists($disturbance->IDnr, $this->existingDisturbances);
                    $post = ($exists) ? $this->existingDisturbances[$disturbance->IDnr] : null;

                    // Set the pages parameters
                    $page = array(
                        'post_content'   => $this->formatPageContent($disturbance->MoreInfo),
                        'post_name'      => sanitize_title('alarm-' . $disturbance->HtText),
                        'post_title'     => $disturbance->HtText,
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'post_author'    => 1,
                        'post_parent'    => $this->newsDir->ID,
                        'post_excerpt'   => $disturbance->MoreInfo,
                        'comment_status' => 'closed'
                    );

                    // If page already exist, add ID to update
                    if ($post) {
                        $page['ID'] = $post->ID;
                        if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "o Sida för lillstörning fanns redan, uppdaterar den: " . $page['post_title'] . "<br>";
                    } else {
                        if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "+ Skapar sida för lillstörning: " . $page['post_title'] . "<br>";
                    }

                    // Create/update page
                    $pageId = wp_insert_post($page, true);
                    add_post_meta($pageId, 'alarm_id', $disturbance->IDnr, true);
                    $this->modifiedDisturbances[] = $disturbance->IDnr;

                    if (!$post || $post->post_parent == $this->disturbanceDir->ID) {
                    //if ("hej" == "hej") {
                        /**
                         * Add to news list widget
                         */

                        if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "++ Skapar list-nod för lillstörning: " . $page['post_title'] . "<br>";

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
                        $linkListWidgetNew = array();

                        foreach ($linkListWidget as $key => $value) {
                            if (substr($key, 0, 4) != 'item') {
                                $linkListWidgetNew[$key] = $value;
                            }
                        }

                        $nextKey = 1;
                        $linkListWidgetNew['item' . $nextKey] = $disturbance->HtText;
                        $linkListWidgetNew['item_link' . $nextKey] = get_permalink($pageId);
                        $linkListWidgetNew['item_class' . $nextKey] = "";
                        $linkListWidgetNew['item_target' . $nextKey] = "";
                        $linkListWidgetNew['item_warning' . $nextKey] = "on";
                        $linkListWidgetNew['item_info' . $nextKey] = "";
                        $linkListWidgetNew['item_id' . $nextKey] = $pageId;
                        $linkListWidgetNew['item_date' . $nextKey] = "";
                        $linkListWidgetNew['amount'] = $linkListWidget['amount']+1;

                        $lastNode = 1;
                        foreach ($linkListWidget as $key => $value) {
                            if (substr($key, 0, 4) == 'item') {
                                // Find key
                                preg_match_all('/item([0-9])+/', $key, $matches);
                                if (isset($matches[1][0])) $lastNode++;

                                // Add values to key
                                $stringKey = preg_replace('/[0-9]+/', '', $key);
                                $linkListWidgetNew[$stringKey . $lastNode] = $value;
                            }
                        }

                        unset($pageWidgets[$linksWidgetID]);
                        $pageWidgets[$linksWidgetID] = $linkListWidgetNew;

                        update_option($pageWidgetIdentifier, $pageWidgets);
                    }
                }
            } else {
                if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Fanns inga lillstörningar i databasen, inga sidor skapade.<br>";
            }

            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>LILLSTÖRNINGAR SLUT</strong><br><br>";
        }

        /**
         * Create page for big disturbance
         * @return void
         */
        public function createAlarmPagesBig() {
            global $wpdb;
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>STORSTÖRNINGAR</strong><br>";

            /**
             * Get big disturbances, news directory page and disturbance page
             */
            $bigDisturbances = $this->getBigDisturbances();

            /**
             * Loop disturbances and create articles that we need
             */
            if (count($bigDisturbances) > 0) {
                foreach ($bigDisturbances as $disturbance) {

                    // Check if post with same name and parent already exist in big disturbance "folder"
                    $exists = array_key_exists($disturbance->IDnr, $this->existingDisturbances);
                    $post = ($exists) ? $this->existingDisturbances[$disturbance->IDnr] : null;

                    // If no page exists since earlier create one (storstörning)
                    $page = array(
                        'post_content'   => $this->formatPageContent($disturbance->MoreInfo, $disturbance->Comment),
                        'post_title'     => $disturbance->HtText,
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'post_author'    => 1,
                        'post_parent'    => $this->disturbanceDir->ID,
                        'post_excerpt'   => '<a href="' . get_permalink($pageId) . '">Läs mer</a>',
                        'comment_status' => 'closed'
                    );

                    // If post already exist, add id to perform an update instead of insert
                    if ($post) {
                        $page['ID'] = $post->ID;
                        if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "o Sidan för storstörning finns redan, uppdaterar den: " . $page['post_title'] . "<br>";
                    } else {
                        if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "+ Skapade sida för storstörning: " . $page['post_title'] . "<br>";
                    }

                    $pageId = wp_insert_post($page);
                    add_post_meta($pageId, 'alarm_id', $disturbance->IDnr, true);
                    $this->modifiedDisturbances[] = $disturbance->IDnr;
                }
            } else {
                if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "Fanns inga storstörningar i databasen, inga sidor skapade.<br>";
            }

            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>STORSTÖRNINGAR SLUT</strong><br><br>";
        }

        public function removeTurnedOffAlarms() {
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>INAKTUELLA ALARM</strong><br>";
            foreach ($this->existingDisturbances as $key => $disturbance) {
                if (!in_array($key, $this->modifiedDisturbances)) {
                    wp_delete_post($disturbance->ID);
                    if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "- Tog bort: " . $disturbance->post_title . "<br>";
                }
            }
            if (isset($_GET['dist']) && $_GET['dist'] == 'debug') echo "<strong>INAKTUELLA ALARM SLUT</strong><br>";
        }

        /**
         * Formats the html content of the page
         * @param  string $moreInfo More info content
         * @param  string $comment  Comment content
         * @return string           Formatted content
         */
        public function formatPageContent($moreInfo = null) {
            $content = '';
            if (strlen($moreInfo)) $content .= '<p>' . nl2br($moreInfo) . '</p>';
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