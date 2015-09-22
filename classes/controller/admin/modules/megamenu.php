<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Megamenu extends Controller_Admin_Front {

	public $inner_layout = 'layout/inner';

	protected $top_menu_item = 'modules';
	protected $sub_title = 'Megamenu';

	protected function get_module_pages($module_key)
	{
		return ORM::factory('page')
			->where('megamenu', '>', 0)
			->find_all();
	}
	
	protected function get_aside_view()
	{
		$menu_items = array_merge_recursive(
			$this->module_config->get('left_menu'),
			$this->_ex_menu_items
		);

		return parent::get_aside_view()
			->set('menu_items', $menu_items)
			->set('replace', array(
				'{PAGE_ID}' =>	$this->module_page_id,
			));
	}

	protected function left_menu_column_add()
	{
		$this->_ex_menu_items = array_merge_recursive($this->_ex_menu_items, array(
			'megamenu' => array(
				'sub' => array(
					'add_column' => array(
						'title' => __('Add column'),
						'link' => Route::url('modules', array(
							'controller' => 'megamenu_column',
							'action' => 'edit',
							'query' => 'page='.$this->module_page_id,
						)),
					),
				),
			),
		));
	}
	
	protected function left_menu_column_fix()
	{
		$this->_ex_menu_items = array_merge_recursive($this->_ex_menu_items, array(
			'fix' => array(
				'title' => __('Fix positions'),
				'link'  => Route::url('modules', array(
					'controller' => 'megamenu_column',
					'action' => 'position',
					'query' => 'page='.$this->module_page_id.'&mode=fix',
				)),
			),
		));
	}
	
	protected function left_menu_row_add($column_id)
	{
		$back_url = $this->request->current()->url();
		$this->_ex_menu_items = array_merge_recursive($this->_ex_menu_items, array(
			'megamenu' => array(
				'sub' => array(
					'add_row' => array(
						'title' => __('Add row'),
						'link' => Route::url('modules', array(
							'controller' => 'megamenu_row',
							'action' => 'edit',
							'query' => 'page='.$this->module_page_id.'&column='.$column_id.'&back_url='.$back_url,
						)),
					),
				),
			),
		));
	}
	
	protected function left_menu_row_fix($column_id)
	{
		$back_url = $this->request->current()->url();
		$this->_ex_menu_items = array_merge_recursive($this->_ex_menu_items, array(
			'fix' => array(
				'title' => __('Fix positions'),
				'class' => 'js-menu-item-row-fix',
				'link'  => Route::url('modules', array(
					'controller' => 'megamenu_row',
					'action' => 'position',
					'query' => 'page='.$this->module_page_id.'&mode=fix&column='.$column_id.'&back_url='.$back_url,
				)),
			),
		));
	}
} 
