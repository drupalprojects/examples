// $Id$

Drupal.verticalTabs = Drupal.verticalTabs || {};

Drupal.verticalTabs.vertical_tabs_example = function() {
  if ($('#edit-vertical_tabs_example-enabled').attr('checked')) {
    // We show here the container with the custom settings form
    $('#vertical_tabs_example-custom-container').show('fast')
    return $('#edit-vertical_tabs_example-custom-setting').val();
  }
  else {
    // We hide here the container with the custom settings form
    $('#vertical_tabs_example-custom-container').hide('fast')
    return Drupal.t('Using default.');
  }
}
