<?php

namespace Drupal\content_entity_example\Tests;

use Drupal\content_entity_example\Entity\Contact;
use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Tests the basic functions of the Content Entity Example module.
 *
 * @package Drupal\content_entity_example\Tests
 *
 * @ingroup content_entity_example
 *
 * @group content_entity_example
 * @group examples
 */
class ContentEntityExampleTest extends ExamplesBrowserTestBase {

  public static $modules = array('content_entity_example', 'block', 'field_ui');

  /**
   * Basic tests for Content Entity Example.
   */
  public function testContentEntityExample() {
    $assert = $this->assertSession();

    $web_user = $this->drupalCreateUser(array(
      'add contact entity',
      'edit contact entity',
      'view contact entity',
      'delete contact entity',
      'administer contact entity',
      'administer content_entity_example_contact display',
      'administer content_entity_example_contact fields',
      'administer content_entity_example_contact form display',
    ));

    // Anonymous User should not see the link to the listing.
    $assert->pageTextNotContains('Content Entity Example: Contacts Listing');

    $this->drupalLogin($web_user);

    // Web_user user has the right to view listing.
    $assert->linkExists('Content Entity Example: Contacts Listing');

    $this->clickLink('Content Entity Example: Contacts Listing');

    // WebUser can add entity content.
    $assert->linkExists('Add Contact');

    $this->clickLink(t('Add Contact'));

    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');
    $assert->fieldValueEquals('name[0][value]', '');

    $user_ref = $web_user->name->value . ' (' . $web_user->id() . ')';
    $assert->fieldValueEquals('user_id[0][target_id]', $user_ref);

    // Post content, save an instance. Go back to list after saving.
    $edit = array(
      'name[0][value]' => 'test name',
      'first_name[0][value]' => 'test first name',
      'gender' => 'male',
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));

    // Entity listed.
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    $this->clickLink('test name');

    // Entity shown.
    $assert->pageTextContains('test name');
    $assert->pageTextContains('test first name');
    $assert->pageTextContains('male');
    $assert->linkExists('Add Contact');
    $assert->linkExists('Edit');
    $assert->linkExists('Delete');

    // Delete the entity.
    $this->clickLink('Delete');

    // Confirm deletion.
    $assert->linkExists('Cancel');
    $this->drupalPostForm(NULL, array(), 'Delete');

    // Back to list, must be empty.
    $assert->pageTextNotContains('test name');

    // Settings page.
    $this->drupalGet('admin/structure/content_entity_example_contact_settings');
    $assert->pageTextContains('Contact Settings');

    // Make sure the field manipulation links are available.
    $assert->linkExists('Settings');
    $assert->linkExists('Manage fields');
    $assert->linkExists('Manage form display');
    $assert->linkExists('Manage display');
  }

  /**
   * Test all paths exposed by the module, by permission.
   */
  public function testPaths() {
    $assert = $this->assertSession();

    // Generate a contact so that we can test the paths against it.
    $contact = Contact::create(
      array(
        'name' => 'somename',
        'first_name' => 'Joe',
        'gender' => 'female',
      )
    );
    $contact->save();

    // Gather the test data.
    $data = $this->providerTestPaths($contact->id());

    // Run the tests.
    foreach ($data as $datum) {
      // drupalCreateUser() doesn't know what to do with an empty permission
      // array, so we help it out.
      if ($datum[2]) {
        $user = $this->drupalCreateUser(array($datum[2]));
        $this->drupalLogin($user);
      }
      else {
        $user = $this->drupalCreateUser();
        $this->drupalLogin($user);
      }
      $this->drupalGet($datum[1]);
      $assert->statusCodeEquals($datum[0]);
    }
  }

  /**
   * Data provider for testPaths.
   *
   * @param int $contact_id
   *   The id of an existing Contact entity.
   *
   * @return array
   *   Nested array of testing data. Arranged like this:
   *   - Expected response code.
   *   - Path to request.
   *   - Permission for the user.
   */
  protected function providerTestPaths($contact_id) {
    return array(
      array(
        200,
        '/content_entity_example_contact/' . $contact_id,
        'view contact entity',
      ),
      array(
        403,
        '/content_entity_example_contact/' . $contact_id,
        '',
      ),
      array(
        200,
        '/content_entity_example_contact/list',
        'view contact entity',
      ),
      array(
        403,
        '/content_entity_example_contact/list',
        '',
      ),
      array(
        200,
        '/content_entity_example_contact/add',
        'add contact entity',
      ),
      array(
        403,
        '/content_entity_example_contact/add',
        '',
      ),
      array(
        200,
        '/content_entity_example_contact/' . $contact_id . '/edit',
        'edit contact entity',
      ),
      array(
        403,
        '/content_entity_example_contact/' . $contact_id . '/edit',
        '',
      ),
      array(
        200,
        '/contact/' . $contact_id . '/delete',
        'delete contact entity',
      ),
      array(
        403,
        '/contact/' . $contact_id . '/delete',
        '',
      ),
      array(
        200,
        'admin/structure/content_entity_example_contact_settings',
        'administer contact entity',
      ),
      array(
        403,
        'admin/structure/content_entity_example_contact_settings',
        '',
      ),
    );
  }

  /**
   * Test add new fields to the contact entity.
   */
  public function testAddFields() {
    $web_user = $this->drupalCreateUser(array(
      'administer contact entity',
      'administer content_entity_example_contact display',
      'administer content_entity_example_contact fields',
      'administer content_entity_example_contact form display',
    ));

    $this->drupalLogin($web_user);
    $entity_name = 'content_entity_example_contact';
    $add_field_url = 'admin/structure/' . $entity_name . '_settings/fields/add-field';
    $this->drupalGet($add_field_url);
    $field_name = 'test_name';
    $edit = array(
      'new_storage_type' => 'list_string',
      'label' => 'test name',
      'field_name' => $field_name,
    );

    $this->drupalPostForm(NULL, $edit, t('Save and continue'));
    $expected_path = $this->buildUrl('admin/structure/' . $entity_name . '_settings/fields/' . $entity_name . '.' . $entity_name . '.field_' . $field_name . '/storage');

    // Fetch url without query parameters.
    $current_path = strtok($this->getUrl(), '?');
    $this->assertEquals($expected_path, $current_path);
  }

}
