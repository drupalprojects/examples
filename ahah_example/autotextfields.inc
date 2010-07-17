<?php

/**
 * @file
 * Show/hide textfields based on checkbox clicks.
 */
function ahah_example_autotextfields(&$form_state) {

  $default_first_name = !empty($form_state['values']['ask_first_name']) ? $form_state['values']['ask_first_name'] : FALSE;
  $default_last_name = !empty($form_state['values']['ask_last_name']) ? $form_state['values']['ask_last_name'] : FALSE;
  $form['ask_first_name'] = array(
    '#type' => 'checkbox',
    '#title' => t('Ask me my first name'),
    '#default_value' => $default_first_name,
    '#ahah' => array(
      'path' => 'examples/ahah_example/autotextfields/callback',
      'wrapper' => 'textfield-wrapper',
      'effect' => 'fade',
    )
  );
  $form['ask_last_name'] = array(
   '#type' => 'checkbox',
   '#title' => t('Ask me my last name'),
   '#default_value' => $default_last_name,

    '#ahah' => array(
      'path' => 'examples/ahah_example/autotextfields/callback',
      'wrapper' => 'textfield-wrapper',
      'effect' => 'fade',
      // 'event' => 'change', // default value: does not need to be set explicitly.

    ),
  );

  $form['textfields'] = array(
    '#title' => t("Generated text fields for first and last name"),
    '#prefix' => '<div id="textfield-wrapper">',
    '#suffix' => '</div>',
    '#type' => 'fieldset',
    '#description' => t('This is where we put automatically generated textfields'),
  );

   if (!empty($form_state['values']['ask_first_name'])) {
    $form['textfields']['first_name'] = array(
      '#type' => 'textfield',
      '#title' => t('First Name'),
    );
  }
  if (!empty($form_state['values']['ask_last_name'])) {
    $form['textfields']['last_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Last Name'),
    );
  }


  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Click Me'),
  );


  return $form;
}


function ahah_example_autotextfields_callback() {
  $form = ahah_example_callback_helper();
	
  $textfields = $form['textfields'];
  
  // Remove the prefix/suffix wrapper so we don't double it up.
  unset($textfields['#prefix'], $textfields['#suffix']);
  
  // Render the output.
  $output = theme('status_messages');  
  $output .= drupal_render($textfields);

  // Final rendering callback.
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}