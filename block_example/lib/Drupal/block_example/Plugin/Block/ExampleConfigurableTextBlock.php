<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleConfigurableTextBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;

/**
 * Provides a 'Example: configurable text string' block.
 *
 * @Plugin(
 *   id = "example_configurable_text",
 *   subject = @Translation("Title of first block (example_configurable_text)"),
 *   admin_label = @Translation("Title of first block (example_configurable_text)"),
 *   module = "block_example"
 * )
 */
class ExampleConfigurableTextBlock extends BlockBase {

  /**
   * Overrides \Drupal\block\BlockBase::settings().
   */
  public function settings() {
    return array(
      'block_example_string' => t('A default value. This block was created at %time', array('%time' => date('c'))),
      'cache' => DRUPAL_CACHE_PER_ROLE,
    );
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockForm().
   */
  public function blockForm($form, &$form_state) {
    $form['block_example_string_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Block contents'),
      '#size' => 60,
      '#description' => t('This text will appear in the example block.'),
      '#default_value' => $this->configuration['block_example_string'],
    );
    return $form;
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   */
  public function blockSubmit($form, &$form_state) {
    $this->configuration['block_example_string'] = $form_state['values']['block_example_string_text'];
  }

  /**
   * Implements \Drupal\block\BlockBase::blockBuild().
   */
  public function blockBuild() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->configuration['block_example_string'],
    );
  }

}
