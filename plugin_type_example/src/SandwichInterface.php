<?php
/**
 * @file
 * Provides Drupal\plugin_type_example\SandwichInterface.
 */

namespace Drupal\plugin_type_example;

/**
 * An interface for all Sandwich type plugins.
 */
interface SandwichInterface {

  /**
   * Provide a description of the sandwich.
   *
   * @return string
   *   A string description of the sandwich.
   */
  public function description();

}
