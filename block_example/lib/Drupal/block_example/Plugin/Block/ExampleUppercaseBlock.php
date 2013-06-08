<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleUppercaseBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;

/**
 * Provides a 'Example: uppercase this please' block.
 *
 * @Plugin(
 *   id = "example_uppercase",
 *   subject = @Translation("uppercase this please"),
 *   admin_label = @Translation("Example: uppercase this please"),
 *   module = "block_example"
 * )
 */
class ExampleUppercaseBlock extends BlockBase {

  /**
   * Implements \Drupal\block\BlockBase::blockBuild().
   */
  public function blockBuild() {
    return array(
      '#type' => 'markup',
      '#markup' => t("This block's title will be changed to uppercase. Any other block with 'uppercase' in the subject or title will also be altered. If you change this block's title through the UI to omit the word 'uppercase', it will still be altered to uppercase as the subject key has not been changed."),
    );
  }

}
