<?php

if (!function_exists('api_curl')) {

  /**
   * Curl function used to call curl and return result in json format.
   *
   * @param string $url
   * @param string $data
   * @return string
   */
  function api_curl($url,$method = '') {
    $ch = curl_init();

    // set header for curl request
    $headers = array(
      "Cache-Control: no-cache",
      "Pragma: no-cache"
    );

    
    // set required setting with curl configuration
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

	
    // close the curl connection
    $result = curl_exec($ch);
	//echo '<pre>';print_r($result);
    curl_close($ch);
    $result = json_decode($result);

    return $result;
  }
}

if (!function_exists('_get_query_args')) {

  /**
   * get the request string data
   *
   * @return array
   */
  function _get_query_args() {

    $r = $get = $post = array();
    if (isset($_GET)) {
      $get = $_GET;
    }

    if (isset($_POST)) {
      $post = $_POST;
    }

    $r = array_merge($get, $post);

    return $r;

  }
}
