<?php

namespace Drupal\fapi_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;

/**
 * Simple page controller for drupal.
 */
class Page extends ControllerBase {

  /**
   * Lists the examples provided by form_example.
   */
  public function description() {
    // This library is required to facilitate the ajax modal form demo.
    $content['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $content['intro'] = [
      '#markup' => '<p>' . $this->t('Form examples to demonstrate common UI solutions using the Drupal Form API.') . '</p>',
    ];

    // Create a list of links to the form examples.
    $content['links'] = [
      '#theme' => 'item_list',
      '#items' => [
        Link::createFromRoute($this->t('Simple Form'), 'fapi_example.simple_form'),
        Link::createFromRoute($this->t('Multistep Form'), 'fapi_example.multistep_form'),
        Link::createFromRoute($this->t('Common Input Elements'), 'fapi_example.input_demo'),
        Link::createFromRoute($this->t('Build Form Demo'), 'fapi_example.build_demo'),
        Link::createFromRoute($this->t('Container Elements'), 'fapi_example.container_demo'),
        Link::createFromRoute($this->t('Form State Binding'), 'fapi_example.state_demo'),
        Link::createFromRoute($this->t('Vertical Tab Elements'), 'fapi_example.vertical_tabs_demo'),
        Link::createFromRoute($this->t('Ajax Form'), 'fapi_example.ajax_demo'),
        Link::createFromRoute($this->t('Add-more Button'), 'fapi_example.ajax_addmore'),

        // Attributes are used by the core dialog libraries to invoke the modal.
        Link::createFromRoute(
          $this->t('Modal Form'),
          'fapi_example.modal_form',
           [],
           [
             'attributes' => [
               'class' => ['use-ajax'],
               'data-dialog-type' => 'modal',
             ],
           ]
        ),
      ],
    ];

    // The message container is used by the modal form example. It is an empty
    // tag that will be replaced by content.
    $content['message'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'fapi-example-message'],
    ];
    return $content;
  }

}
