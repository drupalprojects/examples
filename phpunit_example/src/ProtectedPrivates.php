<?php

/**
 * @file
 * Contains Drupal\phpunit_example\ProtectedPrivates
 */

namespace Drupal\phpunit_example;

use Drupal\phpunit_example\AddClass;

/**
 * A class with features to show how to do unit testing.
 *
 * This class has private and protected methods to demonstrate
 * how to test with reflection and mocking.
 *
 * protectedAdd() and privateAdd() are shim methods to AddClass::add().
 * We do this so we're concentrating on the testing instead of the
 * code being tested.
 *
 * @ingroup phpunit_example
 */
class ProtectedPrivates {

  /**
   * A simple addition method with validity checking.
   *
   * @param numeric $a
   *   A number to add.
   * @param numeric $b
   *   Another number to add.
   *
   * @return numeric
   *   The sum of $a and $b.
   *
   * @throws \InvalidArgumentException
   *   If either $a or $b is non-numeric, we can't add, so we throw.
   */
  protected function protectedAdd($a, $b) {
    $adder = new AddClass();
    return $adder->add($a, $b);
  }

  /**
   * A simple addition method with validity checking.
   *
   * @param numeric $a
   *   A number to add.
   * @param numeric $b
   *   Another number to add.
   *
   * @return numeric
   *   The sum of $a and $b.
   *
   * @throws \InvalidArgumentException
   *   If either $a or $b is non-numeric, we can't add, so we throw.
   */
  private function privateAdd($a, $b) {
    $adder = new AddClass();
    return $adder->add($a, $b);
  }

}
