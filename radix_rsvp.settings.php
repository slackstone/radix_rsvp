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

  // Add managed_file fields for each size.
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

  // Logo alt text field
  $form['rsvp_logo_settings']['rsvp_logo_alt_text'] = [
    '#type' => 'textfield',
    '#title' => t('Logo Alt Text'),
    '#default_value' => theme_get_setting('rsvp_logo_alt_text'),
    '#description' => t('Enter the alt text for the logos.'),
  ];

  // Add the site name image upload fields
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

  // Site name alt text field
  $form['rsvp_site_name_settings']['rsvp_logo_text_alt_text'] = [
    '#type' => 'textfield',
    '#title' => t('Site Name Alt Text'),
    '#default_value' => theme_get_setting('rsvp_logo_text_alt_text'),
    '#description' => t('Enter the alt text for the site name images.'),
  ];


$form['rsvp_color_settings'] = [
    '#type' => 'details',
    '#title' => t('Color Settings'),
    '#open' => TRUE,
  ];

  $colors = [
    'dark_primary' => '#183043',
    'accent_primary' => '#2B6CB0',
    'absolute_dark' => '#000000',
    'grey' => '#808080',
    'accent_light' => '#F2F6FB',
    'trace_primary' => '#EDF2F7',
    'trace_accent' => '#E2E8F0',
  ];

  foreach ($colors as $key => $default) {
    $form['rsvp_color_settings']["color_{$key}"] = [
      '#type' => 'textfield',
      '#title' => t(ucwords(str_replace('_',' ', $key)) . ' Color'),
      '#default_value' => theme_get_setting("color_{$key}") ?: $default,
      '#description' => t('Enter a hex color code (e.g. #183043).'),
      '#attributes' => [
        'pattern' => '^#[0-9A-Fa-f]{6}$',
        'title' => t('Please enter a valid 6-digit hex color code beginning with "#".')
      ],
    ];
  }

  // Add the submit handler as before.
  $form['#submit'][] = 'radix_rsvp_theme_settings_submit';
  // Add validation callback for server-side validation of hex codes.
  $form['#validate'][] = 'radix_rsvp_color_settings_validate';
}

/**
 * Submit handler to process file uploads and removals.
 */
function radix_rsvp_theme_settings_submit(&$form, FormStateInterface $form_state) {
  $logo_sizes = ['mobile', 'tablet', 'desktop'];
  foreach ($logo_sizes as $size) {
    foreach (['rsvp_logo', 'rsvp_site_name'] as $type) {
      foreach (['', '_invert'] as $suffix) {
        $file_fid = $form_state->getValue("{$type}_{$size}{$suffix}");
        $remove_field = $form_state->getValue("{$type}_{$size}{$suffix}_remove");

        if ($remove_field && !empty($file_fid[0])) {
          $file = File::load($file_fid[0]);
          if ($file) {
            $file->delete();
            $form_state->setValue("{$type}_{$size}{$suffix}", NULL);
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

/**
 * Validation callback to ensure color fields are valid hex codes.
 */
function radix_rsvp_color_settings_validate($form, FormStateInterface $form_state) {
  $colors = [
    'dark_primary',
    'accent_primary',
    'absolute_dark',
    'grey',
    'accent_light',
    'trace_primary',
    'trace_accent',
  ];
  foreach ($colors as $color_key) {
    $value = $form_state->getValue("color_{$color_key}");
    if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
      $form_state->setErrorByName("color_{$color_key}", t('Please enter a valid 6-digit hex code for @name.', [
        '@name' => str_replace('_', ' ', $color_key)
      ]));
    }
  }
}
