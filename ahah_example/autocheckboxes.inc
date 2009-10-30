<?php

/**
 * @file
 * A Self-configure a form based on a select control.
 * Add the number of checkboxes specified in the select.
 */
function ahah_example_autocheckboxes(&$form_state) {

  $default = !empty($form_state['values']['howmany']) ? $form_state['values']['howmany'] : 1;
  $form['howmany'] = array(
    '#title' => t('How many checkboxes do you want?'),
    '#type' => 'select',
    '#options' => array(1=>1, 2=>2, 3=>3, 4=>4),
    '#default_value' => $default,
    '#ahah' => array(
      'path' => 'ahah_example/autocheckboxes/callback',
      'wrapper' => 'checkboxes',
      'effect' => 'fade',
    ),

  );


  $form['checkboxes'] = array(
    '#title' => t("Generated Checkboxes"),
    '#prefix' => '<div id="checkboxes">',
    '#suffix' => '</div>',
    '#type' => 'fieldset',
    '#description' => t('This is where we get automatically generated checkboxes'),
  );

  $num_checkboxes = !empty($form_state['values']['howmany']) ? $form_state['values']['howmany'] : 1;
  for ($i=1; $i<=$num_checkboxes; $i++) {
    $form['checkboxes']["checkbox$i"] = array(
      '#type' => 'checkbox',
      '#title' => "Checkbox $i",
    );
  }

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Click Me'),
  );


  return $form;
}

/**
 * Callback for autocheckboxes. Process the form with the number of checkboxes
 * we want to provide.
 */
function ahah_example_autocheckboxes_callback() {
  $form_state = array('storage' => NULL, 'submitted' => FALSE);
  $form_build_id = $_POST['form_build_id'];
  $form = form_get_cache($form_build_id, $form_state);

  $args = $form['#parameters'];
  $form_id = array_shift($args);
  $form_state['post'] = $form['#post'] = $_POST;
  $form['#programmed'] = $form['#redirect'] = FALSE;

  // HACK: Select values changing never get recognized.
  unset ($form['howmany']['#value']);

  drupal_process_form($form_id, $form, $form_state);
  $form = drupal_rebuild_form($form_id, $form_state, $args, $form_build_id);

  $checkboxes = $form['checkboxes'];
  $output = drupal_render($checkboxes);

  // Final rendering callback.
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}