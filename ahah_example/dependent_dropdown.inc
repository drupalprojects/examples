<?php

/**
 * @file
 * Show/hide textfields based on checkbox clicks.
 */
function ahah_example_dropdown(&$form_state, $my_values = array()) {
  $form = array();
  $form['#cache'] = TRUE;
  $form['#submit'] = array('ahax_example_dropdown_form_submit');
  // the contents of $musician will either come from the db or from $form_state
  if (isset($form_state['my_values'])) {
    $my_values = $form_state['my_values'] + (array)$my_values;
  }
  
  // get the list of options to populate the first dropdown
  $options_first = _ahah_example_get_first_dropdown_options();

  // if we have a value for the first dropdown from $form_state['values'] we use
  // this both as the default value for the first dropdown and also as a
  // parameter to pass to the function that retrieves the options for the
  // second dropdown.
  $selected = isset($my_values['dropdown_first']) ? $my_values['dropdown_first'] : key($options_first);

  $form['dropdown_first'] = array(
    '#type' => 'select',
    '#title' => 'First Dropdown',
    '#options' => $options_first,
    '#default_value' => $selected,
    '#ahah' => array(
      'path' => 'ahah_example/dependent_dropdown/callback',
      'wrapper' => 'dropdown-second-replace',
      'event' => 'change',
    ),
  );  

  // The CSS for this module hides this next button if JS is enabled.
  $form['continue_to_second'] = array(
    '#type' => 'submit',
    '#value' => t('Choose'),
    '#attributes' => array('class' => 'next-button'),
    '#submit' => array('dropdown_submit_handler'),
  );

  $form['dropdown_second_wrapper'] = array(
    '#tree' => TRUE,
    '#prefix' => '<div id="dropdown-second-replace">',
    '#suffix' => '</div>',
  );
  
  $form['dropdown_second_wrapper']['dropdown_second'] = array(
    '#type' => 'select',
    '#title' => 'Second Dropdown',

    // when the form is rebuilt during processing (either AJAX or multistep),
    // the $selected variable will now have the new value and so the options
    // will change.
    '#options' => _ahah_example_get_second_dropdown_options($selected),
    '#default_value' => isset($my_values['dropdown_second']) ? $my_values['dropdown_second'] : '',
  );


  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );


  return $form;
}

/**
* Submit handler for the second drop down.
*/
function dropdown_submit_handler($form, &$form_state) {
  $values = $form_state['values'];
  unset($form_state['submit_handlers']);
  form_execute_handlers('submit', $form, $form_state);
  $form_state['my_values'] = $values;
  $form_state['rebuild'] = TRUE;
}

function ahah_example_dropdown_callback() {
  $form_state = array('storage' => NULL, 'submitted' => FALSE);
  $form_build_id = $_POST['form_build_id'];
  $form = form_get_cache($form_build_id, $form_state);

  $args = $form['#parameters'];
  $form_id = array_shift($args);
  $form_state['post'] = $form['#post'] = $_POST;
  $form['#programmed'] = $form['#redirect'] = FALSE;

  drupal_process_form($form_id, $form, $form_state);
  $form = drupal_rebuild_form($form_id, $form_state, $args, $form_build_id);

  $changed_elements = $form['dropdown_second_wrapper'];
  unset($changed_elements['#prefix'], $changed_elements['#suffix']); // Prevent duplicate wrappers.
  drupal_json(array(
    'status'   => TRUE,
    'data'     => theme('status_messages') . drupal_render($changed_elements),
  ));
}


function ahax_example_dropdown_form_submit($form, &$form_state) {

  if ($form_state['clicked_button']['#id'] == 'edit-submit') {
      $form_state['rebuild'] = FALSE;
      drupal_set_message(t('Your values have been submitted. dropdown_first=@first, dropdown_second=@second', array('@first' => $form_state['values']['dropdown_first'], '@second' => $form_state['values']['dropdown_second_wrapper']['dropdown_second'])));
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