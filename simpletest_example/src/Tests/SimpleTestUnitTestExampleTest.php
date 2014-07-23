<?php

/**
 * @file
 * An example of simpletest tests to accompany the tutorial at
 * http://drupal.org/node/890654.
 */

namespace Drupal\simpletest_example\Tests;

use Drupal\simpletest_example\EmptyDateChecker;
use Drupal\simpletest\UnitTestBase;

/**
 * Test that simpletest_example_empty_mysql_date works properly.
 *
 * Although most core test cases are based on DrupalWebTestCase and are
 * functional tests (exercising the web UI) we also have DrupalUnitTestCase,
 * which can execute much more quickly because it doesn't install Drupal for
 * each test.
 *
 * No Drupal environment is provided to a test case based on DrupalUnitTestCase;
 * it must be entirely self-contained.
 *
 * Note that as of Drupal 8, we also have PHPUnit-based unit testing as well.
 * PHPUnit is much preferred for more strict unit tests, and meaningful code
 * coverage reports.
 *
 * @see DrupalUnitTestCase
 * @see phpunit_example
 *
 * @ingroup simpletest_example
 *
 * SimpleTest uses group annotations to help you organize your tests.
 * @group simpletest_example
 * @group examples
 */
class SimpleTestUnitTestExampleTest extends UnitTestBase {

  /**
   * Unit test of simpletest_example_empty_mysql_date().
   *
   * Call simpletest_example_empty_mysql_date and check that it returns correct
   * result.
   *
   * Note that no environment is provided; we're just testing the correct
   * behavior of a function when passed specific arguments.
   */
  public function testSimpleTestUnitTestExampleFunction() {
    $date_checker = new EmptyDateChecker();

    $result = $date_checker->emptySqlDate(NULL);
    // Note that test assertion messages should never be translated, so
    // this string is not wrapped in t().
    $message = 'A NULL value should return TRUE.';
    $this->assertTrue($result, $message);

    $result = $date_checker->emptySqlDate('');
    $message = 'An empty string should return TRUE.';
    $this->assertTrue($result, $message);

    $result = $date_checker->emptySqlDate('0000-00-00');
    $message = 'An "empty" MySQL DATE should return TRUE.';
    $this->assertTrue($result, $message);

    $result = $date_checker->emptySqlDate(\date('Y-m-d'));
    $message = 'A valid date should return FALSE.';
    $this->assertFalse($result, $message);
  }

}
