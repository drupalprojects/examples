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
      'path' => 'ahah_example/autotextfields/callback',
      'wrapper' => 'textfields',
      'effect' => 'fade',
    )
  );
  $form['ask_last_name'] = array(
   '#type' => 'checkbox',
   '#title' => t('Ask me my last name'),
   '#default_value' => $default_last_name,

    '#ahah' => array(
      'path' => 'ahah_example/autotextfields/callback',
      'wrapper' => 'textfields',
      'effect' => 'fade',

    ),
  );

  $form['textfields'] = array(
    '#title' => t("Generated text fields for first and last name"),
    '#prefix' => '<div id="textfields">',
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
  $form_state = array('storage' => NULL, 'submitted' => FALSE);
  $form_build_id = $_POST['form_build_id'];
  $form = form_get_cache($form_build_id, $form_state);

  $args = $form['#parameters'];
  $form_id = array_shift($args);
  $form_state['post'] = $form['#post'] = $_POST;
  $form['#programmed'] = $form['#redirect'] = FALSE;

  drupal_process_form($form_id, $form, $form_state);
  $form = drupal_rebuild_form($form_id, $form_state, $args, $form_build_id);

  $textfields = $form['textfields'];
  $output = drupal_render($textfields);

  // Final rendering callback.
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}