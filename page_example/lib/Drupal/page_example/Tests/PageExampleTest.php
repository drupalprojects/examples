<?php
/**
 * @file
 * Test case for Testing the page example module.
 *
 * This file contains the test cases to check if module is performing as
 * expected.
 */

namespace Drupal\page_example\Tests;
use Drupal\simpletest\WebTestBase;

class PageExampleTest extends WebTestBase {
  public static $modules = array('page_example');
  protected $webUser;

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
   * @param $length
   *   Length of random string to generate.
   *
   * @return
   *   Randomly generated string.
   */
  private static function randomNumber($length = 8) {
    $str = '';
    for ($i = 0; $i < $length; $i++) {
      $str .= chr(mt_rand(48, 57));
    }
    return $str;
  }

  /**
   * Verify that current user has no access to page.
   *
   * @param $url
   *   URL to verify.
   */
  function pageExampleVerifyNoAccess($url) {
    // Test that page returns 403 Access Denied
    $this->drupalGet($url);
    $this->assertResponse(403);
  }

  /**
   * Main test.
   *
   * Login user, create an example node, and test blog functionality through
   * the admin and user interfaces.
   */
  function testPageExampleBasic() {
    // Verify that anonymous user can't access the pages created by
    // page_example module
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

    // Verify that user can access simple content.
    $first = self::randomNumber(3);
    $second = self::randomNumber(3);
    $this->drupalGet('examples/page_example/arguments/' . $first . '/' . $second);
    $this->assertResponse(200, 'Arguments content successfully accessed.');
    // Verify argument usage.
    $this->assertRaw(t('First number was @number.', array('@number' => $first)), 'First argument successfully verified.');
    $this->assertRaw(t('Second number was @number.', array('@number' => $second)), 'Second argument successfully verified.');
    $this->assertRaw(t('The total was @number.', array('@number' => $first + $second)), 'arguments content successfully verified.');

    // @fixme Revisit to readd if new routing system provides a way to do
    // inclomplete arguments call and we implement it on this example.
    // Verify incomplete argument call to arguments content.
    // $this->drupalGet('examples/page_example/arguments/' . $first . '/');
    // $this->assertText('provides two pages');
    // Verify invalid argument call to arguments content.
    //$this->drupalGet('examples/page_example/arguments/' . $first . '/' . $this->randomString());
    //$this->assertResponse(403, 'Invalid argument for arguments content successfully verified');

    // Verify invalid argument call to arguments content.
    $this->drupalGet('examples/page_example/arguments/' . $this->randomString() . '/' . $second);
    $this->assertResponse(403, 'Invalid argument for arguments content successfully verified');

    // Check if user can't access simple page
    $this->pageExampleVerifyNoAccess('examples/page_example/simple');
  }
}
