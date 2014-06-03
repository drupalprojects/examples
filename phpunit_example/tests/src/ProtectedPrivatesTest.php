<?php

/**
 * @file
 * Contains Drupal\phpunit_example\Tests\ProtectedPrivatesTest
 */

namespace Drupal\phpunit_example\Tests;

use Drupal\Tests\UnitTestCase;

use Drupal\phpunit_example\ProtectedPrivates;

use Drupal\phpunit_example\Tests\Subclasses\ProtectedPrivatesSubclass;

/**
 * A PHPUnit example test case against an example class.
 *
 * This test case demonstrates the following unit testing patterns and topics:
 * - Using reflection to test private class methods.
 * - Using subclassing to test protected class methods.
 *
 * If you are reading this and don't understand the basics of unit testing,
 * start reading AddClassTest instead.
 *
 * This test class uses reflection and subclassing to work around method
 * access problems. Since, by design, a private method is inaccessible,
 * we have to use reflection to gain access to the method for our own
 * purposes.
 *
 * The getAccessibleMethod() method demonstrates a way to do this.
 *
 * Once we've set the method to be accessible, we can use it as if
 * it were public.
 *
 * The same technique can be used for protected methods. However, there
 * might be times when it makes more sense to subclass the class under
 * test, and just make a public accessor method that way. So we
 * demonstrate that here in testProtectedAdd().
 *
 * @ingroup phpunit_example
 * @group phpunit_example
 */
class ProtectedPrivatesTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
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
    $method->setAccessible(TRUE);
    return $method;
  }

  /**
   * Good data provider.
   */
  public function addDataProvider() {
    return array(
      array(5, 2, 3),
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
  public function testPrivateAdd($expected, $a, $b) {
    // Get a reflected, accessible version of the privateAdd() method.
    $privateMethod = $this->getAccessibleMethod(
      'Drupal\phpunit_example\ProtectedPrivates',
      'privateAdd'
    );
    // Create a new ProtectedPrivates object.
    $pp = new ProtectedPrivates();
    // Use the reflection to invoke on the object.
    $sum = $privateMethod->invokeArgs($pp, array($a, $b));
    // Make an assertion.
    $this->assertEquals($expected, $sum);
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
      'Drupal\phpunit_example\ProtectedPrivates',
      'privateAdd');
    // Create a new ProtectedPrivates object.
    $pp = new ProtectedPrivates();
    // Use the reflection to invoke on the object.
    // This should throw an exception.
    $privateMethod->invokeArgs($pp, array($a, $b));
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
  public function testProtectedAdd($expected, $a, $b) {
    $stub = new ProtectedPrivatesSubclass();
    $this->assertEquals($expected, $stub->sub_protectedAdd($a, $b));
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
    $stub = new ProtectedPrivatesSubclass();
    $stub->sub_protectedAdd($a, $b);
  }

}
