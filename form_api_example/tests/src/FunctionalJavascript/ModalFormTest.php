<?php

namespace Drupal\Tests\form_api_example\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\JavascriptTestBase;
use Drupal\Core\Url;

/**
 * @group form_api_example
 *
 * @ingroup form_api_example
 */
class ModalFormTest extends JavascriptTestBase {

  /**
   * Our module dependencies.
   *
   * @var string[]
   */
  static public $modules = ['form_api_example'];

  /**
   * Functional test of the modal form example.
   *
   * Steps:
   * - Visit form route.
   * - Click on 'see this form as a modal'.
   * - Check that modal exists.
   * - Enter a value.
   * - Click 'submit'
   * - Check that we have a new modal.
   * - Click the close X.
   * - Verify that the modal went away.
   */
  public function testModalForm() {
    // Visit form route.
    $modal_route_nojs = Url::fromRoute('form_api_example.modal_form', ['nojs' => 'nojs']);
    $this->drupalGet($modal_route_nojs);

    // Get Mink stuff.
    $assert = $this->assertSession();
    $session = $this->getSession();
    $page = $this->getSession()->getPage();

    // Click on 'see this form as a modal'.
    $this->clickLink('ajax-example-modal-link');

    $this->assertNotEmpty($assert->waitForElementVisible('css', '.ui-dialog'));

    // Enter a value.
    $this->assertNotEmpty($input = $page->find('css', 'div.ui-dialog input[name="title"]'));
    $input->setValue('test_title');

    // Click 'submit'.
    // @todo: Switch to using NodeElement::click() on the button or
    // NodeElement::submit() on the form when #2831506 is fixed.
    // @see https://www.drupal.org/node/2831506
    $session->executeScript("jQuery('button.ui-button.form-submit').click()");
    $assert->assertWaitOnAjaxRequest();

    // Check that we have a new modal.
    $assert->elementContains('css', 'span.ui-dialog-title', 'test_title');

    // Click the close X.
    // @todo: Switch to using NodeElement::click() on the button or
    // NodeElement::submit() on the form when #2831506 is fixed.
    // @see https://www.drupal.org/node/2831506
    $session->executeScript("jQuery('button.ui-dialog-titlebar-close').click()");
    $assert->assertWaitOnAjaxRequest();

    // Verify that the modal went away.
    $assert->pageTextNotContains('appears in this modal dialog.');
  }

}
