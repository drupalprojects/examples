<?php

/**
 * @file
 * Definition of Drupal\field_example\Tests\FieldWebTestBase.
 */

namespace Drupal\field_example;

use Drupal\Core\Session\AccountInterface;
use Drupal\simpletest\WebTestBase;

class FieldExampleWebTestBase extends WebTestBase {

  /**
   * @var string
   */
  protected $contentTypeName;

  /**
   * @var AccountInterface
   */
  protected $administratorAccount;

  /**
   * @var AccountInterface
   */
  protected $authorAccount;

  /**
   * @var string
   */
  protected $fieldName;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('node', 'field_ui', 'field_example');

  /**
   * {@inheritdoc}
   *
   * Once installed, a content type with the desired field is created
   */
  protected function setUp() {
    // Install Drupal.
    parent::setUp();

    // Create and login a user that creates the content type.
    $permissions = array(
      'administer content types',
      'administer node fields',
      'administer node form display',
    );
    $this->administratorAccount = $this->drupalCreateUser($permissions);
    parent::drupalLogin($this->administratorAccount);

    // Prepare a new content type where the field will be added.
    $this->contentTypeName = strtolower($this->randomMachineName(10));
    $this->drupalGet('admin/structure/types/add');
    $edit = array(
      'name' => $this->contentTypeName,
      'type' => $this->contentTypeName,
    );
    $this->drupalPostForm(NULL, $edit, t('Save and manage fields'));
    $this->assertText(t('The content type @name has been added.', array('@name' => $this->contentTypeName)));

    // Reset the permission cache.
    $create_permission = 'create ' . $this->contentTypeName . ' content';
    $this->checkPermissions(array($create_permission), TRUE);

    // Now that we have a new content type, create a user that has privileges
    // on the content type.
    $this->authorAccount = $this->drupalCreateUser(array($create_permission));
  }

  /**
   * Create a field on the content type created during setUp().
   *
   * @param string $type
   *   The storage field type to create
   * @param string $widget_type
   *   The widget to use when editing this field
   * @param int|string $cardinality
   *   Cardinality of the field. Use -1 to signify 'unlimited.'
   *
   * @return string
   *   Name of the field, like field_something
   */
  protected function createField($type = 'field_example_rgb', $widget_type = 'field_example_text', $cardinality = '1') {
    $this->drupalGet('admin/structure/types/manage/' . $this->contentTypeName . '/fields');

    $field_name = strtolower($this->randomMachineName(10));
    // Add a singleton field_example_text field.
    $edit = array(
      'fields[_add_new_field][label]' => $field_name,
      'fields[_add_new_field][field_name]' => $field_name,
      'fields[_add_new_field][type]' => $type,
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));

    // If we get -1 for $cardinality, we should change the drop-down from
    // 'Number' to 'Unlimited.'
    if (-1 == $cardinality) {
      $edit = array(
        'field_storage[cardinality]' => '-1',
      );
    }
    // Otherwise set the cardinality number.
    else {
      $edit = array(
        'field_storage[cardinality_number]' => (string) $cardinality,
      );
    }

    // Using all the default settings, so press the button.
    $this->drupalPostForm(NULL, $edit, t('Save field settings'));
    debug(
      t('Saved settings for field %field_name with widget %widget_type and cardinality %cardinality',
        array(
          '%field_name' => $field_name,
          '%widget_type' => $widget_type,
          '%cardinality' => $cardinality,
        )
      )
    );
    $this->assertText(t('Updated field @name field settings.', array('@name' => $field_name)));

    // Set the widget type for the newly created field.
    $this->drupalGet('admin/structure/types/manage/' . $this->contentTypeName . '/form-display');
    $edit = array(
      'fields[field_' . $field_name . '][type]' => $widget_type,
    );
    $this->drupalPostForm(NULL, $edit, t('Save'));

    return $field_name;
  }

}
