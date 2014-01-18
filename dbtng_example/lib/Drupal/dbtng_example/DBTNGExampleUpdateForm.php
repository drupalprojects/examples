<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleUpdateForm
 */

namespace Drupal\dbtng_example;

use Drupal\Core\Form\FormInterface;

/**
 * Sample UI to update a record.
 */
class DBTNGExampleUpdateForm implements FormInterface {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'dbtng_update_form';
  }

  /**
   * Sample UI to update a record.
   */
  public function buildForm(array $form, array &$form_state) {
    $form = array(
      '#prefix' => '<div id="updateform">',
      '#suffix' => '</div>',
    );

    $entries = DBTNGExampleStorage::load();
    $keyed_entries = array();
    if (empty($entries)) {
      $form['no_values'] = array(
        '#value' => t('No entries exist in the table dbtng_example table.'),
      );
      return $form;
    }

    foreach ($entries as $entry) {
      $options[$entry->pid] = t('@pid: @name @surname (@age)',
        array(
          '@pid' => $entry->pid,
          '@name' => $entry->name,
          '@surname' => $entry->surname,
          '@age' => $entry->age,
        )
      );
      $keyed_entries[$entry->pid] = $entry;
    }
    $default_entry = !empty($form_state['values']['pid']) ? $keyed_entries[$form_state['values']['pid']] : $entries[0];

    $form_state['entries'] = $keyed_entries;

    $form['pid'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#title' => t('Choose entry to update'),
      '#default_value' => $default_entry->pid,
      '#ajax' => array(
        'wrapper' => 'updateform',
        'callback' => array($this, 'updateCallback'),
      ),
    );

    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Updated first name'),
      '#size' => 15,
      '#default_value' => $default_entry->name,
    );

    $form['surname'] = array(
      '#type' => 'textfield',
      '#title' => t('Updated last name'),
      '#size' => 15,
      '#default_value' => $default_entry->surname,
    );
    $form['age'] = array(
      '#type' => 'textfield',
      '#title' => t('Updated age'),
      '#size' => 4,
      '#default_value' => $default_entry->age,
      '#description' => t('Values greater than 127 will cause an exception'),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Update'),
    );
    return $form;
  }

  /**
   * AJAX callback handler for the pid select.
   *
   * When the pid changes, populates the defaults from the database in the form.
   */
  public function updateCallback(array $form, array $form_state) {
    $entry = $form_state['entries'][$form_state['values']['pid']];
    // Setting the #value of items is the only way I was able to figure out
    // to get replaced defaults on these items. #default_value will not do it
    // and shouldn't.
    foreach (array('name', 'surname', 'age') as $item) {
      $form[$item]['#value'] = $entry->$item;
    }
    return $form;
  }

  /**
   * Validating the form.
   */
  public function validateForm(array &$form, array &$form_state) {
    // Confirm that age is numeric.
    if (!intval($form_state['values']['age'])) {
      form_set_error('age', t('Age needs to be a number'));
    }
  }

  /**
   * Submit handler for 'update entry' form.
   */
  public function submitForm(array &$form, array &$form_state) {
    // Gather the current user so the new record has ownership.
    $account = \Drupal::currentUser();
    // Save the submitted entry.
    $entry = array(
      'pid' => $form_state['values']['pid'],
      'name' => $form_state['values']['name'],
      'surname' => $form_state['values']['surname'],
      'age' => $form_state['values']['age'],
      'uid' => $account->id(),
    );
    $count = DBTNGExampleStorage::update($entry);
    drupal_set_message(t('Updated entry @entry (@count row updated)',
      array(
        '@count' => $count,
        '@entry' => print_r($entry, TRUE),
      )
    ));
  }

}
