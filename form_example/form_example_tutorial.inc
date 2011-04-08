<?php

/**
 * @file
 * This is the Form API Tutorial from the handbook.
 * @see http://drupal.org/node/262422
 *
 * It goes through ten form examples in increasing complexity to demonstrate
 * Drupal 6 Form API.
 *
 * Links are provided inline for the related handbook pages.
 */

function form_example_tutorial() {
  return t('This is a set of ten form tutorials tied to the <a href="http://drupal.org/node/262422">Drupal handbook</a>.');
}

//////////////// Tutorial Example 1 //////////////////////
/**
 * This first form function is from the handbook page at
 * http://drupal.org/node/717722.
 * It just creates a very basic form with a textfield.
 *
 * This function is called the "form builder". It builds the form.
 * It takes a single argument, $form_state, but if drupal_get_form()
 * sends additional arguments, they will be provided after $form_state.
 */

function form_example_tutorial_1($form_state) {

  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with nothing but a textfield'),
  );
  // This is the first form element. It's a textfield with a label, "Name"
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
  );
  return $form;
}


//////////////// Tutorial Example 2 //////////////////////

/**
 * This is Example 2, a basic form with a submit button.
 * @see http://drupal.org/node/717726
 */
function form_example_tutorial_2(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A simple form with a submit button'),
  );

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
  );

  // Adds a simple submit button that refreshes the form and clears its contents -- this is the default behavior for forms.
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}

//////////////// Tutorial Example 3 //////////////////////

/**
 * Example 3: A basic form with fieldsets.
 *
 * We establish a fieldset element and then place two text fields within
 * it, one for a first name and one for a last name. This helps us group
 * related content.
 *
 * Study the code below and you'll notice that we renamed the array of the first
 * and last name fields by placing them under the $form['name']
 * array. This tells Form API these fields belong to the $form['name'] fieldset.
 */
function form_example_tutorial_3(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with a fieldset'),
  );

  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
  );
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
  );


  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}


//////////////// Tutorial Example 4 //////////////////////

/**
 * Example 4: Basic form with required fields.
 *
 */
function form_example_tutorial_4(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with validation'),
  );

  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    // Make the fieldset collapsible.
    '#collapsible' => TRUE, // Added
    '#collapsed' => FALSE,  // Added
  );

  // Make these fields required.
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#required' => TRUE, // Added
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#required' => TRUE, // Added
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}


//////////////// Tutorial Example 5 //////////////////////

/**
 * Example 5: Basic form with additional element attributes.
 *
 * This demonstrates additional attributes of text form fields.
 *
 * For a more extensive example on element types
 * @see http://drupal.org/node/751826
 *
 * See the complete form reference at
 * @see http://api.drupal.org/api/file/developer/topics/forms_api.html

 */
function form_example_tutorial_5(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with additional attributes'),
    '#description' => t('This one adds #default_value and #description'),
  );
  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#required' => TRUE,
    '#default_value' => "First name", // added default value.
    '#description' => "Please enter your first name.", // added description
    '#size' => 20, // added
    '#maxlength' => 20, // added
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#required' => TRUE,
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}


//////////////// Tutorial Example 6 //////////////////////

/**
 * Example 6: A basic form with a validate handler.
 *
 * From http://drupal.org/node/717736
 */

function form_example_tutorial_6(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with a validation handler'),
  );

  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#required' => TRUE,
    '#default_value' => "First name",
    '#description' => "Please enter your first name.",
    '#size' => 20,
    '#maxlength' => 20,
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#required' => TRUE,
  );

  // New form field added to permit entry of year of birth.
  // The data entered into this field will be validated with
  // the default validation function.
  $form['year_of_birth'] = array(
    '#type' => 'textfield',
    '#title' => "Year of birth",
    '#description' => 'Format is "YYYY"',
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}



