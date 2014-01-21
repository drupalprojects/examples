<?php
/**
 * @file
 * Test case for testing the page_example module.
 *
 * This file contains the test cases to check if module is performing as
 * expected.
 */

namespace Drupal\page_example\Tests;

use Drupal\simpletest\WebTestBase;

class PageExampleTest extends WebTestBase {

  public static $modules = array('page_example');

  protected $webUser;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Page example functionality',
      'description' => 'Creates page and render the content based on the arguments passed in the URL.',
      'group' => 'Examples',
    );
  }

  /**
   * Generates a random string of ASCII numeric characters (values 48 to 57).
   *
   * @param int $length
   *   Length of random string to generate.
   *
   * @return string
   *   Randomly generated string.
   */
  protected static function randomNumber($length = 8) {
    $str = '';
    for ($i = 0; $i < $length; $i++) {
      $str .= chr(mt_rand(48, 57));
    }
    return $str;
  }

  /**
   * Verify that current user has no access to page.
   *
   * @param string $url
   *   URL to verify.
   */
  public function pageExampleVerifyNoAccess($url) {
    // Test that page returns 403 Access Denied.
    $this->drupalGet($url);
    $this->assertResponse(403);
  }

  /**
   * Main test.
   *
   * Login user, create an example node, and test page functionality through
   * the admin and user interfaces.
   */
  public function testPageExampleBasic() {
    // Verify that anonymous user can't access the pages created by
    // page_example module.
    $this->pageExampleVerifyNoAccess('examples/page_example/simple');
    $this->pageExampleVerifyNoAccess('examples/page_example/arguments/1/2');

    // Create a regular user and login.
    $this->webUser = $this->drupalCreateUser();
    $this->drupalLogin($this->webUser);

    // Verify that regular user can't access the pages created by
    // page_example module.
    $this->pageExampleVerifyNoAccess('examples/page_example/simple');
    $this->pageExampleVerifyNoAccess('examples/page_example/arguments/1/2');

    // Create a user with permissions to access 'simple' page and login.
    $this->webUser = $this->drupalCreateUser(array('access simple page'));
    $this->drupalLogin($this->webUser);

    // Verify that user can access simple content.
    $this->drupalGet('examples/page_example/simple');
    $this->assertResponse(200, 'Simple content successfully accessed.');
    $this->assertText(t('The quick brown fox jumps over the lazy dog.'), 'Simple content successfully verified.');

    // Check if user can't access arguments page.
    $this->pageExampleVerifyNoAccess('examples/page_example/arguments/1/2');

    // Create a user with permissions to access 'simple' page and login.
    $this->webUser = $this->drupalCreateUser(array('access arguments page'));
    $this->drupalLogin($this->webUser);

    // Verify that user can access arguments content.
    $first = self::randomNumber(3);
    $second = self::randomNumber(3);
    $this->drupalGet('examples/page_example/arguments/' . $first . '/' . $second);
    $this->assertResponse(200, 'Arguments content successfully accessed.');
    // Verify argument usage.
    $this->assertRaw(t('First number was @number.', array('@number' => $first)), 'First argument successfully verified.');
    $this->assertRaw(t('Second number was @number.', array('@number' => $second)), 'Second argument successfully verified.');
    $this->assertRaw(t('The total was @number.', array('@number' => $first + $second)), 'arguments content successfully verified.');

    // Verify incomplete argument call to arguments content.
    $this->drupalGet('examples/page_example/arguments/' . $first . '/');
    $this->assertResponse(404, 'User got 404 on incomplete arguments request.');

    // Verify 403 for invalid second argument.
    $this->drupalGet('examples/page_example/arguments/' . $first . '/non-numeric-argument');
    $this->assertResponse(403, 'User got 403 for string argument in second position.');

    // Verify 403 for invalid first argument.
    $this->drupalGet('examples/page_example/arguments/non-numeric-argument/' . $second);
    $this->assertResponse(403, 'User got 403 for string argument in first position.');

    // Check if user can't access simple page.
    $this->pageExampleVerifyNoAccess('examples/page_example/simple');
  }

}
