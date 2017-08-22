<?php

namespace Comicrelief\GuerrillaMail\Services\GuerrillaMail;

/**
 * Class CurlConnection
 *
 * @package Comicrelief\GuerrillaMail\GuerrillaConnect
 */
class CurlConnection {

  /**
   * GuerrillaMail api endpoint.
   *
   * @var string
   */
  private $url = 'http://api.guerrillamail.com/ajax.php';

  /**
   * Client IP Address
   *
   * @var string
   */
  private $ip = "127.0.0.1";

  /**
   * Client Agent
   *
   * @var string
   */
  private $agent = "GuerrillaMail_Library";

  /**
   * Format query string for GuerrillaMail API consumption.
   *
   * @param $action
   * @param array $options
   * @return string
   */
  public function build_query($action, array $options)
  {
    $query = "f={$action}";
    foreach($options as $key => $value)
    {
      if(!is_array($value))
      {
        $query .= "&{$key}=" . urlencode($value);
        continue;
      }

      foreach($value as $a_key => $a_value)
      {
        $query .= "&{$key}%5B%5D=" . urlencode($a_value);
      }
    }

    return $query;
  }

  /**
   * HTTP GET using cURL
   *
   * @param string $action
   * @param array $query
   *
   * @return array|mixed
   */
  public function retrieve($action, array $query) {
    $url = $this->url . '?' . $this->build_query($action, $query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);

    $response = json_decode($output, TRUE);

    $data = [];
    switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
      case 200:
        $data['status'] = 'success';
        $data['data'] = $response;
        break;
      default:
        $data['status'] = 'error';
        $data['message'] = $response;
        break;
    }

    curl_close($ch);
    return $data;
  }

  /**
   * HTTP POST using cURL
   *
   * @param string $action
   * @param array $query
   *
   * @return array|mixed
   */
  public function transmit($action, array $query) {
    $url = $this->url;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($action, $query));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);

    $response = json_decode($output, TRUE);

    $data = [];
    switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
      case 200:
        $data['status'] = 'success';
        $data['data'] = $response;
        break;
      default:
        $data['status'] = 'error';
        $data['message'] = $response;
        break;
    }

    curl_close($ch);
    return $data;
  }
}
