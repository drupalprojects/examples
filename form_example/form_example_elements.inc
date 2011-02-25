<?php

/**
 * @file
 * This is an example demonstrating how a module can define custom form
 * elements.
 *
 * Form elements are already familiar to anyone who uses Form API. Examples
 * of core form elements are 'textfield', 'checkbox' and 'fieldset'. Drupal
 * utilizes hook_elements() to define these FAPI types, and this occurs in
 * the core function system_elements().
 *
 * Each form element has a #type value that determines how it is treated by
 * the Form API and how it is ultimately rendered into HTML. hook_elements()
 * allows modules to define new element types, and tell the Form API what
 * default values they should automatically be populated with.
 *
 * By implementing hook_elements in your own module, you can create custom
 * form elements with their own properties, validation and theming.
 *
 * In this example, we define a series of elements that range from trivial
 * (a renamed textfield) to more advanced (a telephone number field with each
 * portion separately validated).
 *
 * The @link http://drupal.org/node/169815 Elements handbook page @endlink
 * has full details on creating elements. See also hook_elements().
 */


/**
 * Implementation of hook_elements().
 *
 * This defines a new form element types.
 *
 * - form_example_textfield: This is actually just a textfield, but provides
 *   the new type. If more were to be done with it a theme function could be
 *   provided.
 * - form_example_checkbox: Nothing more than a regular checkbox, but uses
 *   an alternate theme function provided by this module.
 * - form_example_phonenumber_discrete: Provides a North-American style
 *   three-part phonenumber where the value of the phonenumber is managed
 *   as an array of three parts.
 * - form_example_phonenumber_combined: Provides a North-American style
 *   three-part phonenumber where the actual value is managed as a 10-digit
 *   string and only broken up into three parts for the user interface.
 *
 * See hook_elements() and the
 * @link http://drupal.org/node/169815 Creating Custom Elements @endlink
 * handbook page.
 */
function _form_example_elements() {
  // Simple elements based on textfield require only a definition and a theme
  // function. In this case we provide the theme function using the default
  // but it would by default be provided in hook_theme(), probably as
  // theme_form_example_textfield().
  $types['form_example_textfield'] = array(
    // #input tells FAPI that this is an element that will carry a value, even
    // if it is a hidden value.
    '#input' => TRUE,
    '#theme' => array('textfield'),
    '#autocomplete_path' => FALSE,
  );

  $types['form_example_checkbox'] = array(
    '#input' => TRUE,
    '#return_value' => TRUE,
    '#process' => array('form_expand_ahah'),
    // #theme is the default theme('form_example_checkbox'), provided by
    // this module.

    // This also depends on the existence of
    // form_type_form_example_checkbox_value(), which is provided by this
    // module. Since this is not a default textfield-derived element, it
    // needs its own value callback.
  );

  // This discrete phonenumber element keeps its values as the separate elements
  // area code, prefix, extension.
  $types['form_example_phonenumber_discrete'] = array(
    '#input' => TRUE,

    // #process is an array of callback functions executed when this element is
    // processed. Here it provides the child form elements which define
    // areacode, prefix, and extension.
    '#process' => array('form_example_phonenumber_discrete_process'),

    // validation handlers for this element
    '#element_validate' => array('form_example_phonenumber_discrete_validate'),
    '#autocomplete_path' => FALSE,
  );

  // Define form_example_phonenumber_combined, which combines the phone
  // number into a single validated text string.
  $types['form_example_phonenumber_combined'] = array(
    '#input' => TRUE,
    '#process' => array('form_example_phonenumber_combined_process'),
    '#element_validate' => array('form_example_phonenumber_combined_validate'),
    '#autocomplete_path' => FALSE,
    '#default_value' => array(
      'areacode' => '',
      'prefix' => '',
      'extension' => '',
    ),
  );
  return $types;
}

/**
 * Helper function to determine the value for an form_example_checkbox.
 *
 * Required for the element type 'form_example_checkbox' to work.
 * Copied from form.inc.
 *
 * @param $form
 *   The form element whose value is being populated.
 * @param $edit
 *   The incoming POST data to populate the form element. If this is FALSE,
 *   the element's default value should be returned.
 * @return
 *   The data that will appear in the $form_state['values'] collection
 *   for this element. Return nothing to use the default.
 */
