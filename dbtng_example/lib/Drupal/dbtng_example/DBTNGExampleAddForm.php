<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNExampleAddForm
 */

namespace Drupal\dbtng_example;

use Drupal\Core\Form\FormInterface;

/**
 * Simple form to add an entry, with all the interesting fields.
 */
class DBTNGExampleAddForm implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'dbtng_add_form';
  }

  /**
   * Prepare a simple form to add an entry, with all the interesting fields.
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array();

    $form['add'] = array(
      '#type' => 'fieldset',
      '#title' => t('Add a person entry'),
    );
    $form['add']['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#size' => 15,
    );
    $form['add']['surname'] = array(
      '#type' => 'textfield',
      '#title' => t('Surname'),
      '#size' => 15,
    );
    $form['add']['age'] = array(
      '#type' => 'textfield',
      '#title' => t('Age'),
      '#size' => 5,
      '#description' => t("Values greater than 127 will cause an exception. Try it - it's a great example why exception handling is needed with DTBNG."),
    );
    $form['add']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Add'),
    );

    return $form;
  }

  /**
   * Validate the form.
   */
  public function validateForm(array &$form, array &$form_state) {
    // Confirm that age is numeric.
    if (!intval($form_state['values']['age'])) {
      form_set_error('age', t('Age needs to be a number'));
    }
  }

  /**
   * Submit handler for 'add entry' form.
   */
  public function submitForm(array &$form, array &$form_state) {
    // Gather the current user so the new record has ownership.
    $account = \Drupal::currentUser();
    // Save the submitted entry.
    $entry = array(
      'name' => $form_state['values']['name'],
      'surname' => $form_state['values']['surname'],
      'age' => $form_state['values']['age'],
      'uid' => $account->id(),
    );
    $return = DBTNGExampleStorage::insert($entry);
    if ($return) {
      drupal_set_message(t('Created entry @entry', array('@entry' => print_r($entry, TRUE))));
    }
  }

}
