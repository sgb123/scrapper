<?php
/**
 * Class WebRequest
 */
class WebRequest
{
    /**
     * @var string
     */
    public $response;
    /**
     * @var resource
     */
    private $curl;
    /**
     * @var string
     */
    private $cookieFile;

    public function __construct()
    {
        $this->cookieFile = tempnam(sys_get_temp_dir(), 'web_request');
        touch($this->cookieFile);
    }

    public function __destruct()
    {
        unlink($this->cookieFile);
    }

    /**
     * @param string $url
     * @param bool $ajax
     * @return string
     */
    public function get($url, $ajax = false)
    {
        $this->initCurl($url, $ajax);
        $this->processCurl();
        return $this->response;
    }

    /**
     * @param string $url
     * @param array $params
     * @param bool $ajax
     * @return string
     */
    public function post($url, $params, $ajax = false)
    {
        $this->initCurl($url, $ajax);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($params));
        $this->processCurl();
        return $this->response;
    }

    /**
     * @param string $url
     * @param bool $ajax
     */
    private function initCurl($url, $ajax = false)
    {
        $this->curl = curl_init($url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        if ($ajax) {
            $headers[] = 'X-Requested-With: XMLHttpRequest';
        }
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, $this->cookieFile);
    }

    private function processCurl()
    {
        $this->response = curl_exec($this->curl);
        curl_close($this->curl);
    }
}