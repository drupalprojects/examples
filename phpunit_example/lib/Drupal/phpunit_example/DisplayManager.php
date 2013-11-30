<?php

/**
 * @file
 * Contains Drupal\phpunit_example\DisplayManager
 */

namespace Drupal\phpunit_example;

use Drupal\phpunit_example\DisplayInfoInterface;

/**
 * An example class to demonstrate unit testing.
 *
 * Think of this class as a class that collects DisplayInfoInterface
 * objects, because that's what it is. It also might go on to one day
 * display lists of info about these info objects.
 *
 * But it never will, because it's just an example class.
 *
 * Part of the PHPUnit Example module.
 *
 * @ingroup phpunit_example
 */
class DisplayManager {

  protected $items;

  public function addDisplayableItem(DisplayInfoInterface $item) {
    $this->items[$item->getDisplayName()] = $item;
  }

  public function countDisplayableItems() {
    return count($this->items);
  }

  public function displayableItems() {
    return $this->items;
  }

  public function item($name) {
    if (isset($this->items[$name])) {
      return $this->items[$name];
    }
    return NULL;
  }

}