/**
 * Now we add a handler/function to validate the data entered into the
 * "year of birth" field to make sure it's between the values of 1900
 * and 2000. If not, it displays an error. The value report is
 * $form_state['values'] (see http://drupal.org/node/144132#form-state).
 *
 * Notice the name of the function. It is simply the name of the form
 * followed by '_validate'. This is always the name of the default validation
 * function. An alternate list of validation functions could have been provided
 * in $form['#validate'].
 */
function form_example_tutorial_6_validate($form, &$form_state) {
  $year_of_birth = $form_state['values']['year_of_birth'];
  if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
    form_set_error('year_of_birth', t('Enter a year between 1900 and 2000.'));
  }
}



//////////////// Tutorial Example 7 //////////////////////

/**
 * Example 7: With a submit handler.
 *
 * From the handbook page:
 * http://drupal.org/node/717740
 */

function form_example_tutorial_7($form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with a submit handler'),
  );
  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#required' => TRUE,
    '#default_value' => "First name",
    '#description' => "Please enter your first name.",
    '#size' => 20,
    '#maxlength' => 20,
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#required' => TRUE,
  );
  $form['year_of_birth'] = array(
    '#type' => 'textfield',
    '#title' => "Year of birth",
    '#description' => 'Format is "YYYY"',
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}


/**
 * Validation function.
 */
function form_example_tutorial_7_validate($form, &$form_state) {
    $year_of_birth = $form_state['values']['year_of_birth'];
    if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
        form_set_error('year_of_birth', t('Enter a year between 1900 and 2000.'));
    }
}

/**
 * Adds a submit handler/function to our form to send a successful
 * completion message to the screen.
 */

function form_example_tutorial_7_submit($form, &$form_state) {
    drupal_set_message(t('The form has been submitted. name="@first @last", year of birth=@year_of_birth',
      array('@first' => $form_state['values']['first'], '@last' => $form_state['values']['last'], '@year_of_birth' => $form_state['values']['year_of_birth'])));
}


//////////////// Tutorial Example 8 //////////////////////

/**
 * Example 8: With a "reset" button.
 *
 * From handbook page http://drupal.org/node/717742.
 */
function form_example_tutorial_8(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with a "reset" button'),
  );

  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  // Removes the #required property and
  // uses the validation function instead.
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#default_value' => "First name",
    '#description' => "Please enter your first name.",
    '#size' => 20,
    '#maxlength' => 20,
  );

  // Removes the #required property and
  // uses the validation function instead.
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
  );
  $form['year_of_birth'] = array(
    '#type' => 'textfield',
    '#title' => "Year of birth",
    '#description' => 'Format is "YYYY"',
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  // Adds a new button to clear the form. The #validate property
  // directs the form to use a validation handler function specific
  // to this button instead of using the default handler.
  $form['clear'] = array(
    '#type' => 'submit',
    '#value' => 'Reset form',
    '#validate' => array('form_example_tutorial_8_clear'),
  );

  return $form;
}

/**
 * Validation function for the reset button.
 *
 * Setting the $form_state['rebuild'] value to TRUE says that we're going to
 * rebuild the form.  We also clear the existing values, although values
 * aren't used in the form builder in this example.
 */
function form_example_tutorial_8_clear($form, &$form_state) {
    $form_state['rebuild'] = TRUE;
    unset($form_state['values']);
}

/**
 * Makes the first and last name fields required by using the
 * validation function to make sure values have been entered. This
 * causes the name fields to show up in red if left blank after clearing
 * the form with the "Reset form" button.
 *
 * In a more extensive form, a validation function can check interdependencies
 * between fields. For example, it might be OK to enter only a last name, but
 * if you entered a last name you'd have to enter a first name.
 */
function form_example_tutorial_8_validate($form, &$form_state) {
    $year_of_birth = $form_state['values']['year_of_birth'];
    $first_name = $form_state['values']['first'];
    $last_name = $form_state['values']['last'];
    if (!$first_name) {
        form_set_error('first', t('Please enter your first name.'));
    }
    if (!$last_name) {
        form_set_error('last', t('Please enter your last name.'));
    }
    if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
        form_set_error('year_of_birth', t('Enter a year between 1900 and 2000.'));
    }
}

