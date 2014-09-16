<?php

/**
 * @file
 * Contains \Drupal\email_example\Form\EmailExampleGetFormPage.
 */

namespace Drupal\email_example\Form;

use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * File test form class.
 */
class EmailExampleGetFormPage implements FormInterface {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormID() {
    return 'email_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['intro'] = array(
      '#markup' => t('Use this form to send a message to an e-mail address. No spamming!'),
    );
    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => t('E-mail address'),
      '#required' => TRUE,
    );
    $form['message'] = array(
      '#type' => 'textarea',
      '#title' => t('Message'),
      '#required' => TRUE,
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!valid_email_address($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', t('That e-mail address is not valid.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    email_example_mail_send($form_state->getValues());
  }

}
