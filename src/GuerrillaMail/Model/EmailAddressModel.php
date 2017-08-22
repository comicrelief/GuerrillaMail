<?php

namespace Comicrelief\GuerrillaMail\Model;

/**
 * Class EmailAddressModel
 *
 * @package Comicrelief\GuerrillaMail
 */
class EmailAddressModel {

  /**
   * @var string
   */
  private $emailAddress;

  /**
   * @var int
   */
  private $created;

  /**
   * @var string
   */
  private $sid;

  /**
   * @var string
   */
  private $inboxHash;

  /**
   * @return mixed
   */
  public function getEmailAddress() {
    return $this->emailAddress;
  }

  /**
   * @param string $emailAddress
   */
  public function setEmailAddress($emailAddress) {
    $this->emailAddress = $emailAddress;
  }

  /**
   * @return mixed
   */
  public function getCreated() {
    return $this->created;
  }

  /**
   * @param int $created
   */
  public function setCreated($created) {
    $this->created = $created;
  }

  /**
   * @return mixed
   */
  public function getAlias() {
    return $this->alias;
  }

  /**
   * @return mixed
   */
  public function getSid() {
    return $this->sid;
  }

  /**
   * @param string $sid
   */
  public function setSid($sid) {
    $this->sid = $sid;
  }

  /**
   * @return string
   */
  public function getInboxHash() {
    return $this->inboxHash;
  }

  /**
   * @param string $inboxHash
   */
  public function setInboxHash($inboxHash) {
    $this->inboxHash = $inboxHash;
  }
}
