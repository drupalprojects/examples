<?php

/**
 * @file
 * Contains Drupal\fapi_example\Tests\BuildDemoTest.
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
class BuildDemoTest extends WebTestBase {

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
  public function testBuildDemo() {

    // Test for a link to the simple_form example on the form_example page.
    $this->drupalGet('examples/fapi_example');
    $this->assertLinkByHref('examples/fapi_example/build_demo');

    // Verify that anonymous can access the page.
    $this->drupalGet('examples/fapi_example/build_demo');
    $this->assertResponse(200, 'The Build Demo Form is available.');

    // Post the form.
    $edit = [
      'change' => '1',
    ];
    $this->drupalPostForm('/examples/fapi_example/build_demo', $edit, t('Submit'));
    $this->assertText('1. __construct');
    $this->assertText('2. getFormId');
    $this->assertText('3. validateForm');
    $this->assertText('4. submitForm');
  }

}
