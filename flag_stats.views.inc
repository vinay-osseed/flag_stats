<?php

/**
 * @file
 * Contains views API hooks for Flag module.
 */

/**
 * Implements hook_views_data().
 */
function flag_stats_views_data() {
  $data = [];
  $data['flag_stats']['table']['group'] = t('Flag Statistics');

  // Define this as base table.
  $data['flag_stats']['table']['base'] = [
    // This is the identifier field for the view.
    'field' => 'fid',
    'title' => t('Flag Statistics'),
    'help' => t('Flag Statistics table contains flagged or unflagged content data.'),
    'weight' => -10,
  ];

  // This table references the {node} table. The declaration below creates an
  // 'implicit' relationship to the node table, so that when 'node' is the base
  // table, the fields are automatically available.
  $data['flag_stats']['table']['join'] = [
    'node' => [
      // The primary key in the referenced table.
      'left_field' => 'nid',
      // The foreign key in this table.
      'field' => 'nid',
    ],
  ];

  // Define a relationship to the {node} table.
  $data['flag_stats']['entity_id'] = [
    'relationship' => [
      'base' => 'node_field_data',
      'id' => 'standard',
      'label' => t('Content'),
      'title' => t('Content'),
      'help' => t('Flag statistics for node'),
    ],
  ];

  // Fid field.
  $data['flag_stats']['fid'] = [
    'title' => t('Fid'),
    'help' => t('The unique ID for particular flag statistics.'),
    'field' => [
      'id' => 'standard',
    ],
    'sort' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'numeric',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
  ];

  // Timestamp field.
  $data['flag_stats']['timestamp'] = [
    'title' => t('Timestamp'),
    'help' => t('The UNIX time stamp representing when the flag was set.'),
    'field' => [
      'id' => 'date',
    ],
    'sort' => [
      'id' => 'date',
    ],
    'filter' => [
      'id' => 'date',
    ],
  ];

  return $data;
}
