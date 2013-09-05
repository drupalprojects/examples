<?php

/**
 * @file
 * Contains Drupal\phpunit_example\Tests\ProtectedPrivatesStub
 */

namespace Drupal\phpunit_example\Tests\Stubs;

use Drupal\phpunit_example\ProtectedPrivates;

/**
 * A class for testing ProtectedPrivate::protectedAdd().
 *
 * We could use reflection to test protected methods, just as with
 * private ones. But in some circumstances it might make more sense
 * to make a subclass and then run the tests on it.
 *
 * This stub class allows us to get access to the protected method.
 */
class ProtectedPrivatesStub extends ProtectedPrivates {

  /**
   * A stub class so we can access a protected method.
   *
   * We use a naming convention so our test code is clear that
   * we are using a stub method.
   */
  public function stub_protectedAdd($a, $b) {
    return $this->protectedAdd($a, $b);
  }

}
