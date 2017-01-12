<?php

namespace Drupal\Tests\fapi_example\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the Multistep FAPI Example.
 *
 * @group fapi_example
 * @group examples
 */
class MultistepFormTest extends BrowserTestBase {

  public static $modules = ['fapi_example'];

  /**
   * Test of paths through the example wizard form.
   */
  public function testWizardForm() {
    $this->drupalGet('examples/fapi-example/multistep-form');
    $page = $this->getSession()->getPage();
    $h1 = $page->find('css', 'h1');
    $this->assertContains('Multistep Form', $h1->getText());
    $desc = $page->find('css', '#edit-description label');
    $this->assertContains('page 1', $desc->getText());
    $this->submitForm([
      'first_name' => 'Bozo',
      'last_name' => 'Di Clown',
      'birth_year' => 1980,
    ],
    'Next');

    // Really new page?
    $page2 = $this->getSession()->getPage();
    $desc = $page2->find('css', '#edit-description label');
    $this->assertContains('page 2', $desc->getText());

    // Try the back button.
    $this->submitForm([], 'Back');
    $page1 = $this->getSession()->getPage();
    $desc = $page1->find('css', '#edit-description label');
    $this->assertContains('page 1', $desc->getText());
    // Is the form still filled out?
    $first_name = $page1->findField('first_name')->getValue();
    $this->assertEquals('Bozo', $first_name);
    $second_name = $page1->findField('last_name')->getValue();
    $this->assertEquals('Di Clown', $second_name);
    $birth_year = $page1->findField('birth_year')->getValue();
    $this->assertEquals('1980', $birth_year);

    // Back to the second page.
    $this->click('#edit-next');
    $page2 = $this->getSession()->getPage();
    $desc = $page2->find('css', '#edit-description label');
    $this->assertContains('page 2', $desc->getText());
    $this->submitForm(['color' => 'neon green'], 'Submit');

    // This should take us back to the first page with a status message.
    $messages = $this->getSession()->getPage()->find('css', 'ul.messages__list');
    $message_text = $messages->getHtml();
    $this->assertContains('Bozo Di Clown', $message_text);
    $this->assertContains('1980', $message_text);
    $this->assertContains('neon green', $message_text);
  }

}
