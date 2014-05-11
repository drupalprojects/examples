<?php

/**
 * @file
 * Contains \Drupal\dbtng_example\DBTNGExampleController.
 */

namespace Drupal\dbtng_example;

/**
 * Controller for DBTNG Example.
 */
class DBTNGExampleController {

  /**
   * Render a list of entries in the database.
   */
  public function entryList() {
    $rows = array();
    $headers = array(t('Id'), t('uid'), t('Name'), t('Surname'), t('Age'));

    foreach ($entries = DBTNGExampleStorage::load() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('check_plain', (array) $entry);
    }
    return array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries available.'),
    );
  }

  /**
   * Render a filtered list of entries in the database.
   */
  public function entryAdvancedList() {
    $headers = array(
      t('Id'),
      t('Created by'),
      t('Name'),
      t('Surname'),
      t('Age'),
    );

    $rows = array();
    foreach ($entries = DBTNGExampleStorage::advancedLoad() as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('check_plain', $entry);
    }
    return array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#attributes' => array('id' => 'dbtng-example-advanced-list'),
      '#empty' => t('No entries available.'),
    );
  }

}