function form_example_tutorial_8_submit($form, &$form_state) {
  drupal_set_message(t('The form has been submitted. name="@first @last", year of birth=@year_of_birth',
    array('@first' => $form_state['values']['first'], '@last' => $form_state['values']['last'], '@year_of_birth' => $form_state['values']['year_of_birth'])));
}



//////////////// Tutorial Example 9 //////////////////////

/**
 * Example 9: A form with a dynamically added new fields.
 *
 * This example adds default values so that when the form is rebuilt,
 * the form will by default have the previously-entered values.
 *
 * From handbook page http://drupal.org/node/717746.
 */
function form_example_tutorial_9(&$form_state) {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A form with dynamically added new fields'),
  );

  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );

  // Change/add the #default_value for the first name, last name, and
  // birth year to show their old values so when the "Add another name"
  //button is clicked, they will retain their values.
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#default_value' => !empty($form_state['values']['first']) ? $form_state['values']['first'] : '', // changed
    '#description' => "Please enter your first name.",
    '#size' => 20,
    '#maxlength' => 20,
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#default_value' => !empty($form_state['values']['last']) ? $form_state['values']['last'] : '', // added
  );
  $form['year_of_birth'] = array(
    '#type' => 'textfield',
    '#title' => "Year of birth",
    '#description' => 'Format is "YYYY"',
    '#default_value' => !empty($form_state['values']['year_of_birth']) ? $form_state['values']['year_of_birth'] : '', // added
  );

  // Add new elements to the form if $form_state['storage']['new_name'] is set.
  // This happens when the "add new name" button is clicked.
  if (isset($form_state['storage']['new_name'])) {
    $form['name2'] = array(
      '#type' => 'fieldset',
      '#title' => t('Name #2'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    $form['name2']['first2'] = array(
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#description' => "Please enter your first name.",
      '#size' => 20,
      '#maxlength' => 20,
      '#default_value' => !empty($form_state['values']['first2']) ? $form_state['values']['first2'] : '',
    );
    $form['name2']['last2'] = array(
      '#type' => 'textfield',
      '#title' => t('Last name'),
      '#default_value' => !empty($form_state['values']['last2']) ? $form_state['values']['last2'] : '',
    );
    $form['year_of_birth2'] = array(
      '#type' => 'textfield',
      '#title' => "Year of birth",
      '#description' => 'Format is "YYYY"',
      '#default_value' => !empty($form_state['values']['year_of_birth2']) ? $form_state['values']['year_of_birth2'] : '',
    );
  }

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  $form['clear'] = array(
    '#type' => 'submit',
    '#value' => 'Reset form',
    '#validate' => array('form_example_tutorial_9_clear'),
  );

  // Adds "Add another name" button, if it hasn't already been clicked.
  if (empty($form_state['storage']['new_name'])) {
    $form['new_name'] = array(
      '#type' => 'submit',
      '#value' => 'Add another name',
      '#validate' => array('form_example_tutorial_9_new_name'),
    );
  }

  return $form;
}

/**
 * Creating a new element, 'new_name' in the $form_state['storage'] array
 * sets the value used to determine whether to show the new
 * fields on the form and hide the "Add another name" button.
 *
 * $form_state['storage'] is just a place to store spare information. Any
 * keys can be created in this array.
 */
function form_example_tutorial_9_new_name($form, &$form_state) {
    $form_state['storage']['new_name'] = TRUE;
    // Setting $form_state['rebuild'] = TRUE causes the default submit
    // function to be skipped and the form to be rebuilt.
    $form_state['rebuild'] = TRUE;
}

function form_example_tutorial_9_clear($form, &$form_state) {
  // Ensures fields are blank after the reset button is clicked.
  unset($form_state['values']);
  // Ensures the reset button removes the new_name button.
  unset($form_state['storage']);
  // Setting $form_state['rebuild'] = TRUE causes the default submit
  // function to be skipped and the form to be rebuilt.

  $form_state['rebuild'] = TRUE;
}

/**
 * Adds logic to validate the form to check the validity of the new fields,
 * if they exist.
 */
function form_example_tutorial_9_validate($form, &$form_state) {
  $year_of_birth = $form_state['values']['year_of_birth'];
  $first_name = $form_state['values']['first'];
  $last_name = $form_state['values']['last'];
  if (!$first_name) {
    form_set_error('first', t('Please enter your first name.'));
  }
  if (!$last_name) {
    form_set_error('last', t('Please enter your last name.'));
  }
  if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
    form_set_error('year_of_birth', t('Enter a year between 1900 and 2000.'));
  }
  if ($form_state['storage']['new_name']) {
    $year_of_birth = $form_state['values']['year_of_birth2'];
    $first_name = $form_state['values']['first2'];
    $last_name = $form_state['values']['last2'];
    if (!$first_name) {
      form_set_error('first2', t('Please enter your first name.'));
    }
    if (!$last_name) {
      form_set_error('last2', t('Please enter your last name.'));
    }
    if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
      form_set_error('year_of_birth2', t('Enter a year between 1900 and 2000.'));
    }
  }
}
/**
 * Submit function.
 *
 * Commenting out unset($form_state['storage'] and
 * then adding a new set of name fields and submitting the form,
 * would cause the form to rebuilt with the existing values, because in
 * Drupal 6 if $form_state['storage'] is set, $form_state['rebuild'] is also
 * automatically set, causing the form fields to get rebuilt with the
 * values found in $form_state['values'].
 */
