<?php

/**
 * @file
 * A Hello-world AHAH. Just swaps out a markup section on submit.
 */

/**
 * This trivial form builder function just creates a box as a section on the
 * page which can be replaced when AHAH-enabled submit element fires.
 */
function ahah_example_simplest(&$form_state) {

	$form['explanation'] = array(
	 '#type' => 'markup',
	 '#value' => '<div>' . t('This is the very simplest AHAH example, which just replaces the HTML for a div on the page. Notice the more advanced examples in the other tabs.') . '</div>',
	);
	
  $initial_markup = '<div style="width: 150px; height: 150px; border: 1px solid black">' . t('This box will be replaced') . '</div>';

  $form['box'] = array(
    '#type' => 'markup',
    '#prefix' => '<div id="box">',
    '#suffix' => '</div>',
    '#value' => $initial_markup,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#ahah' => array(
      'path' => 'examples/ahah_example/simplest_ahah/callback',
      'wrapper' => 'box',
    ),
    '#value' => t('Click Me to change box color'),
  );

  return $form;
}

/**
 * This callback has nothing to do with the form itself, so just returns a bit
 * of HTML that will replace the existing markup in $form['box'].
 */
function ahah_example_simplest_callback() {
  $colors = preg_split('/[ ,]+/', "aqua, black, blue, fuchsia, gray, green, lime, maroon, navy, olive, purple, red, silver, teal, white, yellow");
  $color = $colors[array_rand($colors)];
  $output = '<div id="box" style="background-color:' . $color .
    '; width: 150px; height: 150px; border: 1px solid black;">This is ' . $color . ' box</div>';
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}