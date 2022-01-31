<?php

/**
 * @file
 * Views integration for the flag stats module.
 */

/**
 * Implements hook_views_data().
 */
function flag_stats_views_data() {
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
      'base' => 'node',
      'base field' => 'nid',
      'handler' => 'views_handler_relationship',
      'label' => t('Content'),
      'title' => t('content nid'),
      'help' => t('Flag statistics for node'),
    ],
  ];

  // fid field.
  $data['flag_stats']['fid'] = [
    'title' => t('Fid'),
    'help' => t('The unique ID for particular flag statistics.'),
    'field' => [
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
    ],
    'sort' => [
      'handler' => 'views_handler_sort',
    ],
    'filter' => [
      'handler' => 'views_handler_filter_string',
    ],
    'argument' => [
      'handler' => 'views_handler_argument_string',
    ],
  ];

  // Timestamp field.
  $data['flag_stats']['timestamp'] = [
    'title' => t('Timestamp'),
    'help' => t('The UNIX time stamp representing when the flag was set.'),
    'field' => [
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ],
    'sort' => [
      'handler' => 'views_handler_sort_date',
    ],
    'filter' => [
      'handler' => 'views_handler_filter_date',
    ],
  ];
  return $data;
}
