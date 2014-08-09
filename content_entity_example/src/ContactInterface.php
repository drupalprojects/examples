<?php
/**
 * @file
 * Contains \Drupal\content_entity_example\ContactInterface.
 */

namespace Drupal\content_entity_example;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a Contact entity.
 * @ingroup content_entity_example
 */
interface ContactInterface extends ContentEntityInterface, EntityOwnerInterface {

}
