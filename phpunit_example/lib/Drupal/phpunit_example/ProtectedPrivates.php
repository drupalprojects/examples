<?php

/**
 * @file
 * Contains Drupal\phpunit_example\AddClass
 */

namespace Drupal\phpunit_example;

use Drupal\phpunit_example\AddClass;

/**
 * A class with features to show how to do unit testing.
 *
 * This class has private and protected methods to demonstrate
 * how to test with reflection.
 *
 * protectedAdd() and privateAdd() are shim methods to AddClass::add().
 * We do this so we're concentrating on the testing instead of the
 * code being tested.
 */
class ProtectedPrivates {

  /**
   * Add two numbers.
   */
  protected function protectedAdd($a, $b) {
    $adder = new AddClass();
    return $adder->add($a, $b);
  }

  /**
   * Add two numbers.
   */
  private function privateAdd($a, $b) {
    $adder = new AddClass();
    return $adder->add($a, $b);
  }

}
