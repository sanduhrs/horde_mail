<?php

/**
 * @file
 * Contains \Drupal\horde_smtp\Form\SettingsForm.
 */

namespace Drupal\horde_smtp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures horde_smtp settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'horde_smtp_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $settings = $this->config('horde_smtp.settings');

    $form['settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Horde SMTP settings'),
    );
    $form['settings']['host'] = array(
      '#type' => 'textfield',
      '#title' => t('Host'),
      '#description' => t('.'),
      '#default_value' => $settings->get('host'),
      '#required' => TRUE,
    );
    $form['settings']['port'] = array(
      '#type' => 'textfield',
      '#title' => t('Port'),
      '#description' => t('.'),
      '#default_value' => $settings->get('port'),
      '#required' => TRUE,
    );
    $form['settings']['secure'] = array(
      '#type' => 'textfield',
      '#title' => t('Secure'),
      '#description' => t('.'),
      '#default_value' => $settings->get('secure'),
      '#required' => TRUE,
    );
    $form['settings']['username'] = array(
      '#type' => 'textfield',
      '#title' => t('Username'),
      '#description' => t('.'),
      '#default_value' => $settings->get('username'),
      '#required' => TRUE,
    );
    $form['settings']['password'] = array(
      '#type' => 'textfield',
      '#title' => t('Password'),
      '#description' => t('.'),
      '#default_value' => $settings->get('password'),
      '#required' => TRUE,
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('horde_smtp.settings')
      ->set('host', $values['host'])
      ->set('port', $values['port'])
      ->set('secure', $values['secure'])
      ->set('username', $values['username'])
      ->set('password', $values['password'])
      ->save();
  }
}