function form_type_form_example_checkbox_value($form, $edit = FALSE) {
  if ($edit !== FALSE) {
    if (empty($form['#disabled'])) {
      return !empty($edit) ? $form['#return_value'] : 0;
    }
    else {
      return $form['#default_value'];
    }
  }
}

/**
 * Process callback for the discrete version of phonenumber.
 */
function form_example_phonenumber_discrete_process($element, $edit, &$form_state, $complete_form) {
  // #tree = TRUE means that the values in $form_state['values'] will be stored
  // hierarchically. In this case, the parts of the element will appear in
  // $form_state['values'] as
  // $form_state['values']['<element_name>']['areacode'],
  // $form_state['values']['<element_name>']['prefix'],
  // etc. This technique is preferred when an element has member form
  // elements.
  $element['#tree'] = TRUE;

  // Normal FAPI field definitions, except that #value is defined.
  $element['areacode'] = array(
    '#type' => 'textfield',
    '#size' => 3,
    '#maxlength' => 3,
    '#value' => $element['#value']['areacode'],
    '#required' => TRUE,
    '#prefix' => '(',
    '#suffix' => ')',
  );
  $element['prefix'] =  array(
    '#type' => 'textfield',
    '#size' => 3,
    '#maxlength' => 3,
    '#required' => TRUE,
    '#value' => $element['#value']['prefix'],
  );
  $element['extension'] =  array(
    '#type' => 'textfield',
    '#size' => 4,
    '#maxlength' => 4,
    '#value' => $element['#value']['extension'],
  );

  return $element;
}

/**
 * Validation handler for the discrete version of the phone number.
 *
 * Using regular expressions, we check that:
 *  - the area code is a three digit number
 *  - the prefix is numeric 3-digit number
 *	- the extension is a numeric 4-digit number
 *
 * Any problems are shown on the form element using form_error().
 */
function form_example_phonenumber_discrete_validate($element, &$form_state) {
  if (isset($element['#value']['areacode'])) {
    if (0 == preg_match('/^\d{3}$/', $element['#value']['areacode'])) {
      form_error($element['areacode'], t('The area code is invalid.'));
    }
  }
  if (isset($element['#value']['prefix'])) {
    if (0 == preg_match('/^\d{3}$/', $element['#value']['prefix'])) {
      form_error($element['prefix'], t('The prefix is invalid.'));
    }
  }
  if (isset($element['#value']['extension'])) {
    if (0 == preg_match('/^\d{4}$/', $element['#value']['extension'])) {
      form_error($element['extension'], t('The extension is invalid.'));
    }
  }
  return $element;
}



/**
 * Process callback for the combined version of the phonenumber element.
 */
function form_example_phonenumber_combined_process($element, $edit, &$form_state, $complete_form) {
  // #tree = TRUE means that the values in $form_state['values'] will be stored
  // hierarchically. In this case, the parts of the element will appear in
  // $form_state['values'] as
  // $form_state['values']['<element_name>']['areacode'],
  // $form_state['values']['<element_name>']['prefix'],
  // etc. This technique is preferred when an element has member form
  // elements.
  $element['#tree'] = TRUE;


  // Normal FAPI field definitions, except that #value is defined.
  $element['areacode'] = array(
    '#type' => 'textfield',
    '#size' => 3,
    '#maxlength' => 3,
    '#required' => TRUE,
    '#prefix' => '(',
    '#suffix' => ')',
  );
  $element['prefix'] =  array(
    '#type' => 'textfield',
    '#size' => 3,
    '#maxlength' => 3,
    '#required' => TRUE,
  );
  $element['extension'] =  array(
    '#type' => 'textfield',
    '#size' => 4,
    '#maxlength' => 4,
    '#required' => TRUE,
  );


  $matches = array();
  $match = preg_match('/^(\d{3})(\d{3})(\d{4})$/', $element['#default_value'], $matches);
  if ($match) {
    array_shift($matches); // get rid of the "all match" element
    list($element['areacode']['#default_value'], $element['prefix']['#default_value'], $element['extension']['#default_value']) = $matches;
  }
  return $element;
}

/**
 * Combined version validation function.
 *
 * Using regular expressions, we check that:
 *  - the area code is a three digit number
 *  - the prefix is numeric 3-digit number
 *  - the extension is a numeric 4-digit number
 *
 * Any problems are shown on the form element using form_error().
 *
 * The combined value is then updated in the element.
 */
