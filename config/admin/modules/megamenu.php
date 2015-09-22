<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'left_menu' => array(
		'megamenu' => array(
			'title' => __('Columns list'),
			'link' => Route::url('modules', array(
				'controller' => 'megamenu_column',
				'query' => 'page={PAGE_ID}',
			)),
			'sub' => array(),
		),
	),
	'a2'	=>	array(
		'resources' => array(
			'megamenu_column_controller' => 'module_controller',
			'megamenu_row_controller' => 'module_controller',
			'megamenu_column' => 'module',
		),
		'rules' => array(
			'allow' => array(
				'controller_column_access' => array(
					'role' => 'main',
					'resource' => 'megamenu_column_controller',
					'privilege' => 'access',
				),
				'megamenu_column_edit' => array(
					'role' => 'main',
					'resource' => 'megamenu_column',
					'privilege' => 'edit',
				),
				'megamenu_column_fix' => array(
					'role' => 'main',
					'resource' => 'megamenu_column',
					'privilege' => 'fix_positions',
				),
				
				'controller_row_access' => array(
					'role' => 'main',
					'resource' => 'megamenu_row_controller',
					'privilege' => 'access',
				),
				'megamenu_row_edit' => array(
					'role' => 'main',
					'resource' => 'megamenu_row',
					'privilege' => 'edit',
				),
				'megamenu_row_fix' => array(
					'role' => 'main',
					'resource' => 'megamenu_row',
					'privilege' => 'fix_positions',
				),
			),
		)
	),
);