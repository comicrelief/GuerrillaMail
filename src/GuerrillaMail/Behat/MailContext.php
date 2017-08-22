<?php

namespace Comicrelief\GuerrillaMail\Behat;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
// use Comicrelief\GuerrillaMail\Services\GuerrillaMail\GuerrillaMail;
use Comicrelief\GuerrillaMail\Services\MyTempEmail\MyTempEmail;

/**
 * Class GuerrillaMailContext
 *
 * @package Comicrelief\GuerrillaMail\Behat
 */
class MailContext extends RawMinkContext implements Context {

  /**
   * @var \Comicrelief\GuerrillaMail\Services\GuerrillaMail\GuerrillaMail
   */
  private $mailer;

  /**
   * @var \Comicrelief\GuerrillaMail\Model\EmailAddressModel
   */
  private $emailAddressModel;

  /**
   * @var array
   */
  private $cachedEmails = [];

  /**
   * EmailContext constructor.
   */
  public function __construct() {
    $this->generateNewEmailAccount();
  }

  /**
   * @Then I generate a new test email address
   */
  public function iGenerateANewTestEmailAddress() {
    $this->generateNewEmailAccount();
  }

  /**
   * Fills in form field with specified id|name|label|value.
   * Example: When I fill in the "email" field with a test email address
   *
   * @Then I fill in the :arg1 field with a test email address
   */
  public function iFillInTheFieldWithATestEmailAddress($field) {
    $field = str_replace('\\"', '"', $field);
    $value = $this->emailAddressModel->getEmailAddress();
    $this->getSession()->getPage()->fillField($field, $value);
  }

  /**
   * Checks to see if an email exists with a value in the body.
   * Example: Then I should receive an email with "test" in the body
   *
   * @Then I should receive an email with :arg1 in the body
   */
  public function iShouldReceiveAnEmailWithInTheBody($arg1) {
    $this->checkEmailFieldForContents('getBody', $arg1);
  }

  /**
   * Checks to see if an email exists with a value in the subject.
   * Example: Then I should receive an email with "test" in the subject
   *
   * @Then I should receive an email with :arg1 in the subject
   */
  public function iShouldReceiveAnEmailWithInTheSubject($arg1) {
    $this->checkEmailFieldForContents('getSubject', $arg1);
  }

  /**
   * @Then I should receive an email with :arg1 in the body and :arg2 in the
   *   subject
   */
  public function iShouldReceiveAnEmailWithInTheBodyAndInTheSubject($arg1, $arg2) {
    $emailId = $this->checkEmailFieldForContents('getBody', $arg1);
    $this->checkEmailFieldForContents('getSubject', $arg2, $emailId);
  }

  /**
   * Check created email account to see if an email exists with a value in a
   * field.
   *
   * @param $fieldMethod
   * @param $contents
   * @param null $emailId
   *
   * @return mixed
   * @throws \Exception
   */
  private function checkEmailFieldForContents($fieldMethod, $contents, $emailId = NULL) {
    $loweredContents = strtolower($contents);

    for ($attempts = 0; $attempts <= 60; $attempts++) {
      // Fetch and cache the emails.
      $this->cacheEmails();

      if (count($this->cachedEmails) >= 1) {
        /** @var \Comicrelief\GuerrillaMail\Model\EmailModel $email */
        foreach ($this->cachedEmails as $email) {
          // Test to see if the contents of the field matches the defined field.
          $contentsTest = strpos(strtolower($email->{$fieldMethod}()), $loweredContents) !== false;

          // If the email is set then add extra check to make sure the email contains the id.
          if ($emailId && $contentsTest && $emailId === $email->getId()) {
            return $email->getId();
          }

          if (!$emailId && $contentsTest) {
            return $email->getId();
          }
        }
      }

      sleep(2);
    }

    throw new \Exception('Email does not exist');
  }

  /**
   * Checks and Caches emails for future testing against.
   */
  private function cacheEmails() {
    /**
     * Get the emails from guerilla mail.
     * @var \Comicrelief\GuerrillaMail\Model\EmailInboxModel $inbox
     */
    $inbox = $this->mailer->checkEmail();

    // Test to see if there are any emails in the inbox.
    if ($inbox->countEmails() >= 1) {
      // Loop through the emails and cache them/
      foreach ($inbox->getEmails() as $email) {
        $this->cachedEmails[$email->getId()] = $email;
      }
    }
  }

  /**
   * Generate a new email account.
   */
  private function generateNewEmailAccount() {
    $this->cachedEmails = [];

    /*
    $this->mailer = new GuerrillaMail();
    $this->emailAddressModel = $this->mailer->getEmailAddress();

    if ($this->emailAddressModel->getEmailAddress()) {
      return true;
    }
    */

    $this->mailer = new MyTempEmail();
    $this->emailAddressModel = $this->mailer->getEmailAddress();

    return true;
  }
}
