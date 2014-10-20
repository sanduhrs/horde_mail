<?php

/**
 * @file
 * Contains \Drupal\horde_smtp\Plugin\Mail\HordeSmtpMailer.
 */

namespace Drupal\horde_mail\Plugin\Mail;

use Drupal\Core\Mail\MailInterface;
use Horde_Mail_Rfc822_List;
use Horde_Mime_Headers;
use Horde_Mime_Part;
use Horde_Mail_Transport_Smtphorde;
use Horde_Mail_Exception;

/**
 * Defines a Drupal mail backend, using Horde_SMTP.
 *
 * @Mail(
 *   id = "hordemailer",
 *   label = @Translation("HordeMailer"),
 *   description = @Translation("Horde Mail Plugin.")
 * )
 */
class HordeMailer implements MailInterface {

  protected $config;

  protected $params;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->config['settings'] = \Drupal::config('horde_smtp.settings')->getRawData();
    $this->params = array(
      'host' => $this->config['settings']['host'],
      'port' => $this->config['settings']['port'],
      //'secure' => $this->config['settings']['secure'],
      'secure' => TRUE,
      //'timeout' => $this->config['settings']['timeout'],
      'username' => $this->config['settings']['username'],
      'password' => $this->config['settings']['password'],
      //'xoauth2_token' => $this->config['settings']['xoauth2_token'],
      //'debug' => $this->config['settings']['debug'],
    );
  }

  /**
   * Concatenates and wraps the email body for plain-text mails.
   *
   * @param array $message
   *   A message array, as described in hook_mail_alter().
   *
   * @return array
   *   The formatted $message.
   */
  public function format(array $message) {
    // Join the body array into one string.
    $message['body'] = implode("\n\n", $message['body']);
    // Convert any HTML to plain-text.
    $message['body'] = drupal_html_to_text($message['body']);
    // Wrap the mail body for sending.
    $message['body'] = drupal_wrap_mail($message['body']);

    return $message;
  }

  /**
   * {@inheritdoc}
   */
  public function mail(array $message) {
    $message['headers']['Subject'] = $message['subject'];

    try {
      $transport = new Horde_Mail_Transport_Smtphorde($this->params);
      $transport->send8bit = TRUE;
      $transport->send(
        $message['to'],
        $message['headers'],
        $message['body']
      );
      return TRUE;
    }
    catch (Horde_Mail_Exception $e) {
      dsm($e->getMessage());
    }
    return FALSE;
  }
}
