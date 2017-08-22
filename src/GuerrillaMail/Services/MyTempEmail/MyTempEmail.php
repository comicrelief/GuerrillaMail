<?php

namespace Comicrelief\GuerrillaMail\Services\MyTempEmail;

use Comicrelief\GuerrillaMail\Model\EmailAddressModel;
use Comicrelief\GuerrillaMail\Model\EmailInboxModel;
use Comicrelief\GuerrillaMail\Model\EmailModel;
use Comicrelief\GuerrillaMail\Services\MailInterface;

class MyTempEmail implements MailInterface {

  /**
   * @var string
   */
  private $baseUrl = 'https://api.mytemp.email/1/';

  /**
   * @var EmailAddressModel
   */
  private $emailModel;

  /**
   * @var int
   */
  private $sid;

  /**
   * MyTempEmail constructor.
   */
  public function __construct() {
    $this->sid = mt_rand(1000000,9000000);
  }

  /**
   * @param string $lang
   *
   * @return bool|\Comicrelief\GuerrillaMail\Model\EmailAddressModel
   */
  public function getEmailAddress($lang = 'en') {
    $query = $this->get('inbox/create', 'sid=' . $this->sid . '&task=3&tt=6');

    if ($query['status'] === 'error') {
      return false;
    }

    $response = $query['data'];

    $emailModel = new EmailAddressModel();

    $emailModel->setEmailAddress($response['inbox']);
    $emailModel->setCreated($response['ts']);
    $emailModel->setSid($this->sid);
    $emailModel->setInboxHash($response['inbox_hash']);

    $this->emailModel = $emailModel;

    return $emailModel;
  }

  /**
   * @return \Comicrelief\GuerrillaMail\Model\EmailInboxModel
   */
  public function checkEmail() {

    $params = array(
      'inbox' => $this->emailModel->getEmailAddress(),
      'inbox_hash' => $this->emailModel->getInboxHash(),
      'task' => '9&tt=30'
    );

    $query = $this->get('inbox/check', http_build_query($params));

    $messages = $query['data']['emls'];

    $inbox = new EmailInboxModel();
    $inbox->setEmailAddressModel($this->emailModel);

    foreach ($messages as $message) {

      $messageParams = array(
        'eml' => $message['eml'],
        'eml_hash' => $message['eml_hash'],
        'task' => '3&tt=3'
      );

      $messageQuery = $this->get('eml/get', http_build_query($messageParams));
      $messageData = $messageQuery['data'];

      $emailModel = new EmailModel();
      $emailModel->setId($message['eml']);
      $emailModel->setHash($message['eml_hash']);
      $emailModel->setSubject($messageData['subject']);
      $emailModel->setBody($messageData['body_text']);
      $emailModel->setFrom($messageData['from_address']);
      $emailModel->setTimestamp($messageData['ts']);

      $inbox->addEmail($emailModel);
    }

    return $inbox;
  }

  /**
   * @param $action
   * @param $params
   *
   * @return array
   */
  private function get($action, $params) {
    $url = $this->baseUrl . $action . '?' . $params;

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
}
