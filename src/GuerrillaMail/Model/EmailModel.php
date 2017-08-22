<?php

namespace Comicrelief\GuerrillaMail\Model;

/**
 * Class EmailModel
 *
 * @package Comicrelief\GuerrillaMail\Model
 */
class EmailModel {

  /**
   * @var
   */
  private $id;

  /**
   * @var
   */
  private $hash;

  /**
   * @var
   */
  private $subject;

  /**
   * @var
   */
  private $body;

  /**
   * @var
   */
  private $from;

  /**
   * @var
   */
  private $timestamp;

  /**
   * @return mixed
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getHash() {
    return $this->hash;
  }

  /**
   * @param mixed $hash
   */
  public function setHash($hash) {
    $this->hash = $hash;
  }

  /**
   * @return mixed
   */
  public function getSubject() {
    return $this->subject;
  }

  /**
   * @param mixed $subject
   */
  public function setSubject($subject) {
    $this->subject = $subject;
  }

  /**
   * @return mixed
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * @param mixed $body
   */
  public function setBody($body) {
    $this->body = $body;
  }

  /**
   * @return mixed
   */
  public function getFrom() {
    return $this->from;
  }

  /**
   * @param mixed $from
   */
  public function setFrom($from) {
    $this->from = $from;
  }

  /**
   * @return mixed
   */
  public function getTimestamp() {
    return $this->timestamp;
  }

  /**
   * @param mixed $timestamp
   */
  public function setTimestamp($timestamp) {
    $this->timestamp = $timestamp;
  }
}
