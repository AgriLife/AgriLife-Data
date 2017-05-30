<?php

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array (
    'key' => 'group_57acdaa51dc58',
    'title' => 'Agency Affiliation',
    'fields' => array (
      array (
        'key' => 'field_57acdad9e680e',
        'label' => 'Agency Top',
        'name' => 'agency_top',
        'type' => 'checkbox',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array (
          'college' => 'College',
          'extension' => 'Extension',
          'research' => 'Research',
          'tfs' => 'TFS',
          'tvmdl' => 'TVMDL',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => array (
          0 => 'college',
        ),
        'layout' => 'vertical',
        'allow_custom' => 0,
        'save_custom' => 0,
        'toggle' => 0,
        'return_format' => 'value',
      ),
      array (
        'key' => 'field_57acdd9bc9f10',
        'label' => 'Extension Type',
        'name' => 'ext_type',
        'type' => 'checkbox',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array (
          array (
            array (
              'field' => 'field_57acdad9e680e',
              'operator' => '==',
              'value' => 'extension',
            ),
          ),
        ),
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array (
          'typical' => 'Typical',
          '4h' => '4-H',
          'county' => 'County Office',
          'tce' => 'County TCE Office',
          'mg' => 'Master Gardener Chapter',
          'mn' => 'Master Naturalist Chapter',
          'sg' => 'Sea Grant',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'save_other_choice' => 0,
        'default_value' => array (
          0 => 'typical',
        ),
        'layout' => 'vertical',
        'allow_custom' => 0,
        'save_custom' => 0,
        'toggle' => 0,
        'return_format' => 'value',
      ),
      array (
        'key' => 'field_5925ec2bb1dd3',
        'label' => 'Research Center Name',
        'name' => 'research_center_name',
        'type' => 'select',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array (
          array (
            array (
              'field' => 'field_57acdad9e680e',
              'operator' => '==',
              'value' => 'research',
            ),
          ),
        ),
        'wrapper' => array (
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array (
          0 => 'N/A',
          353 => 'Amarillo Research and Extension Center',
          354 => 'Beaumont Research and Extension Center',
          355 => 'Corpus Christi Research and Extension Center',
          356 => 'Dallas Research and Extension Center',
          329 => 'El Paso Research and Extension Center',
          357 => 'Lubbock Research and Extension Center',
          358 => 'Overton Research and Extension Center',
          359 => 'San Angelo Research and Extension Center',
          361 => 'Stephenville Research and Extension Center',
          362 => 'Temple Research and Extension Center',
          363 => 'Uvalde Research and Extension Center',
          364 => 'Vernon Research and Extension Center',
          365 => 'Weslaco Research and Extension Center',
        ),
        'default_value' => array (
          0 => 0,
        ),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'ajax' => 0,
        'return_format' => 'value',
        'placeholder' => '',
      ),
    ),
    'location' => array (
      array (
        array (
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'agrilifedata-siteaffiliation',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
  ));

endif;
