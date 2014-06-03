<?php

/**
 * @file
 * Contains Drupal\phpunit_example\AddClass
 */

namespace Drupal\phpunit_example;

/**
 * A class with features to show how to do unit testing.
 *
 * @ingroup phpunit_example
 */
class AddClass {

  /**
   * A simple addition method with validity checking.
   *
   * @param $a
   *   A number to add.
   * @param $b
   *   Another number to add.
   *
   * @return
   *   The sum of $a and $b.
   *
   * @throws \InvalidArgumentException
   *   If either $a or $b is non-numeric, we can't add, so we throw.
   */
  public function add($a, $b) {
    // Check whether the arguments are numeric.
    foreach (array($a, $b) as $argument) {
      if (!is_numeric($argument)) {
        throw new \InvalidArgumentException('Arguments must be numeric.');
      }
    }
    return $a + $b;
  }

}
