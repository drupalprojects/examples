<?php

/**
 * @file
 * Test for the Simple Form example.
 */

namespace Drupal\fapi_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Ensure that the fapi_example forms work properly.
 *
 * @see Drupal\simpletest\WebTestBase
 *
 * SimpleTest uses group annotations to help you organize your tests.
 *
 * @group fapi_example
 *
 * @ingroup fapi_example
 */
class SimpleFormTest extends WebTestBase {

  /**
   * Our module dependencies.
   *
   * @var array List of test dependencies.
   */
  static public $modules = array('fapi_example');

  /**
   * The installation profile to use with this test.
   *
   * @var string Installation profile required for test.
   */
  protected $profile = 'minimal';

  /**
   * Test example forms provided by fapi_example.
   *
   * Check if SimpleTest Example can successfully return its main page,
   * if there is a link to the simpletest_example in the Tools menu.
   */
  public function testSimpleFormExample() {
    // Test for a link to the form_example in the Tools menu.
    $this->drupalGet('');
    $this->assertResponse(200, 'The Home page is available.');
    $this->assertLinkByHref('examples/fapi_example');

    // Test for a link to the simple_form example on the form_example page.
    $this->drupalGet('examples/fapi_example');
    $this->assertLinkByHref('examples/fapi_example/simple_form');

    // Verify that anonymous can access the simpletest_examples page.
    $this->drupalGet('examples/fapi_example/simple_form');
    $this->assertResponse(200, 'The Simple Form Example page is available.');

    // Post a title.
    $edit = ['title' => 'My Custom Title'];
    $this->drupalPostForm('/examples/fapi_example/simple_form', $edit, t('Submit'));
    $this->assertText('You specified a title of My Custom Title.');
  }

}
