<?php
// $Id$

/**
 * @file
 * Administrative pages for the module.
 *
 * Contains form building functions, submit handlers, and theme functions for
 * the module's overview form, add and edit forms, and the delete confirmation
 * form.
 */

/**
 * Build an overview form with drag and drop re-ordering of records.
 *
 * Loads all records and builds an overview form with weight elements for each
 * record, then adds Drupal's tabledrag.js file. Because ALL records will be
 * displayed on this page, this style of overview is best suited to small sets
 * of data.
 *
 * If your module will work with large numbers of records, or if you don't need
 * the drag-and-drop re-ordering feature, the paged version of the overview
 * is likly be a better solution.
 *
 * @ingroup forms
 * @see _scaffolding_example_overview_record_field()
 * @see scaffolding_example_overview_form_submit()
 * @see theme_scaffolding_example_overview_form()
 * @see scaffolding_example_overview_pager()
 */
function scaffolding_example_overview_form(&$form_state) {
  $records = scaffolding_example_record_load_all();

  $form['records']['#tree'] = TRUE;
  foreach ($records as $record_id => $record) {
    $form['records'][$record_id] = _scaffolding_example_overview_record_field($record);
  }

  $form['buttons']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save changes'),
    '#disabled' => empty($records),
  );

  return $form;
}

/**
 * Builds the fields for a single record on the drag-and-drop overview form.
 *
 * This internal function should not be called outside the module, unless you're
 * feeling particularly cheeky.
 *
 * @ingroup forms
 * @see scaffolding_example_overview_form()
 */
function _scaffolding_example_overview_record_field($record) {
  $form['record_id'] = array(
    '#type' => 'hidden',
    '#value' => $record['record_id'],
  );

  $form['title'] = array(
    '#type' => 'markup',
    '#value' => check_plain($record['title']),
  );

  $form['weight'] = array(
    '#type' => 'weight',
    '#default_value' => $record['weight'],
  );

  $form['operations'] = array(
    '#type' => 'markup',
    '#value' => _scaffolding_example_record_links($record),
  );

  return $form;
}

/**
 * Build the edit and delete links for a single record.
 *
 * @see scaffolding_example_overview_form()
 * @see scaffolding_example_overview_pager()
 */
function _scaffolding_example_record_links($record) {
  $path = drupal_get_path('module', 'scaffolding_example') . '/images/';
  $links['edit'] = array(
    'title' => theme('image', $path . 'text-editor.png', t('Edit')),
    'href' => 'admin/build/scaffolding_example/' . $record['record_id'] . '/edit',
    'html' => TRUE,
    'query' => drupal_get_destination(),
  );
  $links['delete'] = array(
    'title' => theme('image', $path . 'edit-delete.png', t('Delete')),
    'href' => 'admin/build/scaffolding_example/' . $record['record_id'] . '/delete',
    'html' => TRUE,
    'query' => drupal_get_destination(),
  );
  return theme('links', $links);
}

/**
 * General submit handler for the drag-and-drop overview form.
 *
 * Updates the weights of all records on the form.
 *
 * @ingroup formapi
 * @see scaffolding_example_overview_form()
 */
function scaffolding_example_overview_form_submit($form, &$form_state) {
  $records = $form_state['values']['records'];
  foreach ($records as $record) {
    scaffolding_example_record_save($record);
  }
}

/**
 * Theme the drag-and-drop overview form.
 *
 * Arranges records in a table, and adds the css and js for draggable sorting.
 *
 * @ingroup themeable
 * @ingroup forms
 * @see scaffolding_example_overview_form()
 */
function theme_scaffolding_example_overview_form($form) {
  // Each record has a 'weight' that can be used to arrange it in relation to
  // other records. Drupal's tabledrag.js library allows users to control these
  // weights by dragging and dropping the records in a list -- we just need to
  // add identifying CSS classes to key elements in the table.

  $rows = array();
  foreach (element_children($form['records']) as $key) {
    $row = array();

    // Render the hidden 'record id' field and the title of the record into the
    // same column of the row.
    $row[] = drupal_render($form['records'][$key]['record_id']) . drupal_render($form['records'][$key]['title']);

    // Add an identifying CSS class to our weight field, as it's the one
    // the tabledrag.js will be controlling. This can be anything we want it to
    // be, we'll just tell the tabledrag.js library what it should look for.
    $form['records'][$key]['weight']['#attributes']['class'] = 'scaffolding-example-weight';
    $row[] = drupal_render($form['records'][$key]['weight']);

    // Render the edit and delete links into their own column.
    $row[] = drupal_render($form['records'][$key]['operations']);

    // Add the new row to our collection of rows, and give it the 'draggable'
    // class, indicating that it should be... well, draggable.
    $rows[] = array(
      'data' => $row,
      'class' => 'draggable',
    );
  }

  // If there were no records found, note the fact so users don't get confused
  // by a completely empty table.
  if (count($rows) == 0) {
    $rows[] = array(t('No records have been added.'), '<span class="scaffolding-example-weight"></span>', '');
  }

  // Render a list of header titles, and our array of rows, into a table. Even
  // we've already rendered all of our records, we always call drupal_render()
  // on the form itself after we're done, so hidden security fields and other
  // elements (like buttons) will appear properly at the bottom of the form.
  $header = array(t('Title'), t('Weight'), t('Operations'));
  $output = theme('table', $header, $rows, array('id' => 'scaffolding-example-overview'));
  $output .= drupal_render($form);

  // Now that we've built our output, tell Drupal to add the tabledrag.js library.
  // We'll pass in the ID of the table, the behavior we want it to use, and the
  // class that appears on each 'weight' form element it should be controlling.
  drupal_add_tabledrag('scaffolding-example-overview', 'order', 'self', 'scaffolding-example-weight');

  return $output;
}

