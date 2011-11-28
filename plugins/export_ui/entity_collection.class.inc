<?php

class entity_collection_ui extends ctools_export_ui {
  public function edit_form(&$form, &$form_state) {
    // This is needed in order to get the ajax working in the ctools form.
    ctools_include('plugins');
    ctools_include('export');
    ctools_get_plugins('ctools', 'export_ui', 'entity_collection');
    parent::edit_form($form, $form_state);
    // We need to define our own function, the one provided by ctools does not
    // work with the Drupal ajax API.
    $form['info']['name']['#machine_name']['exists'] = 'entity_collection_bundle_exists';
  }
}