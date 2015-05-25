<?php

if (!class_exists('HelsingborgGalleryYoutubeWrapper')) {

    class HelsingborgGalleryYoutubeWrapper {

        static protected $_apiKey = 'AIzaSyAK4m-Yqi12k0CsfCwc5S0av_JK9gJ-4uE';

        /**
         * Gets video information for videos with specified id's
         * @param  string $id Comma seperated id(s)
         * @return object     The video info
         */
        public static function getVideos($id) {
            // If id is posted, use that
            if (isset($_POST['id'])) $id = $_POST['id'];

            /**
             * The endpoint url
             * @var string
             */
            $endpointUrl = 'https://www.googleapis.com/youtube/v3/videos';
            $response = self::request('GET', $endpointUrl, array(
                'part' => 'snippet',
                'id'   => $id
            ));

            /**
             * Echo if ajax else return
             */
            if (isset($_POST['action'])) {
                header('Content-Type: application/json; charset=UTF-8');
                echo $response;
                exit;
            } else {
                return json_decode($response);
            }
        }

        /**
         * Curl request
         * @param  string $type Request method
         * @param  string $url  The request url
         * @param  array $data  The data (post-data or get-data)
         * @return json string
         */
        public function request($type, $url, $data = NULL, $contentType = 'json') {
            $arguments = null;
            $data['key'] = self::$_apiKey;
            $data['alt'] = 'json';

            switch (strtoupper($type)) {

                /**
                 * Method: GET
                 */
                case 'GET':
                    // Append $data as querystring to $url
                    if (is_array($data)) {
                        $url .= '?' . http_build_query($data);
                    }

                    // Set curl options for GET
                    $arguments = array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HEADER         => false,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_URL            => $url
                    );

                    break;

                /**
                 * Method: POST
                 */
                case 'POST':
                    // Set curl options for POST
                    $arguments = array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL            => $url,
                        CURLOPT_USERAGENT      => 'Helsingborg.se',
                        CURLOPT_REFERER        => 'http://www.helsingborg.se',
                        CURLOPT_POST           => count($data),
                        CURLOPT_POSTFIELDS     => http_build_query($data)
                    );

                    break;
            }

            $ch = curl_init();
            curl_setopt_array($ch, $arguments);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

    }

}