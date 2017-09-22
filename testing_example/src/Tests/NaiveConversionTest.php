<?php
// @codingStandardsIgnoreFile

// Move this file to testing_example/tests/src/Functional/.

// Change this namespace to Drupal\Tests\testing_example\Functional.
namespace Drupal\testing_example\Tests;

// Change this to use Drupal\Tests\BrowserTestBase.
use Drupal\simpletest\WebTestBase;

/**
 * Change this class declaration so it extends BrowserTestBase.
 *
 * @group testing_example
 */
class NaiveConversionTest extends WebTestBase {

  /**
   * A very basic passing test method that requires no further conversion.
   */
  public function testConversion() {
    $this->assertTrue(TRUE);
  }

}
