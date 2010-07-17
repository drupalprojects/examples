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
      'path' => 'examples/ahah_example/autocheckboxes/callback',
      'wrapper' => 'checkboxes',
      'effect' => 'fade',
      // 'event' => 'change', // default value: does not need to be set explicitly.
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
    if (isset($form_state['values']["checkbox$i"])) {
      $form['checkboxes']["checkbox$i"]['#default_value'] = $form_state['values']["checkbox$i"];
    }
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
  $form = ahah_example_callback_helper();
	
  $checkboxes = $form['checkboxes'];
  // Remove the wrapper so we don't double it up.
  unset($checkboxes['#prefix'], $checkboxes['#suffix']);

  $output = theme('status_messages');  
  $output .= drupal_render($checkboxes);
  
  // Final rendering callback.
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}