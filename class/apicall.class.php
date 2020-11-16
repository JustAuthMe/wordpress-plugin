<?php

defined('ABSPATH') || exit;

class ApiCall {
    private $method = 'GET';
    private $url = null;
    private $post_params = null;
    private $custom_header = null;
    private $trust_any_ssl = false;
    private $json_content = false;
    private $response_header = null;
    private $response_body = null;
    private $error_info = null;

    /**
     * @param array $header
     * @return array
     */
    private function parseResponseHeader($header) {
        $headers = ['Status' => $header[0]];
        unset($header[0]);

        foreach ($header as $value) {
            $split = explode(': ', $value);
            $headers[$split[0]] = $split[1];
        }

        return $headers;
    }

    /**
     * @return ApiCall
     */
    public function exec() {
        if (function_exists('curl_version')) {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            switch ($this->method) {
                case 'GET':
                    break;
                case 'POST':
                    curl_setopt($ch, CURLOPT_POST, true);
                    break;
                default:
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
            }

            if ($this->post_params !== null) {
                $postdata = $this->json_content ? json_encode($this->post_params) : http_build_query($this->post_params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            }


            $header = $this->custom_header ? $this->custom_header : [];
            if ($this->json_content) {
                $header = array_merge($header, ['Content-Type: application/json']);
            }

            if (!empty($header)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }

            if ($this->trust_any_ssl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }

            $result = curl_exec($ch);

            if ($result !== false) {
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($result, 0, $header_size);
                $body = substr($result, $header_size);

                $header = str_replace("\r\n", "\n", trim($header));
                $header = str_replace("\r", "\n", $header);
                $header = explode("\n", $header);

                $this->response_header = $this->parseResponseHeader($header);
                $this->response_body = $body;
            } else {
                $errno = curl_errno($ch);
                $this->error_info = curl_strerror($errno);
            }

            curl_close($ch);
        } else {
            $opts = ['http' => [
                'method' => $this->method,
                'ignore_errors' => true
            ]];

            if ($this->post_params !== null) {
                $postdata = $this->json_content ? json_encode($this->post_params) : http_build_query($this->post_params);
                $opts['http']['content'] = $postdata;
            }

            if ($this->custom_header !== null) {
                $opts['http']['header'] = ($this->json_content ? 'Content-Type: application/json' . "\n" : '') .
                    implode("\n", $this->custom_header);
            }

            $context = stream_context_create($opts);
            $result = @file_get_contents($this->url, false, $context);

            if ($result !== false) {
                $this->response_header = $this->parseResponseHeader($http_response_header);
                $this->response_body = $result;
            } else {
                $this->error_info = error_get_last()['message'];
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param string $method
     * @return ApiCall
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return ApiCall
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getPostParams() {
        return $this->post_params;
    }

    /**
     * @param array|null $post_params
     * @return ApiCall
     */
    public function setPostParams($post_params) {
        $this->post_params = $post_params;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getCustomHeader() {
        return $this->custom_header;
    }

    /**
     * @param array|null $custom_headers
     * @return ApiCall
     */
    public function setCustomHeader($custom_headers) {
        $this->custom_header = $custom_headers;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTrustingAnySsl() {
        return $this->trust_any_ssl;
    }

    /**
     * @return bool
     */
    public function isJsonContent() {
        return $this->json_content;
    }

    /**
     * @return ApiCall
     */
    public function jsonContent() {
        $this->json_content = true;
        return $this;
    }

    /**
     * @return ApiCall
     */
    public function trustAnySsl() {
        $this->trust_any_ssl = true;
        return $this;
    }

    /**
     * @return array
     */
    public function responseHeader() {
        return $this->response_header;
    }

    /**
     * @return int
     */
    public function responseCode() {
        return (int) substr($this->responseHeader()['Status'], 9, 3);
    }

    /**
     * @return string|null
     */
    public function responseText() {
        return $this->response_body;
    }

    /**
     * @return stdClass|null
     */
    public function responseObj() {
        return json_decode($this->response_body);
    }

    /**
     * @return array|null
     */
    public function responseArray() {
        return json_decode($this->response_body, true);
    }

    /**
     * @return string|null
     */
    public function errorInfo() {
        return $this->error_info;
    }
}
