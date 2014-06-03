<?php

/**
 * @file
 * An example of simpletest tests to accompany the tutorial at
 * http://drupal.org/node/890654.
 */

namespace Drupal\simpletest_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * SimpleTestExampleMockModuleTestCase allows us to demonstrate how you can
 * use a mock module to aid in functional testing in Drupal.
 *
 * If you have some functionality that's not intrinsic to the code under test,
 * you can add a special mock module that only gets installed during test
 * time. This allows you to implement APIs created by your module, or otherwise
 * exercise the code in question.
 *
 * This test case class is very similar to SimpleTestExampleTestCase. The main
 * difference is that we enable the simpletest_example_test module in the
 * setUp() method. Then we can test for behaviors provided by that module.
 *
 * @see SimpleTestExampleTestCase
 *
 * @ingroup simpletest_example
 */
class SimpleTestExampleMockModuleTest extends WebTestBase {

  /**
   * Our module dependencies.
   *
   * In Drupal 8's SimpleTest, we declare module dependencies in a public
   * static property called $modules.
   *
   * @var array
   */
  static public $modules = array('simpletest_example', 'simpletest_example_test');

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
      'name' => 'SimpleTest Mock Module Example',
      'description' => "Ensure that we can modify SimpleTest Example's content types.",
      'group' => 'Examples',
    );
  }

  /**
   * Test modifications made by our mock module.
   *
   * We create a simpletest_example node and then see if our submodule
   * operated on it.
   */
  public function testSimpleTestExampleMockModule() {
    // Create a user.
    $test_user = $this->drupalCreateUser(array('access content'));
    // Log them in.
    $this->drupalLogin($test_user);
    // Set up some content.
    $settings = array(
      'type' => 'simpletest_example',
      'title' => $this->randomName(32),
    );
    // Create the content node.
    $node = $this->drupalCreateNode($settings);
    // View the node.
    $this->drupalGet('node/' . $node->id());
    // Check that our module did it's thing.
    $this->assertText(t('The test module did its thing.'), "Found evidence of test module.");
  }

}
