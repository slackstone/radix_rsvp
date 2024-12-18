<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;


/**
 * Implements hook_form_system_theme_settings_alter() for radix_rsvp theme.
 */
function radix_rsvp_form_system_theme_settings_alter(&$form, FormStateInterface $form_state) {

  // Create a new fieldset specifically for RSVP System logo settings.
  $form['rsvp_logo_settings'] = [
    '#type' => 'details',
    '#title' => t('RSVP System Logo Settings'),
    '#open' => TRUE,
  ];

  $logo_sizes = ['mobile', 'tablet', 'desktop'];
  
  // Add managed_file fields for each size as you already have.
  foreach ($logo_sizes as $size) {
    // Normal logo field
    $form['rsvp_logo_settings']["rsvp_logo_{$size}"] = [
      '#type' => 'managed_file',
      '#title' => t('RSVP ' . ucfirst($size) . ' Logo'),
      '#description' => t('Upload the logo to be used on ' . $size . ' devices.'),
      '#default_value' => theme_get_setting("rsvp_logo_{$size}"),
      '#upload_location' => 'public://theme/rsvp_logos/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
    ];

    // Inverted scroll logo field
    $form['rsvp_logo_settings']["rsvp_logo_{$size}_invert"] = [
      '#type' => 'managed_file',
      '#title' => t('RSVP ' . ucfirst($size) . ' Inverted Scroll Logo'),
      '#description' => t('Upload the inverted scroll effect logo for ' . $size . ' devices.'),
      '#default_value' => theme_get_setting("rsvp_logo_{$size}_invert"),
      '#upload_location' => 'public://theme/rsvp_logos/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
    ];

    // Checkbox to remove file
    $form['rsvp_logo_settings']["rsvp_logo_{$size}_remove"] = [
      '#type' => 'checkbox',
      '#title' => t('Remove RSVP ' . ucfirst($size) . ' Logo'),
      '#default_value' => FALSE,
    ];
  }

  // Add a text field for the logo alt text
  $form['rsvp_logo_settings']['rsvp_logo_alt_text'] = [
    '#type' => 'textfield',
    '#title' => t('Logo Alt Text'),
    '#default_value' => theme_get_setting('rsvp_logo_alt_text'),
    '#description' => t('Enter the alt text for the logos.'),
  ];


  // Add the site name image upload fields back to the settings
  $form['rsvp_site_name_settings'] = [
    '#type' => 'details',
    '#title' => t('RSVP System Site Name Image Settings'),
    '#open' => TRUE,
  ];

  foreach ($logo_sizes as $size) {
    // Normal site name field
    $form['rsvp_site_name_settings']["rsvp_site_name_{$size}"] = [
      '#type' => 'managed_file',
      '#title' => t('RSVP ' . ucfirst($size) . ' Site Name Image'),
      '#description' => t('Upload the site name image to be used on ' . $size . ' devices.'),
      '#default_value' => theme_get_setting("rsvp_site_name_{$size}"),
      '#upload_location' => 'public://theme/rsvp_site_names/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
    ];

    // Inverted site name field
    $form['rsvp_site_name_settings']["rsvp_site_name_{$size}_invert"] = [
      '#type' => 'managed_file',
      '#title' => t('RSVP ' . ucfirst($size) . ' Inverted Scroll Site Name Image'),
      '#description' => t('Upload the inverted scroll effect site name image for ' . $size . ' devices.'),
      '#default_value' => theme_get_setting("rsvp_site_name_{$size}_invert"),
      '#upload_location' => 'public://theme/rsvp_site_names/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg svg gif'],
      ],
    ];

    // Checkbox to remove the site name image
    $form['rsvp_site_name_settings']["rsvp_site_name_{$size}_remove"] = [
      '#type' => 'checkbox',
      '#title' => t('Remove RSVP ' . ucfirst($size) . ' Site Name Image'),
      '#default_value' => FALSE,
    ];
  }

  // Add a text field for the site name alt text
  $form['rsvp_site_name_settings']['rsvp_logo_text_alt_text'] = [
    '#type' => 'textfield',
    '#title' => t('Site Name Alt Text'),
    '#default_value' => theme_get_setting('rsvp_logo_text_alt_text'),
    '#description' => t('Enter the alt text for the site name images.'),
  ];

  // Add the submit handler as before.
  $form['#submit'][] = 'radix_rsvp_theme_settings_submit';
}

/**
 * Submit handler to process file uploads and removals.
 */
function radix_rsvp_theme_settings_submit(&$form, FormStateInterface $form_state) {
  // List of file fields from the form.
  $logo_sizes = ['mobile', 'tablet', 'desktop'];
  // Loop through each size.
  foreach ($logo_sizes as $size) {
    // Process both logos and site name images.
    foreach (['rsvp_logo', 'rsvp_site_name'] as $type) {
      // Handle both normal and inverted versions.
      foreach (['', '_invert'] as $suffix) {
        $file_fid = $form_state->getValue("{$type}_{$size}{$suffix}");
        $remove_field = $form_state->getValue("{$type}_{$size}{$suffix}_remove");

        if ($remove_field && !empty($file_fid[0])) {
          // Remove the file if the checkbox is checked.
          $file = File::load($file_fid[0]);
          if ($file) {
            $file->delete();
            $form_state->setValue("{$type}_{$size}{$suffix}", NULL); // Unset file value.
          }
        } elseif (!empty($file_fid[0])) {
          // Set file as permanent if uploaded.
          $file = File::load($file_fid[0]);
          if ($file && $file->isTemporary()) {
            $file->setPermanent();
            $file->save();
          }
        }
      }
    }
  }
}
