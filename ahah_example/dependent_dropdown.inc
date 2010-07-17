<?php

/**
 * @file
 * Show/hide textfields based on checkbox clicks.
 */

/**
 * Form builder function to create a form showing dependent dropdowns. The
 * second dropdown has its values populated based on the first.
 * @param $form_state
 * @param $my_values
 */
function ahah_example_dropdown(&$form_state, $my_values = array()) {
  $form = array();

  // get the list of options to populate the first dropdown
  $initial_options = _ahah_example_get_first_dropdown_options();

  $form['explanation'] = array(
    '#type' => 'markup',
    '#value' => '<div>' . t('This is an example of a properly degrading dynamic form. It will work correctly with or without AJAX enabled. However, it has to provide an extra hidden button to change the values in the dependent dropdown.') . '</div>',
  );
  // if we have a value for the first dropdown from $form_state['values'] we use
  // this both as the default value for the first dropdown and also as a
  // parameter to pass to the function that retrieves the options for the
  // second dropdown.
  $master_selection = !empty($form_state['values']['master_dropdown']) ? $form_state['values']['master_dropdown'] : t('Strings');

  $form['master_dropdown'] = array(
    '#type' => 'select',
    '#title' => 'Master Dropdown',
    '#options' => $initial_options,
    '#default_value' => $master_selection,
    '#ahah' => array(
      'path' => 'examples/ahah_example/dependent_dropdown/callback',
      'wrapper' => 'dependent-dropdown-wrapper',
      // 'event' => 'change', // default value: does not need to be set explicitly.
    ),
    '#attributes' => array('class' => 'master-dropdown'),
  );

  // The CSS for this module hides this next button if JS is enabled.
  $form['continue_to_dependent_dropdown'] = array(
    '#type' => 'submit',
    '#value' => t('Choose'),
    '#attributes' => array('class' => 'next-button'),
    '#submit' => array('ahah_example_dropdown_continue'),
  );

  $form['dependent_dropdown_holder'] = array(
    '#tree' => TRUE,
    '#prefix' => '<div id="dependent-dropdown-wrapper">',
    '#suffix' => '</div>',
  );

  $form['dependent_dropdown_holder']['dependent_dropdown'] = array(
    '#type' => 'select',
    '#title' => t('Dependent Dropdown (changes based on master dropdown)'),

    // when the form is rebuilt during processing (either AJAX or multistep),
    // the $master_selction variable will now have the new value and so the
    // options will change.
    '#options' => _ahah_example_get_second_dropdown_options($master_selection),
    '#default_value' => isset($my_values['dependent_dropdown']) ? $my_values['dependent_dropdown'] : '',
  );


  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * The AHAH callback. It processes the form using ahah_example_callback_helper()
 * and then
 */
function ahah_example_dropdown_callback() {
	$form = ahah_example_callback_helper();

  $changed_elements = $form['dependent_dropdown_holder'];

  // Prevent duplicate wrappers.
  unset($changed_elements['#prefix'], $changed_elements['#suffix']);

  $output = theme('status_messages') . drupal_render($changed_elements);

  drupal_json(array(
    'status'   => TRUE,
    'data'     => $output,
  ));
}

/**
* Submit handler for 'continue_to_dependent_dropdown'.
*/
function ahah_example_dropdown_continue($form, &$form_state) {
  $values = $form_state['values'];
  unset($form_state['submit_handlers']);
  form_execute_handlers('submit', $form, $form_state);
  $form_state['my_values'] = $values;
  $form_state['rebuild'] = TRUE;
}

/**
 * Default submit handler for form. This one happens when the main submit
 * button is pressed.
 */
function ahax_example_dropdown_submit($form, &$form_state) {

  if ($form_state['clicked_button']['#id'] == 'edit-submit') {
      $form_state['rebuild'] = FALSE;
      drupal_set_message(t('Your values have been submitted. dropdown_first=@first, dependent_dropdown=@second', array('@first' => $form_state['values']['dropdown_first'], '@second' => $form_state['values']['dependent_dropdown_wrapper']['dependent_dropdown'])));
  }

  // edit-next or anything else will cause rebuild.
  $form_state['rebuild'] = TRUE;
}

/**
 * Helper function to populate the first dropdown. This would normally be
 * pulling data from the database.
 *
 * @return array of options
 */
function _ahah_example_get_first_dropdown_options() {
  // drupal_map_assoc() just makes an array('Strings' => 'Strings'...).
  return drupal_map_assoc(array(t('Strings'), t('Woodwinds'), t('Brass'), t('Percussion')));
}

/**
 * Helper function to populate the second dropdown. This would normally be
 * pulling data from the database.
 *
 * @param key. This will determine which set of options is returned.
 *
 * @return array of options
 */
function _ahah_example_get_second_dropdown_options($key = '') {
  $options = array(
    t('Strings') => drupal_map_assoc(array(t('Violin'), t('Viola'), t('Cello'), t('Double Bass'))),
    t('Woodwinds') => drupal_map_assoc(array(t('Flute'), t('Clarinet'), t('Oboe'), t('Bassoon'))),
    t('Brass') => drupal_map_assoc(array(t('Trumpet'), t('Trombone'), t('French Horn'), t('Euphonium'))),
    t('Percussion') => drupal_map_assoc(array(t('Bass Drum'), t('Timpani'), t('Snare Drum'), t('Tambourine'))),
  );
  if (isset($options[$key])) {
    return $options[$key];
  }
  else {
    return array();
  }
}