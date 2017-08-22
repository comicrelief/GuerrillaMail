<?php

namespace Comicrelief\GuerrillaMail\Services;

/**
 * Interface MailInterface
 *
 * @package Comicrelief\GuerrillaMail\Services
 */
interface MailInterface
{

  /**
   * @param string $lang
   *
   * @return mixed
   */
  public function getEmailAddress($lang = 'en');

  /**
   * @return mixed
   */
  public function checkEmail();
}
