<?php
$xpdo_meta_map['bagEmailsHistory']= array (
  'package' => 'bagemails',
  'version' => '1.0',
  'table' => 'bagemails_history',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'form_id' => 0,
    'data' => NULL,
    'createdon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'form_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'data' => 
    array (
      'dbtype' => 'json',
      'phptype' => 'string',
      'null' => false,
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'Form' => 
    array (
      'class' => 'bagEmalisForm',
      'local' => 'form_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
