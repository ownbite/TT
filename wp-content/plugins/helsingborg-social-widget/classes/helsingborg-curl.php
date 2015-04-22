<?php

if (!class_exists('HbgCurl')) {
    class HbgCurl {
        /**
         * Curl request
         * @param  string $type Request method
         * @param  string $url  The request url
         * @param  array $data  The data (post-data or get-data)
         * @return json string
         */
        public static function request($type, $url, $data = NULL, $contentType = 'json') {
            $arguments = null;

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