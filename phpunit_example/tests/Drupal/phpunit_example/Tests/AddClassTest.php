<?php

/**
 * @file
 * Contains Drupal\phpunit_example\Tests\AddClassTest
 */

namespace Drupal\phpunit_example\Tests;

use Drupal\Tests\UnitTestCase;
use Drupal\phpunit_example\AddClass;

/**
 * A Drupal PHPUnit test case against an example class.
 *
 * PHPUnit looks for classes with names ending in 'Test'. Then it
 * looks to see whether that class is a subclass of
 * \PHPUnit_Framework_TestCase. Drupal supplies us with
 * Drupal\Tests\UnitTestCase, which is a subclass of
 * \PHPUnit_Framework_TestCase. So yay, PHPUnit will find this class.
 *
 * In unit testing, there should be as few dependencies as possible.
 * We want the smallest number of moving parts to be interacting in
 * our test, or we won't be sure where the errors are, or if our tests
 * passed by accident.
 *
 * So with that in mind, UnitTestCase provides us with very few methods.
 * It's up to us to build out whatever dependencies we need.
 *
 * Annotation for api.drupal.org:
 * @ingroup phpunit_example
 *
 * Annotation for PHPUnit:
 * @group phpunit_example
 */
class AddClassTest extends UnitTestCase {

  public static function getInfo() {
    return array(
      'name' => 'AddClass Unit Test',
      'description' => 'Show some simple unit tests',
      'group' => 'Examples',
    );
  }

  /**
   * Test AddClass::add().
   *
   * This is a very simple unit test of a single method. It has
   * a single assertion, and that assertion is probably going to
   * pass. It ignores most of the problems that could arise in the
   * method under test, so therefore: It is not a very good test.
   */
  public function testAdd() {
    $sut = new AddClass();
    $this->assertEquals($sut->add(2, 3), 5);
  }

  /**
   * Data provider for testAddWithDataProvider().
   *
   * Data provider methods take no arguments and return an array of data
   * to use for tests. Each element of the array is another array, which
   * corresponds to the arguments in the test method's signature.
   *
   * Note also that PHPUnit tries to run tests using methods that begin
   * with 'test'. This means that data provider method names should not
   * begin with 'test'. Also, by convention, they should end with
   * 'DataProvider'.
   *
   * @see AddClassTest::testAddWithDataProvider()
   */
  public function addDataProvider() {
    return array(
      // array($a, $b, $expected)
      array(2, 3, 5),
      array(20, 30, 50),
    );
  }

  /**
   * Test AddClass::add() with a data provider method.
   *
   * This method is very similar to testAdd(), but uses a data provider method
   * to test with a wider range of data.
   *
   * You can tell PHPUnit which method is the data provider using the
   * '@dataProvider' annotation.
   *
   * This test has a better methodology than testAdd(), because it can easily
   * be adapted by other developers, and because it tries more than one data
   * set. This test is much better than testAdd(), although it still only
   * tests 'good' data. When combined with testAddWithBadDataProvider(),
   * we get a better picture of the behavior of the method under test.
   *
   * @dataProvider addDataProvider
   *
   * @see AddClassTest::addDataProvider()
   */
  public function testAddWithDataProvider($a, $b, $expected) {
    $sut = new AddClass();
    $this->assertEquals($sut->add($a, $b), $expected);
  }

  /**
   * Data provider for testAddWithBadDataProvider().
   *
   * Since AddClass::add() can throw exceptions, it's time
   * to give it some data that will cause these exceptions.
   *
   * add() should throw exceptions if either of it's arguments are
   * not numeric, and we will generate some test data to prove that
   * this is what it actually does.
   *
   * @see AddClassTest::testAddWithBadDataProvider()
   */
  public function addBadDataProvider() {
    $badData = array();
    // Set up an array with data that should cause add()
    // to throw an exception.
    $badDataTypes = array('string', FALSE, array('foo'), new \stdClass());
    // Create some data where both $a and $b are bad types.
    foreach ($badDataTypes as $badDatumA) {
      foreach ($badDataTypes as $badDatumB) {
        $badData[] = array($badDatumA, $badDatumB);
      }
    }
    // Create some data where $a is good and $b is bad.
    foreach ($badDataTypes as $badDatumB) {
      $badData[] = array(1, $badDatumB);
    }
    // Create some data where $b is good and $a is bad.
    foreach ($badDataTypes as $badDatumA) {
      $badData[] = array($badDatumA, 1);
    }
    return $badData;
  }

  /**
   * Test AddClass::add() with data that should throw an exception.
   *
   * This method is similar to testAddWithDataProvider(), but the data
   * provider gives us data that should throw an exception.
   *
   * This test uses the '@expectedException' annotation to tell PHPUnit that
   * a thrown exception should pass the test. You specify a
   * fully-qualified exception class name. If you specify \Exception, PHPUnit
   * will pass any exception, whereas a more specific subclass of \Exception
   * will require that exception type to be thrown.
   *
   * Alternately, you can use try and catch blocks with assertions in order
   * to test exceptions.
   *
   * @dataProvider addBadDataProvider
   * @expectedException \InvalidArgumentException
   *
   * @see AddClassTest::addBadDataProvider()
   */
  public function testAddWithBadDataProvider($a, $b) {
    $sut = new AddClass();
    $sut->add($a, $b);
  }

}
