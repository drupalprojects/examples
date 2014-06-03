<?php

/**
 * @file
 * Contains Drupal\simpletest_example\EmptyDateChecker.
 *
 * This class exists purely to demonstrate unit testing with SimpleTest.
 */

namespace Drupal\simpletest_example;

class EmptyDateChecker {

  /**
   * Determines if a a date string is empty or zero-date.
   *
   * This function exists to demonstrate unit-testing a function.
   *
   * @see SimpletestUnitTestExampleTestCase
   */
  public function emptySqlDate($date_string) {
    if (empty($date_string) || $date_string == '0000-00-00' || $date_string == '0000-00-00 00:00:00') {
      return TRUE;
    }
    return FALSE;
  }

}
