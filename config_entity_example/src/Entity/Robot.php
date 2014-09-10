<?php

/**
 * @file
 * Contains Drupal\config_entity_example\Entity\Robot.
 *
 * This contains our entity class.
 *
 * Originally based on code from blog post at
 * http://previousnext.com.au/blog/understanding-drupal-8s-config-entities
 */

namespace Drupal\config_entity_example\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the robot entity.
 *
 * The lines below, starting with '@ConfigEntityType,' are a plugin annotation.
 * These define the entity type to the entity type manager.
 *
 * The properties in the annotation are as follows:
 *  - id: The machine name of the entity type.
 *  - label: The human-readable label of the entity type. We pass this through
 *    the "@Translation" wrapper so that the multilingual system may
 *    translate it in the user interface.
 *  - controllers: An array specifying controller classes that handle various
 *    aspects of the entity type's functionality. Below, we've specified
 *    controllers which can list, add, edit, and delete our robot entity, and
 *    which control user access to these capabilities.
 *  - config_prefix: This tells the config system the prefix to use for
 *    filenames when storing entities. This means that the default entity we
 *    include in our module has the filename
 *    'config_entity_example.robot.marvin.yml'.
 *  - entity_keys: Specifies the class properties in which unique keys are
 *    stored for this entity type. Unique keys are properties which you know
 *    will be unique, and which the entity manager can use as unique in database
 *    queries.
 *
 * @see http://previousnext.com.au/blog/understanding-drupal-8s-config-entities
 * @see annotation
 * @see Drupal\Core\Annotation\Translation
 *
 * @ingroup config_entity_example
 *
 * @ConfigEntityType(
 *   id = "robot",
 *   label = @Translation("Robot"),
 *   admin_permission = "administer robots",
 *   handlers = {
 *     "access" = "Drupal\config_entity_example\RobotAccessController",
 *     "list_builder" = "Drupal\config_entity_example\Controller\RobotListBuilder",
 *     "form" = {
 *       "add" = "Drupal\config_entity_example\Form\RobotAddForm",
 *       "edit" = "Drupal\config_entity_example\Form\RobotEditForm",
 *       "delete" = "Drupal\config_entity_example\Form\RobotDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "robot.edit",
 *     "delete-form" = "robot.delete"
 *   }
 * )
 */
class Robot extends ConfigEntityBase {

  /**
   * The robot ID.
   *
   * @var string
   */
  public $id;

  /**
   * The robot UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The robot label.
   *
   * @var string
   */
  public $label;

  /**
   * The robot floopy flag.
   *
   * @var string
   */
  public $floopy;
}
