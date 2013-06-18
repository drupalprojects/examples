<?php

/**
 * @file
 * Contains \Drupal\page_example\Controller\PageExampleController.
 */

namespace Drupal\page_example\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Controller routines for filter routes.
 */
class PageExampleController {

  /**
   * Displays a page with a descriptive page.
   *
   * Our router maps this method to the path 'examples/page_example'.
   */
  function description() {
    $build = array(
      '#markup' => t('<p>The page_example provides two pages, "simple" and "arguments".</p><p>The <a href="@simple_link">simple page</a> just returns a renderable array for display.</p><p>The <a href="@arguments_link">arguments page</a> takes two arguments and displays them, as in @arguments_link</p>', array('@simple_link' => url('examples/page_example/simple', array('absolute' => TRUE)), '@arguments_link' => url('examples/page_example/arguments/23/56', array('absolute' => TRUE))))
    );

    return $build;
  }

  /**
   * Constructs a simple page.
   *
   * The simple _content callback, mapped to the path
   * 'examples/page_example/simple'.
   *
   * _content callbacks return a renderable array with the content area of the
   * page. The theme system will later render and surround the content in the
   * appropriate blocks, navigation, and styling.
   *
   * @todo: Is this paragraph valid?
   * If you do not want to use the theme system (for example for outputting an
   * image or XML), just return the right response object.
   */
  function simple() {
    return array(
      '#markup' => '<p>' . t('Simple page: The quick brown fox jumps over the lazy dog.') . '</p>'
    );
  }

  /**
   * A more complex _content callback that takes arguments.
   *
   * This callback is mapped to the path
   * 'examples/page_example/arguments/{first}/{second}'.
   *
   * The arguments in brackets are passed to this callback from the page URL.
   *
   * This function also demonstrates a more complex render array in the returned
   * values. Instead of just rendering the HTML with a theme('item_list'), the
   * list is left unrendered, and a #theme attached to it so that it can be
   * rendered as late as possible, giving more parts of the system a chance to
   * change it if necessary.
   *
   * Consult @link http://drupal.org/node/930760 Render Arrays documentation
   * @endlink for details.
   *
   * @param string $first
   *   A string to use, should be a number.
   * @param string $second
   *   Another string to use, should be a number.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   If the parameters are invalid.
   */
  function arguments($first, $second) {
    // Make sure you don't trust the URL to be safe! Always check for exploits.
    if (!is_numeric($first) || !is_numeric($second)) {
      // We will just show a standard "access denied" page in this case.
      throw new AccessDeniedHttpException();
    }

    $list[] = t("First number was @number.", array('@number' => $first));
    $list[] = t("Second number was @number.", array('@number' => $second));
    $list[] = t('The total was @number.', array('@number' => $first + $second));

    $render_array['page_example_arguments'] = array(
      // The theme function to apply to the #items
      '#theme' => 'item_list',
      // The list itself.
      '#items' => $list,
      '#title' => t('Argument Information'),
    );
    return $render_array;
  }
}
