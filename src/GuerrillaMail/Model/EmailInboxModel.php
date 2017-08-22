<?php

namespace Comicrelief\GuerrillaMail\Model;

/**
 * Class EmailInboxModel
 *
 * @package Comicrelief\GuerrillaMail\Model
 */
class EmailInboxModel {

  /**
   * @var EmailAddressModel
   */
  private $emailAddressModel;

  /**
   * @var EmailModel[]
   */
  private $emails;

  /**
   * @return EmailAddressModel
   */
  public function getEmailAddressModel() {
    return $this->emailAddressModel;
  }

  /**
   * @param EmailAddressModel $emailAddressModel
   */
  public function setEmailAddressModel(EmailAddressModel $emailAddressModel) {
    $this->emailAddressModel = $emailAddressModel;
  }

  /**
   * @return int
   */
  public function countEmails() {
    return count($this->emails);
  }

  /**
   * @return EmailModel[]
   */
  public function getEmails() {
    return $this->emails;
  }

  /**
   * @param EmailModel $emailModel
   */
  public function addEmail(EmailModel $emailModel) {
    $this->emails[] = $emailModel;
  }
}