/**
 * Builds a sortable, paged overview of all records.
 *
 * This version of the overview page doesn't allow administrators to re-order
 * records, but it breaks up long lists into groups of 20, with a convenient
 * pager at the bottom of the list of records. This style of overview page is
 * very useful when you know you'll be managing dozens (or hundreds) of records.
 *
 * Because this version of the overview page uses Drupal's helper functions to
 * build a paged table with clickable column-headers, it can't use the module's
 * build-in scaffolding_example_record_load_all() function. Instead, it will
 * build its own SQL in a way that works with the Pager and Sortable Table APIs.
 *
 * @see scaffolding_example_overview_form()
 */
function scaffolding_example_overview_pager() {
  $sql = "SELECT * FROM {scaffolding_record}";
  $header = array(
    array('data' => t('Title'), 'field' => 'title', 'sort' => 'asc'),
    array('data' => t('Weight'), 'field' => 'weight'),
    t('Operations'),
  );
  $sql .= tablesort_sql($header);

  $limit = 10;
  $result = pager_query($sql, $limit);

  while ($record = db_fetch_array($result)) {
    // single row element
    $row = array();
    $row[] = check_plain($record['title']);
    $row[] = check_plain($record['weight']);
    $row[] = _scaffolding_example_record_links($record);

    // Add this element to the table
    $rows[] = $row;
  }
  if (!isset($rows)) {
    $rows[] = array(array('data' => t('No records have been added.'), 'colspan' => 3));
  }

  $output = theme('table', $header, $rows);
  $output .= theme('pager', NULL, $limit);

  return $output;
}

/**
 * Build the record editing form.
 *
 * If a record is passed in, an edit form with both Save and Delete buttons will
 * be built. Otherwise, a blank 'add new record' form, without the Delete button,
 * will be built.
 *
 * @ingroup forms
 * @see scaffolding_example_form_submit()
 * @see scaffolding_example_form_delete()
 */
function scaffolding_example_form(&$form_state, $record = array()) {
  // Set the default values for a new item. By using += rather than =, we
  // only overwrite array keys that have not yet been set. It's safe to use
  // on both an empty array, and an incoming array with full or partial data.
  $record += array(
    'title' => '',
    'content' => '',
    'weight' => 0,
  );

  // If we're editing an existing record, we'll add a value field to the form
  // containing the record's unique ID.
  if (!empty($record['record_id'])) {
    $form['record_id'] = array(
      '#type' => 'value',
      '#value' => $record['record_id'],
    );
  }

  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#required' => TRUE,
    '#default_value' => $record['title'],
  );

  $form['weight'] = array(
    '#type' => 'weight',
    '#title' => t('Weight'),
    '#default_value' => $record['weight'],
  );

  $form['content'] = array(
    '#type' => 'textarea',
    '#title' => t('Content'),
    '#required' => TRUE,
    '#default_value' => $record['content'],
  );

  $form['buttons']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  // Only show the delete button if we already have an ID. Set the delete
  // button's submit handler to a custom function that should only fire if
  // this button is clicked. In all other cases, the form will fall back to
  // the default $form_id_submit() function.
  if (!empty($record['record_id'])) {
    $form['buttons']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
      '#submit' => array('scaffolding_example_form_delete'),
    );
  }

  return $form;
}

/**
 * General submit handler for Scaffolding's add/edit form.
 *
 * Simply passes incoming form values on to the module's CRUD save function,
 * then redirects to the overview form.
 *
 * @ingroup formapi
 * @see scaffolding_example_form()
 */
function scaffolding_example_form_submit($form, &$form_state) {
  $record = $form_state['values'];
  scaffolding_example_record_save($record);
  $form_state['redirect'] = 'admin/build/scaffolding_example';
}

/**
 * Delete button submit handler for Scaffolding's add/edit form.
 *
 * Redirects to the 'delete record' confirmation page without performing any
 * operations.
 *
 * @ingroup formapi
 * @see scaffolding_example_form()
 */
function scaffolding_example_form_delete($form, &$form_state) {
  $form_state['redirect'] = 'admin/build/scaffolding_example/' . $form_state['values']['record_id'] . '/delete';
}

/**
 * Build the delete confirmation form.
 *
 * A simple wrapper around Drupal's core confirm_form() function. Adds a value
 * field to store the ID of the record being deleted.
 *
 * @ingroup forms
 * @see scaffolding_example_delete_confirm_submit()
 * @see confirm_form()
 */
function scaffolding_example_delete_confirm(&$form_state, $record) {
  $form['record_id'] = array(
    '#type' => 'value',
    '#value' => $record['record_id'],
  );

  return confirm_form($form,
    t('Are you sure you want to delete %title?', array('%title' => $record['title'])),
    isset($_GET['destination']) ? $_GET['destination'] : 'admin/build/scaffolding_example',
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * General submit handler for the delete confirmation form.
 *
 * Core's confirm_form() function adds the 'confirm' value element we check
 * against to ensure the form was properly submitted. If it's there, delete
 * the record and redirect to the overview form.
 *
 * @ingroup formapi
 * @see scaffolding_example_form()
 */

function scaffolding_example_delete_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm']) {
    scaffolding_example_record_delete($form_state['values']['record_id']);
    drupal_set_message(t('Your record was deleted.'));
  }
  $form_state['redirect'] = 'admin/build/scaffolding_example';
}