function form_example_tutorial_9_submit($form, &$form_state) {
  unset($form_state['storage']);
  drupal_set_message(t('The form has been submitted. name="@first @last", year of birth=@year_of_birth',
    array('@first' => $form_state['values']['first'], '@last' => $form_state['values']['last'], '@year_of_birth' => $form_state['values']['year_of_birth'])));
  if (!empty($form_state['values']['first2'])) {
      drupal_set_message(t('Second name: name="@first @last", year of birth=@year_of_birth',
    array('@first' => $form_state['values']['first2'], '@last' => $form_state['values']['last2'], '@year_of_birth' => $form_state['values']['year_of_birth2'])));
  }
}



//////////////// Tutorial Example 10 //////////////////////

/**
 * Example 10: A simple multistep form.
 *
 * Handbook page: http://drupal.org/node/717750.
 *
 * For more extensive multistep forms,
 * @see http://pingvision.com/blog/ben-jeavons/2009/multi-step-forms-drupal-6-using-variable-functions
 *
 */

/**
 * Adds logic to our form builder to give it two pages. It checks a
 * value in $form_state['storage'] to determine if it should display page 2.
 */
function form_example_tutorial_10($form_state) {

  // Display page 2 if $form_state['storage']['page_two'] is set
  if (isset($form_state['storage']['page_two'])) {
    return form_example_tutorial_10_page_two();
  }
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A basic multistep form (page 1)'),
  );

  // Page 1 is displayed if $form_state['storage']['page_two'] is not set
  $form['name'] = array(
    '#type' => 'fieldset',
    '#title' => t('Name'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
  );
  $form['name']['first'] = array(
    '#type' => 'textfield',
    '#title' => t('First name'),
    '#default_value' => !empty($form_state['values']['first']) ? $form_state['values']['first'] : '', // changed
    '#description' => "Please enter your first name.",
    '#size' => 20,
    '#maxlength' => 20,
  );
  $form['name']['last'] = array(
    '#type' => 'textfield',
    '#title' => t('Last name'),
    '#default_value' => !empty($form_state['values']['last']) ? $form_state['values']['last'] : '', // added
  );
  $form['year_of_birth'] = array(
    '#type' => 'textfield',
    '#title' => "Year of birth",
    '#description' => 'Format is "YYYY"',
    '#default_value' => !empty($form_state['values']['year_of_birth']) ? $form_state['values']['year_of_birth'] : '', // added
  );
  // Add new elements to the form
  if (!empty($form_state['storage']['new_name'])) {
    $form['name2'] = array(
      '#type' => 'fieldset',
      '#title' => t('Name #2'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
    );
    $form['name2']['first2'] = array(
      '#type' => 'textfield',
      '#title' => t('First name'),
      '#description' => "Please enter your first name.",
      '#size' => 20,
      '#maxlength' => 20,
      '#default_value' => !empty($form_state['values']['first2']) ? $form_state['values']['first2'] : '',
    );
    $form['name2']['last2'] = array(
      '#type' => 'textfield',
      '#title' => t('Last name'),
      '#default_value' => !empty($form_state['values']['last2']) ? $form_state['values']['last2'] : '',
    );
    $form['year_of_birth2'] = array(
      '#type' => 'textfield',
      '#title' => "Year of birth",
      '#description' => 'Format is "YYYY"',
      '#default_value' => !empty($form_state['values']['year_of_birth2']) ? $form_state['values']['year_of_birth2'] : '',
    );
  }
  $form['clear'] = array(
    '#type' => 'submit',
    '#value' => 'Reset form',
    '#validate' => array('form_example_tutorial_10_clear'),
  );
  if (empty($form_state['storage']['new_name'])) {
    $form['new_name'] = array(
      '#type' => 'submit',
      '#value' => 'Add another name',
      '#validate' => array('form_example_tutorial_10_new_name'),
    );
  }
  $form['next'] = array(
    '#type' => 'submit',
    '#value' => 'Next >>',
  );
  return $form;
}

// New function created to help make the code more manageable.
function form_example_tutorial_10_page_two() {
  $form['description'] = array(
    '#type' => 'item',
    '#title' => t('A basic multistep form (page 2)'),
  );

  $form['color'] = array(
    '#type' => 'textfield',
    '#title' => 'Your favorite color',
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  return $form;
}

function form_example_tutorial_10_new_name($form, &$form_state) {
  $form_state['storage']['new_name'] = TRUE;
  $form_state['rebuild'] = TRUE; // default submit function will be skipped
}

function form_example_tutorial_10_clear($form, &$form_state) {
  unset($form_state['values']);
  unset($form_state['storage']);

  $form_state['rebuild'] = TRUE;
}

/**
 * The validate function now validates page 2 as well.
 */
function form_example_tutorial_10_validate($form, &$form_state) {
  // Validate page 2 here
  if (isset($form_state['storage']['page_two'])) {
    $color = $form_state['values']['color'];
    if (!$color) {
      form_set_error('color', t('Please enter a color.'));
    }
    return;
  }

  $year_of_birth = $form_state['values']['year_of_birth'];
  $first_name = $form_state['values']['first'];
  $last_name = $form_state['values']['last'];
  if (!$first_name) {
    form_set_error('first', t('Please enter your first name.'));
  }
  if (!$last_name) {
    form_set_error('last', t('Please enter your last name.'));
  }
  if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
    form_set_error('year_of_birth', t('Enter a year between 1900 and 2000.'));
  }
  if ($form_state['storage']['new_name']) {
    $year_of_birth = $form_state['values']['year_of_birth2'];
    $first_name = $form_state['values']['first2'];
    $last_name = $form_state['values']['last2'];
    if (!$first_name) {
      form_set_error('first2', t('Please enter your first name.'));
    }
    if (!$last_name) {
      form_set_error('last2', t('Please enter your last name.'));
    }
    if ($year_of_birth && ($year_of_birth < 1900 || $year_of_birth > 2000)) {
      form_set_error('year_of_birth2', t('Enter a year between 1900 and 2000.'));
    }
  }
}

/**
 * Modifies this function so that it will respond appropriately based on
 * which page was submitted. If the first page is being submitted,
 * values in the 'storage' array are saved and the form gets
 * automatically reloaded.
 *
 * If page 2 was submitted, we display a message and redirect the
 * user to another page.
 */
function form_example_tutorial_10_submit($form, &$form_state) {
  // Handle page 1 submissions
  if ($form_state['clicked_button']['#id'] == 'edit-next') {
    $form_state['storage']['page_two'] = TRUE; // We set this to determine
                                               // which elements to display
                                               // when the page reloads.

    // Values below in the $form_state['storage'] array are saved
    // to carry forward to subsequent pages in the form.
    $form_state['storage']['page_one_values'] = $form_state['values'];
  }
  // Handle page 2 submissions.
  else {
    /*
     Normally, some code would go here to alter the database with the data
     collected from the form. Sets a message with drupal_set_message()
     to validate working code.
     */
    $page_one_values = $form_state['storage']['page_one_values'];
    drupal_set_message(t('The form has been submitted. name="@first @last", year of birth=@year_of_birth',
      array('@first' => $page_one_values['first'], '@last' => $page_one_values['last'], '@year_of_birth' => $page_one_values['year_of_birth'])));

    if (!empty($page_one_values['first'])) {
      $first2 = isset($page_one_values['first2']) ? $page_one_values['first2'] : '';
      $last2  = isset($page_one_values['last2']) ? $page_one_values['last2'] : '';
      $year2  = isset($page_one_values['year_of_birth2']) ? $page_one_values['year_of_birth2'] : '';
      drupal_set_message(t('Second name: name="@first @last", year of birth=@year_of_birth',
        array('@first' => $first2, '@last' => $last2, '@year_of_birth' => $year2)));
    }
    drupal_set_message(t('And the favorite color is @color', array('@color' => $form_state['values']['color'])));

    // $form_state['storage'] must be unset for redirection to occur. Otherwise
    // $form_state['rebuild'] is automatically set and this form will be
    // rebuilt.
    unset($form_state['storage']);
    $form_state['redirect'] = 'node'; // Redirects the user to /node.
  }
}

//////////////// Tutorial Example 11 //////////////////////

/**
 * Example 11: A form with a file upload field.
 *
 * This example allows the user to upload a file to Drupal which is stored
 * physically and with a reference in the database. In this example, only
 * png, gif, jpg, and jpeg files are allowed.
 *
 * @see form_example_tutorial_11_validate()
 * @see form_example_tutorial_11_submit()
 * @ingroup form_example
 *
 */
function form_example_tutorial_11($form_state) {
  // This is required to upload files.
  // enctype="multipart/form-data" required by browsers to handle files.
  $form = array(
    '#attributes' => array('enctype' => "multipart/form-data"),
  );

  $form['file'] = array(
    '#type' => 'file',
    '#title' => t('Image'),
    '#description' => t('Upload a file, allowed extensions: jpg, jpeg, png, gif'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;
}

/**
 * Validate handler for form_example_tutorial_11().
 * Verify the valid extensions, and verify content is an image also.
 */
function form_example_tutorial_11_validate($form, &$form_state) {
  $file = file_save_upload('file', array(
    'file_validate_extensions' => array('png gif jpg jpeg'),
    'file_validate_is_image' => array(),
  ));

  // If the file passed validation:
  if (isset($file->filename)) {
    // Move the file, into the Drupal file system
    if (file_move($file, $file->filename)) {
      // Update the new file location in the database.
      drupal_write_record('files', $file, 'fid');
      // Save the file for use in the submit handler.
      $form_state['storage']['file'] = $file;
    }
    else {
      form_set_error('file', t('Failed to write the uploaded file the site\'s file folder.'));
    }
  }
  else {
    form_set_error('file', t('Invalid file, only images with the extension png, gif, jpg, jpeg are allowed'));
  }
}

/**
 * Submit handler for form_example_tutorial_11().
 */
function form_example_tutorial_11_submit($form, &$form_state) {
  $file = $form_state['storage']['file'];
  // We are done with the file, remove it from storage.
  unset($form_state['storage']['file']);
  // Make the storage of the file permanent
  file_set_status($file, FILE_STATUS_PERMANENT);
  // Set a response to the user.
  drupal_set_message(t('The form has been submitted and the image has been saved, filename: @filename.', array('@filename' => $file->filename)));
}
