<?php
/**
 * @file
 * Test case for testing the email example module.
 *
 * This file contains the test cases to check if module is performing as
 * expected.
 */

namespace Drupal\email_example\Tests;

use Drupal\simpletest\WebTestBase;

class EmailExampleTest extends WebTestBase {
  public static $modules = array('email_example');

  public static function getInfo() {
    return array(
      'name' => 'Email example functionality',
      'description' => 'Ensure the email example module is working.',
      'group' => 'Examples',
    );
  }

  /**
   * Tests the email form.
   */
  function testEmailExampleBasic() {
    $this->drupalGet('examples/email_example');
    $this->assertFieldById('edit-email', NULL, 'The email field appears.');
    $this->assertFieldById('edit-message', NULL, 'The message field appears.');
    $edit = array('email' => 'example@example.com','message' => 'test');
    $this->drupalPostForm('examples/email_example', $edit, t('Submit'));
    $this->assertResponse(200);
    $this->assertText(t('Your message has been sent.'), 'The text "Your message has been sent." appears on the email example page.', 'Form response with the right message.');
    $this->assertMailString('to', $edit['email'], 1);
    $from = \Drupal::config('system.site')->get('mail');
    $t_options = array('langcode' => \Drupal::languageManager()->getDefaultLanguage()->id);
    $this->assertMailString('subject', t('E-mail sent from @site-name', array('@site-name' => $from), $t_options), 1);
    $this->assertMailString('body', $edit['message'], 1);
    $this->assertMailString('body', t("\n--\nMail altered by email_example module.", array(), $t_options), 1);
  }
}
