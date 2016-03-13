<?php

/**
 * @file
 * Test for the Input Demo form.
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
class InputDemoTest extends WebTestBase {

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
  public function testInputDemoForm() {
    // Test for a link to the form_example in the Tools menu.
    $this->drupalGet('');
    $this->assertResponse(200, 'The Home page is available.');
    $this->assertLinkByHref('examples/fapi_example');

    // Test for a link to the simple_form example on the form_example page.
    $this->drupalGet('examples/fapi_example');
    $this->assertLinkByHref('examples/fapi_example/input_demo');

    // Verify that anonymous can access the simpletest_examples page.
    $this->drupalGet('examples/fapi_example/input_demo');
    $this->assertResponse(200, 'The Demo of Common Input Elements page is available.');

    // Post the form.
    $edit = [
      'tests_taken[SAT]' => TRUE,
      'color' => '#ff6bf1',
      'expiration' => '2015-10-21',
      'email' => 'somebody@example.org',
      'quantity' => '4',
      'password' => 'letmein',
      'password_confirm[pass1]' => 'letmein',
      'password_confirm[pass2]' => 'letmein',
      'size' => '76',
      'active' => '1',
      'search' => 'my search string',
      'favorite' => 'blue',
      'phone' => '555-555-5555',
      'table[1]' => TRUE,
      'table[3]' => TRUE,
      'text' => 'This is a test of my form.',
      'subject' => 'Form test',
      'weight' => '3',
    ];
    $this->drupalPostForm('/examples/fapi_example/input_demo', $edit, t('Submit'));
    $this->assertText('Value for What standardized tests did you take?: Array ( [SAT] =&gt; SAT )');
    $this->assertText('Value for Color: #ff6bf1');
    $this->assertText('Value for Content expiration: 2015-10-21');
    $this->assertText('Value for Email: somebody@example.org');
    $this->assertText('Value for Quantity: 4');
    $this->assertText('Value for Password: letmein');
    $this->assertText('Value for New Password: letmein');
    $this->assertText('Value for Size: 76');
    $this->assertText('Value for active: 1');
    $this->assertText('Value for Search: my search string');
    $this->assertText('Value for Favorite color: blue');
    $this->assertText('Value for Phone: 555-555-5555');
    $this->assertText('Value for Users: Array ( [1] =&gt; 1 [3] =&gt; 3 )');
    $this->assertText('Value for Text: This is a test of my form.');
    $this->assertText('Value for Subject: Form test');
    $this->assertText('Value for Weight: 3');
  }

}
