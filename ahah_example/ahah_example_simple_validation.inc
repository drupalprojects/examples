<?php

/**
 * @file
 * Demonstrate validation of a textfield using AHAH.
 * This approach allows 'live' validation of a field which degrades gracefully
 * when JavaScript is not available.
 */
function ahah_example_simple_validation(&$form_state) {

	// Wrap the dynamic element in a fieldset, as some themes render the throbber
	// wrong and things appear to jump around on the screen. This can also be
	// fixed with some CSS.
	$form['wrapper_fieldset'] = array(
	 '#type' => 'fieldset',
	);
  $form['wrapper_fieldset']['two_words_required'] = array(
    '#title' => t('At least two words are required in this textfield'),
    '#type' => 'textfield',
    '#default_value' => !empty($form_state['values']['two_words_required']) ? $form_state['values']['two_words_required'] : '',
    '#ahah' => array(
      'path' => 'examples/ahah_example/simple_validation/callback',
      'wrapper' => 'two_words_required_wrapper',
    ),
    '#prefix' => '<div id="two_words_required_wrapper">',
    '#suffix' => '</div>',
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Click Me'),
  );

  return $form;
}

/**
 * Validation function for the form. Requires that two words be entered.
 */
function ahah_example_simple_validation_validate(&$form, &$form_state) {
	$words = explode(' ', $form_state['values']['two_words_required']);
	if (count($words) < 2) {
		form_set_error('two_words_required', t('You have to enter at least two words'));
	}
}
/**
 * Callback for autocheckboxes. Process the form with the number of checkboxes
 * we want to provide.
 */
function ahah_example_simple_validation_callback() {
  $form = ahah_example_callback_helper();
  
  // This section prepares the actual output that will be returned to the 
  // browser.
  $selected_portion = $form['wrapper_fieldset']['two_words_required'];
  
  // To avoid doubling-up the wrapper, we have to remove it here.
  unset($selected_portion['#prefix'], $selected_portion['#suffix']);
  
  // Now render and output.
  $output = drupal_render($selected_portion);
  
  // Include (optionally) the results of any drupal_set_message() calls that
  // may have occurred.
  $output .= theme('status_messages');  
  
  // Output the results and exit.
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}