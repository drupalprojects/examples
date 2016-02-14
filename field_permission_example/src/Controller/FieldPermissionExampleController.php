<?php

/**
 * @file
 * Contains \Drupal\field_permission_example\Controller\FieldPermissionExampleController.
 */

namespace Drupal\field_permission_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Controller routines for field permission example routes.
 */
class FieldPermissionExampleController extends ControllerBase {

  /**
   * A simple controller method to explain what this example is about.
   */
  public function description() {
    // Make a link from a route to the permissions admin page.
    $url = Url::fromRoute('user.admin_permissions');
    $permissions_admin_link = $this->l($this->t('the permissions admin page'), $url);

    $build = [
      'description' => [
        '#theme' => 'field_permission_description',
        '#admin_link' => $permissions_admin_link,
      ],
    ];
    return $build;
  }

}
