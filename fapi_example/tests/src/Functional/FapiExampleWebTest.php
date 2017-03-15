<?php

namespace Drupal\Tests\fapi_example\Functional;
use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Ensure that the fapi_example forms work properly.
 *
 * SimpleTest uses group annotations to help you organize your tests.
 *
 * @group fapi_example
 * @group examples
 *
 * @ingroup fapi_example
 */
class FapiExampleWebTest extends ExamplesBrowserTestBase {

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
   * Test the ajax demo form.
   */
  public function testAjaxDemoForm() {

    $assert = $this->assertSession();

    // Test for a link to the ajax_demo example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $assert->linkByHrefExists('examples/fapi-example/ajax-demo');

    // Verify that anonymous can access the page.
    $this->drupalGet('examples/fapi-example/ajax-demo');
    $assert->statusCodeEquals(200);

    // Post the form.
    $edit = [
      'temperature' => 'warm',
    ];
    $this->drupalPostForm('/examples/fapi-example/ajax-demo', $edit, t('Submit'));
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Value for Temperature: warm');
  }

  /**
   * Test the build demo form.
   */
  public function testBuildDemo() {
    $assert = $this->assertSession();

    // Test for a link to the build_demo example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $assert->statusCodeEquals(200);

    $assert->linkByHrefExists('examples/fapi-example/build-demo');

    // Verify that anonymous can access the page.
    $this->drupalGet('examples/fapi-example/build-demo');
    $assert->statusCodeEquals(200);

    $edit = [
      'change' => '1',
    ];
    $this->drupalPostForm('/examples/fapi-example/build-demo', $edit, t('Submit'));

    $assert->pageTextContains('1. __construct');
    $assert->pageTextContains('2. getFormId');
    $assert->pageTextContains('3. validateForm');
    $assert->pageTextContains('4. submitForm');
  }

  /**
   * Test the container demo form.
   */
  public function testContainerDemoForm() {
    $assert = $this->assertSession();

    // Test for a link to the container_demo example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');

    $assert->linkByHrefExists('examples/fapi-example/container-demo');

    // Verify that anonymous can access the container_demo example page.
    $this->drupalGet('examples/fapi-example/container-demo');
    $assert->statusCodeEquals(200);

    // Post the form.
    $edit = [
      'name' => 'Dave',
      'pen_name' => 'DMan',
      'title' => 'My Book',
      'publisher' => 'me',
      'diet' => 'vegan',
    ];
    $this->drupalPostForm('/examples/fapi-example/container-demo', $edit, t('Submit'));
    $assert->pageTextContains('Value for name: Dave');
    $assert->pageTextContains('Value for pen_name: DMan');
    $assert->pageTextContains('Value for title: My Book');
    $assert->pageTextContains('Value for publisher: me');
    $assert->pageTextContains('Value for diet: vegan');
  }

  /**
   * Test the input demo form.
   */
  public function testInputDemoForm() {
    $assert = $this->assertSession();

    // Test for a link to the input_demo example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $this->assertLinkByHref('examples/fapi-example/input-demo');

    // Verify that anonymous can access the input_demo page.
    $this->drupalGet('examples/fapi-example/input-demo');
    $assert->statusCodeEquals(200);

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
    $this->drupalPostForm('/examples/fapi-example/input-demo', $edit, t('Submit'));
    $assert->statusCodeEquals(200);

    $assert->pageTextContains('Value for What standardized tests did you take?: Array ( [SAT] => SAT )');
    $assert->pageTextContains('Value for Color: #ff6bf1');
    $assert->pageTextContains('Value for Content expiration: 2015-10-21');
    $assert->pageTextContains('Value for Email: somebody@example.org');
    $assert->pageTextContains('Value for Quantity: 4');
    $assert->pageTextContains('Value for Password: letmein');
    $assert->pageTextContains('Value for New Password: letmein');
    $assert->pageTextContains('Value for Size: 76');
    $assert->pageTextContains('Value for active: 1');
    $assert->pageTextContains('Value for Search: my search string');
    $assert->pageTextContains('Value for Favorite color: blue');
    $assert->pageTextContains('Value for Phone: 555-555-5555');
    $assert->pageTextContains('Value for Users: Array ( [1] => 1 [3] => 3 )');
    $assert->pageTextContains('Value for Text: This is a test of my form.');
    $assert->pageTextContains('Value for Subject: Form test');
    $assert->pageTextContains('Value for Weight: 3');
  }

  /**
   * Test the modal form.
   */
  public function testModalForm() {
    $assert = $this->assertSession();

    // Test for a link to the modal_form example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $assert->linkByHrefExists('examples/fapi-example/modal-form');

    // Verify that anonymous can access the page.
    $this->drupalGet('examples/fapi-example/modal-form');
    $assert->statusCodeEquals(200);

    // Post the form.
    $edit = [
      'title' => 'My Book',
    ];
    $this->drupalPostForm('/examples/fapi-example/modal-form', $edit, t('Submit'));
    $assert->pageTextContains('Submit handler: You specified a title of My Book.');
  }

  /**
   * Check routes defined by fapi_example.
   */
  public function testSimpleFormExample() {
    $assert = $this->assertSession();

    // Test for a link to the fapi_example in the Tools menu.
    $this->drupalGet('');
    $assert->statusCodeEquals(200);
    $assert->linkByHrefExists('examples/fapi-example');

    // Test for a link to the simple_form example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $assert->linkByHrefExists('examples/fapi-example/simple-form');

    // Verify that anonymous can access the simple_form page.
    $this->drupalGet('examples/fapi-example/simple-form');
    $assert->statusCodeEquals(200);

    // Post a title.
    $edit = ['title' => 'My Custom Title'];
    $this->drupalPostForm('/examples/fapi-example/simple-form', $edit, t('Submit'));
    $assert->pageTextContains('You specified a title of My Custom Title.');
  }

  /**
   * Test the state demo form.
   */
  public function testStateDemoForm() {
    $assert = $this->assertSession();

    // Test for a link to the state_demo example on the fapi_example page.
    $this->drupalGet('examples/fapi-example');
    $assert->statusCodeEquals(200);

    $assert->linkByHrefExists('examples/fapi-example/state-demo');

    // Verify that anonymous can access the state_demo page.
    $this->drupalGet('examples/fapi-example/state-demo');
    $assert->statusCodeEquals(200);

    // Post the form.
    $edit = [
      'needs_accommodation' => TRUE,
      'diet' => 'vegan',
    ];
    $this->drupalPostForm('/examples/fapi-example/state-demo', $edit, t('Submit'));
    $assert->pageTextContains('Dietary Restriction Requested: vegan');
  }

  /**
   * Test the vertical tabs demo form.
   */
  public function testVerticalTabsDemoForm() {
    $assert = $this->assertSession();

    // Test for a link to the vertical_tabs_demo example on the fapi_example
    // page.
    $this->drupalGet('examples/fapi-example');

    $assert->linkByHrefExists('examples/fapi-example/vertical-tabs-demo');

    // Verify that anonymous can access the vertical_tabs_demo page.
    $this->drupalGet('examples/fapi-example/vertical-tabs-demo');
    $assert->statusCodeEquals(200);

    // Post the form.
    $edit = [
      'name' => 'Dave',
      'publisher' => 'me',
    ];
    $this->drupalPostForm('/examples/fapi-example/container-demo', $edit, t('Submit'));
    $assert->pageTextContains('Value for name: Dave');
    $assert->pageTextContains('Value for publisher: me');
  }

}
