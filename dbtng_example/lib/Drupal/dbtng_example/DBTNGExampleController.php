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
    $output = '';

    // Get all entries in the dbtng_example table.
    if ($entries = DBTNGExampleStorage::load()) {
      $rows = array();
      foreach ($entries as $entry) {
        // Sanitize the data before handing it off to the theme layer.
        $rows[] = array_map('check_plain', (array) $entry);
      }
      // Make a table for them.
      $header = array(t('Id'), t('uid'), t('Name'), t('Surname'), t('Age'));
      $output .= theme('table', array('header' => $header, 'rows' => $rows));
    }
    else {
      drupal_set_message(t('No entries were found.'));
    }
    return $output;
  }

  /**
   * Render a filtered list of entries in the database.
   */
  public function entryAdvancedList() {
    $output = '';

    $entries = DBTNGExampleStorage::advancedLoad();

    if (!empty($entries)) {
      $rows = array();
      foreach ($entries as $entry) {
        // Sanitize the data before handing it off to the theme layer.
        $rows[] = array_map('check_plain', $entry);
      }
      // Make a table for them.
      $header = array(
        t('Id'),
        t('Created by'),
        t('Name'),
        t('Surname'),
        t('Age'),
      );
      $output .= theme('table',
        array(
          'header' => $header,
          'rows' => $rows,
          'attributes' => array(
            'id' => 'dbtng-example-advanced-list',
          ),
        )
      );
    }
    else {
      drupal_set_message(t('No entries meet the filter criteria (Name = "John" and Age > 18).'));
    }
    return $output;
  }

}
