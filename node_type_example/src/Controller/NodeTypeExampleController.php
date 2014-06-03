<?php

/**
 * @file
 * Contains \Drupal\page_example\Controller\NodeTypeExampleController.
 */

namespace Drupal\node_type_example\Controller;

/**
 * Controller routines for node_type_example.
 *
 * @ingroup node_type_example
 */
class NodeTypeExampleController {

  /**
   * A simple controller method to explain what this module is about.
   */
  public function description() {
    $build = array(
      '#markup' => t(
          '<p>Config Node Type Example is a basic example of defining a content type through configuration YAML files.</p>
<p>In this example we create two content types for Drupal 8, using only YAML files. Well, mostly only YAML files... One of our content types is locked, so the user can&#39;t delete it while the module is installed. For this we need a very tiny amount of support code.</p>
<p>You can observe these content types on <a href="@contentadmin">the content type admin page</a>.</p>
<p>The simplest way to author the per-type YAML files is to create the content types within Drupal and then take the YAML files from the configuration
directory. Like this:</p>
<ul>
<li>Install Drupal 8.</li>
<li>Create a new content type at <code>admin/structure/types/add</code>. Let&#39;s call it &#39;Nifty Content Type&#39;.</li>
<li>Look in <code>sites/default/files/config_[some hex codes]/active/</code>. You&#39;ll see a file called <code>node.type.nifty_content_type.yml</code>.</li>
<li>Copy or move that file to your module&#39;s <code>config/</code> directory.</li>
</ul>
<p>You can see two of these YAML files in this module&#39;s config/ directory.</p>
<p>If you want to lock a content type created in this way, you&#39;ll have to implement <code>hook_install()</code> and <code>hook_uninstall()</code>. In <code>hook_install()</code>, you&#39;ll set the content type to be locked. In <code>hook_uninstall()</code> you&#39;ll set the content type to be unlocked.</p>
<p>Content types created in this way will remain available after the user has uninstalled the module. If you were to fail to set the content type as unlocked, the user would not be able to delete it.</p>
<p>This example is based on this change notification: <a href="https://drupal.org/node/2029519">https://drupal.org/node/2029519</a></p>', array('@contentadmin' => url('admin/structure/types'))),
    );
    return $build;
  }

}
