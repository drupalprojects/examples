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
 * Although most core test cases are based on DrupalWebTestCase and are
 * functional tests (exercising the web UI) we also have DrupalUnitTestCase,
 * which executes much faster because a Drupal install does not have to be
 * one. No environment is provided to a test case based on DrupalUnitTestCase;
 * it must be entirely self-contained.
 *
 * @see DrupalUnitTestCase
 *
 * @ingroup simpletest_example
 */
class SimpleTestUnitTestExampleTest extends UnitTestBase {

  /**
   * Give some info about our test to the testing system.
   *
   * Under SimpleTest, we have to return some information about our test to the
   * testing system.
   *
   * We give our test class a name, a description, and a group.
   *
   * @see Drupal\simpletest\TestBase::getInfo()
   *
   * @return array
   *   A keyed array.
   */
  public static function getInfo() {
    return array(
      'name' => 'SimpleTest Example Unit Testing',
      'description' => 'Test that simpletest_example_empty_mysql_date works properly.',
      'group' => 'Examples',
    );
  }

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