function form_example_phonenumber_combined_validate($element, &$form_state) {
  $lengths = array(
    'areacode' => 3,
    'prefix' => 3,
    'extension' => 4,
  );
  foreach ($lengths as $member => $length) {
    $regex = '/^\d{' . $length . '}$/';
    if (!empty($element['#value'][$member]) && 0 == preg_match($regex, $element['#value'][$member])) {
      form_error($element[$member], t('@member is invalid', array('@member' => $member)));
    }
  }

  // Consolidate into the three parts into one combined value.
  $value = $element['#value']['areacode'] . $element['#value']['prefix'] . $element['#value']['extension'];
  form_set_value($element, $value, $form_state);
  return $element;
}

/**
 * Called by form_example_theme() to provide hook_theme().
 */
function _form_example_element_theme() {
  return array(
    'form_example_checkbox' => array(
      'arguments' => array('element'),
      'file' => 'form_example_elements.inc',
    ),
    'form_example_phonenumber_discrete' => array(
      'arguments' => array('element'),
      'file' => 'form_example_elements.inc',
    ),
    'form_example_phonenumber_combined' => array(
      'arguments' => array('element'),
      'file' => 'form_example_elements.inc',
    ),
  );
}


/**
 * Theme function for form_example_checkbox type.
 */
function theme_form_example_checkbox($element) {
  return theme('checkbox', $element);
}

/**
 * Theme function to format the discrete version.
 *
 * We use the container-inline class so that all three of the HTML elements
 * are placed next to each other, rather than on separate lines.
 */
function theme_form_example_phonenumber_discrete($element) {
  // #children represents all the sublevels elements already rendered in HTML.
  // Here it contains the three parts of the 'phonenumber' element type ('areacode', 'prefix' and 'extension').
  return theme('form_element', $element, '<div class="container-inline">' . $element['#children'] . '</div>');
}


/**
 * Theme function to format the combined version.
 *
 * We use the container-inline class so that all three of the HTML elements
 * are placed next to each other, rather than on separate lines.
 */
function theme_form_example_phonenumber_combined($element) {
  // #children represents all the sublevels elements already rendered in HTML.
  // Here it contains the three parts of the 'phonenumber' element type ('areacode', 'prefix' and 'extension').
  return theme('form_element', $element, '<div class="container-inline">' . $element['#children'] . '</div>');
}

/**
 * This is a simple form to demonstrate how to use the various new FAPI elements
 * we've defined.
 */
function form_example_element_demo_form() {
  $form['form_example_textfield'] = array(
    '#type' => 'form_example_textfield',
    '#title' => t('Form Example textfield'),
    '#default_value' => variable_get('form_example_textfield', ''),
    '#description' => t('form_example_textfield is a new type, but it is actually uses the system-provided functions of textfield'),
  );

  $form['form_example_checkbox'] = array(
    '#type' => 'form_example_checkbox',
    '#title' => t('Form Example checkbox'),
    '#default_value' => variable_get('form_example_checkbox', FALSE),
    '#description' => t('Nothing more than a regular checkbox but with a theme provided by this module.')
  );

  $form['form_example_element_discrete'] = array(
    '#type' => 'form_example_phonenumber_discrete',
    '#title' => t('Discrete phone number'),
    '#default_value' => variable_get('form_example_element_discrete', array('areacode' => '', 'prefix' => '', 'extension' => '')),
    '#description' => t('A phone number : areacode (XXX), prefix (XXX) and extension (XXXX). This one uses a "discrete" element type, one which stores the three parts of the telephone number separately.'),
  );

  $form['form_example_element_combined'] = array(
    '#type' => 'form_example_phonenumber_combined',
    '#title' => t('Combined phone number'),
    '#default_value' => variable_get('form_example_element_combined', '0000000000'),
    '#description' => t('form_example_element_combined one uses a "combined" element type, one with a single 10-digit value which is broken apart when needed.'),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;
}

/**
 * Submit handler for form_example_element_demo_form().
 */
function form_example_element_demo_form_submit($form, &$form_state) {
  // Exclude unnecessary elements.
  unset($form_state['values']['submit'], $form_state['values']['form_id'], $form_state['values']['op'], $form_state['values']['form_token'], $form_state['values']['form_build_id']);

  foreach ($form_state['values'] as $key => $value) {
    variable_set($key, $value);
    drupal_set_message(t('%name has value %value', array('%name' => $key, '%value' => print_r($value, TRUE))));
  }
}
