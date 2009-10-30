<?php

/**
 * @file
 * A Hello-world AHAH. Just swaps out a markup section on submit.
 */
function ahah_example_simplest() {

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
      'path' => 'ahah_example/simplest_ahah/callback',
      'wrapper' => 'box',
    ),
    '#value' => t('Click Me to change box color'),
  );

  return $form;
}

function ahah_example_simplest_callback() {
  $colors = preg_split('/[ ,]+/', "aqua, black, blue, fuchsia, gray, green, lime, maroon, navy, olive, purple, red, silver, teal, white, yellow");
  $color = $colors[array_rand($colors)];
  $output = '<div id="box" style="background-color:' . $color .
    '; width: 150px; height: 150px; border: 1px solid black;">This is ' . $color . ' box</div>';
  print drupal_json(array('status' => TRUE, 'data' => $output));
  exit();
}