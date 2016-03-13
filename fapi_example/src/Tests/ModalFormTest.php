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
class ModalFormTest extends WebTestBase {

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
  public function testModalForm() {

    // Test for a link to the simple_form example on the form_example page.
    $this->drupalGet('examples/fapi_example');
    $this->assertLinkByHref('examples/fapi_example/modal_form');

    // Verify that anonymous can access the page.
    $this->drupalGet('examples/fapi_example/modal_form');
    $this->assertResponse(200, 'The Demo of Modal Form is available.');

    // Post the form.
    $edit = [
      'title' => 'My Book',
    ];
    $this->drupalPostForm('/examples/fapi_example/modal_form', $edit, t('Submit'));
    $this->assertText('Submit handler: You specified a title of My Book.');
  }

}
