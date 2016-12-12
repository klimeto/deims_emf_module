<?php

/**
 * @file
 * Administrative page callbacks for the EMF module.
 */

function emf_settings_form() {

  $form['emf9_data_policies'] = array(
    '#type' => 'textarea',
    '#title' => t('Data policies text'),
    '#default_value' => variable_getemf39_data_policies', ''),
    //'#required' => TRUE,
  );

  return system_settings_form($form);
}
