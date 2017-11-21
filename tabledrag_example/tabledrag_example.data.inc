<?php

/**
 * @file
 * Data file for table drag forms.
 */

/**
 * Basic data for TableDrag Example.
 *
 * Used in module install and data reset button.
 */
function tabledrag_example_data() {
  $rows = [
    [
      'name' => t('Item One'),
      'description' => t('The first item'),
      'itemgroup' => t('Group1'),
    ],
    [
      'name' => t('Item Two'),
      'description' => t('The second item'),
      'itemgroup' => t('Group1'),
    ],
    [
      'name' => t('Item Three'),
      'description' => t('The third item'),
      'itemgroup' => t('Group1'),
    ],
    [
      'name' => t('Item Four'),
      'description' => t('The fourth item'),
      'itemgroup' => t('Group2'),
    ],
    [
      'name' => t('Item Five'),
      'description' => t('The fifth item'),
      'itemgroup' => t('Group2'),
    ],
    [
      'name' => t('Item Six'),
      'description' => t('The sixth item'),
      'itemgroup' => t('Group2'),
    ],
    [
      'name' => t('Item Seven'),
      'description' => t('The seventh item'),
      'itemgroup' => t('Group3'),
    ],
    [
      'name' => t('Item Eight'),
      'description' => t('The eighth item'),
      'itemgroup' => t('Group3'),
    ],
    [
      'name' => t('Item Nine'),
      'description' => t('The ninth item'),
      'itemgroup' => t('Group3'),
    ],
    [
      'name' => t('Item Ten'),
      'description' => t('The tenth item'),
      'itemgroup' => t('Group4'),
    ],
    [
      'name' => t('Item Eleven — A Root Node'),
      'description' => t('This item cannot be nested under a parent item'),
      'itemgroup' => t('Group4'),
    ],
    [
      'name' => t('Item Twelve — A Leaf Item'),
      'description' => t('This item cannot have child items'),
      'itemgroup' => t('Group4'),
    ],
  ];
  return $rows;
}
