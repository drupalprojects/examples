<?php

/**
 * @file
 * Contains \Drupal\field_example\Plugin\field\widget\TextWidget.
 */

namespace Drupal\field_example\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_example_text' widget.
 *
 * @FieldWidget(
 *   id = "field_example_text",
 *   module = "field_example",
 *   label = @Translation("RGB value as #ffffff"),
 *   field_types = {
 *     "field_example_rgb"
 *   }
 * )
 */
class TextWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $element += array(
      '#type' => 'color',
      '#default_value' => $value,
      // Allow a slightly larger size that the field length to allow for some
      // configurations where all characters won't fit in input field.
      '#size' => 7,
      '#maxlength' => 7,
      '#element_validate' => array(
        'form_validate_color',
      ),
    );
    return array('value' => $element);
  }

}
