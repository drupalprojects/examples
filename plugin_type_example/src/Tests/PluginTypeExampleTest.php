<?php

namespace Drupal\plugin_type_example\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\plugin_type_example\Plugin\Sandwich\ExampleHamSandwich;

/**
 * Test the functionality of the Plugin Type Example module.
 *
 * @ingroup plugin_type_example
 *
 * @group plugin_type_example
 * @group examples
 */
class PluginTypeExampleTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('plugin_type_example');

  /**
   * The installation profile to use with this test.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Test the plugin manager can be loaded, and the plugins are registered.
   */
  public function testPluginExample() {
    /* @var $manager \Drupal\plugin_type_example\SandwichPluginManager */
    $manager = $this->container->get('plugin.manager.sandwich');

    $sandwich_plugin_definitions = $manager->getDefinitions();

    // Ensure we have two sandwich plugins defined.
    $this->assertEqual(count($sandwich_plugin_definitions), 2, 'There are two sandwich plugins defined.');

    // Check some of the properties of the ham sandwich plugin definition.
    $sandwich_plugin_definition = $sandwich_plugin_definitions['ham_sandwich'];
    $this->assertEqual($sandwich_plugin_definition['calories'], 426, 'The ham sandwich plugin definition\'s calories property is set.');

    // Check the alter hook fired and changed a property.
    $this->assertIdentical($sandwich_plugin_definition['description'], 'Ham, mustard, ROCKET, sun-dried tomatoes.', 'The ham sandwich plugin definition\'s description property is set, and was correctly altered by the plugin info alter hook.');

    // Create an instance of the ham sandwich plugin to check it works.
    $plugin = $manager->createInstance('ham_sandwich', array('of' => 'configuration values'));

    $this->assertEqual(get_class($plugin), ExampleHamSandwich::class, 'The ham sandwich plugin is instantiated and of the correct class.');

    // Create a meatball sandwich so we can check it's special behavior on
    // Sundays.
    /* @var $meatball \Drupal\plugin_type_example\SandwichInterface */
    $meatball = $manager->createInstance('meatball_sandwich');
    // Set the $day property to 'Sun'.
    $ref_day = new \ReflectionProperty($meatball, 'day');
    $ref_day->setAccessible(TRUE);
    $ref_day->setValue($meatball, 'Sun');
    // Check the special description on Sunday.
    $this->assertEqual($meatball->description(), 'Italian style meatballs drenched in irresistible marinara sauce, served on day old bread.');
  }

  /**
   * Test the output of the example page.
   */
  public function testPluginExamplePage() {
    $this->drupalGet('examples/plugin-type-example');
    $this->assertResponse(200, 'Example page successfully accessed.');

    // Check we see the plugin id.
    $this->assertText(t('ham_sandwich'), 'The plugin ID is output.');

    // Check we see the plugin description.
    $this->assertText(t('Ham, mustard, ROCKET, sun-dried tomatoes.'), 'The plugin description is output.');
  }

}
