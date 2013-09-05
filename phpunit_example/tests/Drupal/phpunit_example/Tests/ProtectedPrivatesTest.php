<?php

/**
 * @file
 * Contains Drupal\phpunit_example\Tests\AddClassTest
 */

namespace Drupal\phpunit_example\Tests;

// @todo: Remove this once we resolve https://drupal.org/node/2025883
require_once('Stubs/ProtectedPrivatesStub.php');

use Drupal\Tests\UnitTestCase;
use Drupal\phpunit_example\ProtectedPrivates;
use Drupal\phpunit_example\Tests\Stubs\ProtectedPrivatesStub;

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
class ProtectedPrivatesTest extends UnitTestCase {

  public static function getInfo() {
    return array(
      'name' => 'ProtectedPrivates Unit Test',
      'description' => 'Demonstrate unit testing of restricted methods.',
      'group' => 'Examples',
    );
  }

  /**
   * Get an accessible method using reflection.
   */
  public function getAccessibleMethod($className, $methodName) {
    $class = new \ReflectionClass($className);
    $method = $class->getMethod($methodName);
    $method->setAccessible(true);
    return $method;
  }

  /**
   * Good data provider.
   */
  public function addDataProvider() {
    return array(
      array(2, 3, 5),
    );
  }

  /**
   * Test ProtectedPrivate::privateAdd().
   *
   * We want to test a private method on a class. This is problematic
   * because, by design, we don't have access to this method. However,
   * we do have a tool available to help us out with this problem:
   * We can override the accessibility of a method using reflection.
   *
   * @dataProvider addDataProvider
   */
  public function testPrivateAdd($a, $b, $expected) {
    // Get a reflected, accessible version of the privateAdd() method.
    $privateMethod = $this->getAccessibleMethod(
        'Drupal\phpunit_example\ProtectedPrivates', 'privateAdd');
    // Create a new ProtectedPrivates object.
    $pp = new ProtectedPrivates();
    // Use the reflection to invoke on the object.
    $sum = $privateMethod->invokeArgs($pp, array($a, $b));
    // Make an assertion.
    $this->assertEquals($sum, $expected);
  }

  /**
   * Bad data provider.
   */
  public function addBadDataProvider() {
    return array(
      array('string', array()),
    );
  }

  /**
   * Test ProtectedPrivate::privateAdd() with bad data.
   *
   * This is essentially the same test as testPrivateAdd(), but using
   * non-numeric data. This lets us test the exception-throwing ability
   * of this private method.
   *
   * @expectedException \InvalidArgumentException
   * @dataProvider addBadDataProvider
   */
  public function testPrivateAddBadData($a, $b) {
    // Get a reflected, accessible version of the privateAdd() method.
    $privateMethod = $this->getAccessibleMethod(
        'Drupal\phpunit_example\ProtectedPrivates', 'privateAdd');
    // Create a new ProtectedPrivates object.
    $pp = new ProtectedPrivates();
    // Use the reflection to invoke on the object.
    $sum = $privateMethod->invokeArgs($pp, array($a, $b));
  }

  /**
   * Test ProtectedPrivates::protectedAdd() using a stub class.
   *
   * We could use the same reflection technique to test protected
   * methods, just like we did with private ones.
   *
   * But sometimes it might make more sense to use a stub class
   * which will have access to the protected method. That's what
   * we'll demonstrate here.
   *
   * @dataProvider addDataProvider
   */
  public function testProtectedAdd($a, $b, $expected) {
    $stub = new ProtectedPrivatesStub();
    $this->assertEquals($stub->stub_protectedAdd($a, $b), $expected);
  }

  /**
   * Test ProtectedPrivates::protectedAdd() with bad data using a stub class.
   *
   * This test is similar to testProtectedAdd(), but expects an exception.
   *
   * @expectedException \InvalidArgumentException
   * @dataProvider addBadDataProvider
   */
  public function testProtectedAddBadData($a, $b) {
    $stub = new ProtectedPrivatesStub();
    $stub->stub_protectedAdd($a, $b);
  }

}
