<?php

/**
 * @file
 * Contains \Drupal\js_example\Controller\JsExampleController.
 */

namespace Drupal\js_example\Controller;

/**
 * Controller for js_example pages.
 *
 * @ingroup js_example
 */
class JsExampleController {

  /**
   * js_example info page.
   */
  public function info() {
    $build ['content'] = array(
      '#markup' =>
'<p>Drupal includes jQuery and jQuery UI.</p>
<p>We have two examples of using these:</p>
<ol>
<li>An accordion-style section reveal effect. This demonstrates calling a jQuery UI function using Drupal&#39;s rendering system.</li>
<li>Sorting according to numeric &#39;weight.&#39; This demonstrates attaching your own JavaScript code to individual page elements using Drupal&#39;s rendering system.</li>
</ol>',
    );
    return $build;
  }

  /**
   * Weights implementation.
   */
  public function getJsWeightImplementation() {
    // Create an array of items with random-ish weight values.
    $weights = array(
      'red' => 100,
      'blue' => 23,
      'green' => 3,
      'brown' => 45,
      'black' => 5,
      'purple' => 60
    );

    // Start building the content.
    $build = array();
    // Main container DIV.
    $build['content'] = array(
      '#markup' => '<div id="js-weights"></div>'
    );
    // Attach a CSS file to show which line is output by which script
    $build['#attached']['css'] = array(drupal_get_path('module', 'js_example') .
      '/css/jsweights.css');
    // Attach some javascript files.
    $build['#attached']['js'] = array(
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/red.js',
        'weight' => $weights['red']
      ),
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/blue.js',
        'weight' => $weights['blue']
      ),
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/green.js',
        'weight' => $weights['green']
      ),
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/brown.js',
        'weight' => $weights['brown']
      ),
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/black.js',
        'weight' => $weights['black']
      ),
      array(
        'data' => drupal_get_path('module', 'js_example') . '/js/purple.js',
        'weight' => $weights['purple']
      ),
    );
    // Attach the weighted array to our JavaScript settings.
    $build['#attached']['js'][] = array(
      'type' => 'setting',
      'data' => array('js_weights' => $weights),
    );

    return $build;
  }

  /**
   * Accordion page implementation.
   *
   * We're allowing a twig template to define our content in this case,
   * which isn't normally how things work, but it's easier to demonstrate
   * the JavaScript this way.
   */
  function getJsAccordionImplementation() {
    $title = t('Click sections to expand or collapse:');
    $build['myelement'] = array(
      '#theme' => 'my_accordion',
      '#title' => $title,
    );
    $build['myelement']['#attached']['library'][] = array(
      'system',
      'jquery.ui.accordion',
    );
    $build['myelement']['#attached']['js'][] = array(
      'data' => '(function($){$(function() { $("#accordion").accordion(); })})(jQuery);',
      'type' => 'inline',
    );
    return $build;
  }

}
