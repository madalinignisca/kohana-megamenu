<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();
	
	
/**** active ****/
	
	echo View_Admin::factory('form/control', array(
		'field'    => 'active',
		'errors'   => $errors,
		'labels'   => $labels,
		'required' => $required,
		'controls' => Form::hidden('active', '').Form::checkbox('active', '1', (bool) $orm->active, array(
			'id' => 'active_field',
		)),
	));
	
/**** title ****/
	
	echo View_Admin::factory('form/control', array(
		'field'		=>	'title',
		'errors'	=>	$errors,
		'labels'	=>	$labels,
		'required'	=>	$required,
		'controls'	=>	Form::input('title', $orm->title, array(
			'id'       => 'title_field',
			'class'    => 'input-xlarge',
		)),
	));
	
/**** mobile_visibility ****/
	
	echo View_Admin::factory('form/control', array(
		'field'    => 'mobile_visibility',
		'errors'   => $errors,
		'labels'   => $labels,
		'required' => $required,
		'controls' => Form::select('mobile_visibility', Kohana::$config->load('_megamenu.mobile_visibility'), $orm->mobile_visibility, array(
			'id'      => 'mobile_visibility_field',
			'class'   => 'input-xlarge',
		)),
	));
	
/**** link ****/
	
	echo View_Admin::factory('form/control', array(
		'field'		=>	'link',
		'errors'	=>	$errors,
		'labels'	=>	$labels,
		'required'	=>	$required,
		'controls'	=>	Form::input('link', $orm->link, array(
			'id'       => 'link_field',
			'class'    => 'input-xlarge',
		)),
	));
	