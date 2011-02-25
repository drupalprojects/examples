
// The following line will prevent a JavaScript error if this file is included
// and vertical tabs are disabled.
Drupal.verticalTabs = Drupal.verticalTabs || {};

Drupal.verticalTabs.vertical_tabs_example = function() {
  if ($('#edit-vertical-tabs-example-enabled').attr('checked')) {
    // We show here the container with the custom settings form.
    $('#edit-vertical-tabs-example-custom-setting-wrapper').parent().show('fast')
    return Drupal.checkPlain($('#edit-vertical-tabs-example-custom-setting').val());
  }
  else {
    // We hide here the container with the custom settings form.
    $('#edit-vertical-tabs-example-custom-setting-wrapper').parent().hide('fast')
    return Drupal.t('Using default');
  }
}
