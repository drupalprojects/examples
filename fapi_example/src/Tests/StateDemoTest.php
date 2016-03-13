<?php

/**
 * @file
 * Test for the State Demo form example.
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
class StateDemoTest extends WebTestBase {

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
   */
  public function testStateDemoForm() {
    // Test for a link to the form_example in the Tools menu.
    $this->drupalGet('');
    $this->assertResponse(200, 'The Home page is available.');
    $this->assertLinkByHref('examples/fapi_example');

    // Test for a link to the simple_form example on the form_example page.
    $this->drupalGet('examples/fapi_example');
    $this->assertLinkByHref('examples/fapi_example/state_demo');

    // Verify that anonymous can access the simpletest_examples page.
    $this->drupalGet('examples/fapi_example/state_demo');
    $this->assertResponse(200, 'The Demo of Form State Binding page is available.');

    // Post the form.
    $edit = [
      'needs_accommodation' => TRUE,
      'diet' => 'vegan',
    ];
    $this->drupalPostForm('/examples/fapi_example/state_demo', $edit, t('Submit'));
    $this->assertText('Dietary Restriction Requested: vegan');
  }

}
