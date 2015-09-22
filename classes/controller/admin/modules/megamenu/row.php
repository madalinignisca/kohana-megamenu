<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Modules_Megamenu_Row extends Controller_Admin_Modules_Megamenu {

	protected $module_config = 'megamenu';
	private $column;
	
	public function before()
	{
		parent::before();
		
		$column_id = (int) $this->request->current()
			->query('column');
		$this->column = ORM::factory('megamenu_Column')
			->and_where('id', '=', $column_id)
			->and_where('page_id', '=', $this->module_page_id)
			->find();
		
		if ($this->column->loaded()) {
			$this->sub_title = $this->column->title;
		}
		
		$this->template
			->set('COLUMN_ID', $this->column->id);
			
	}
	
	public function action_index()
	{
		$orm = ORM::factory('megamenu_Row')
			->where('column_id', '=', $this->column->id);

		$paginator_orm = clone $orm;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(20)
			->count($paginator_orm->count_all());
		unset($paginator_orm);

		$list = $orm
			->paginator($paginator)
			->find_all();
		
		$acl_edit = $this->acl->is_allowed($this->user, $orm, 'edit');
		$this->left_menu_column_add();
		if ($acl_edit) {
			$this->left_menu_row_add($this->column->id);
			$this->left_menu_row_fix($this->column->id);
		}
		
		$this->title = __('Columns list');
		$this->template
			->set_filename('modules/megamenu/row/list')
			->set('list', $list)
			->set('paginator', $paginator);
	}

	public function action_edit()
	{
		$id = (int) Request::current()->param('id');
		$helper_orm = ORM_Helper::factory('megamenu_Row');
		$orm = $helper_orm->orm();
		if ( (bool) $id) {
			$orm
				->and_where('id', '=', $id)
				->and_where('column_id', '=', $this->column->id)
				->find();
			if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit row');
		} else {
			$this->title = __('Add row');
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'page' => $this->module_page_id,
				'column' => $this->column->id,
			);
			$p = Request::current()->query( Paginator::QUERY_PARAM );
			if ( ! empty($p)) {
				$query_array[ Paginator::QUERY_PARAM ] = $p;
			}
			$query = Helper_Page::make_query_string($query_array);
	
			$this->back_url = Route::url('modules', array(
				'controller' => 'megamenu_row',
				'query' => $query,
			));
		}
		if ($this->is_cancel) {
			Request::current()->redirect($this->back_url);
		}

		$errors = array();
		$submit = Request::$current->post('submit');
		if ($submit) {
			try {
				if ( (bool) $id) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
				} else {
					$orm->creator_id = $this->user->id;
					$orm->column_id = $this->column->id;
				}
			
				$values = Request::$current->post();
			
				$helper_orm->save($values + $_FILES);
				Controller_Admin_Structure::clear_structure_cache();
			} catch (ORM_Validation_Exception $e) {
				$errors = $e->errors('');
				if ( ! empty($errors['_files'])) {
					$errors = array_merge($errors, $errors['_files']);
					unset($errors['_files']);
				}
			}
		}

		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			$this->left_menu_column_add();
			$acl_edit = $this->acl->is_allowed($this->user, $orm, 'edit');
			if ($acl_edit) {
				$this->left_menu_row_add($this->column->id);
				$this->left_menu_row_fix($this->column->id);
			}
			
			$this->template
				->set_filename('modules/megamenu/row/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm);
		} else {
			Request::current()->redirect($this->back_url);
		}
	}

	public function action_delete()
	{
		$id = (int) Request::current()->param('id');
	
		$helper_orm = ORM_Helper::factory('megamenu_Row');
		$orm = $helper_orm->orm();
		$orm
			->and_where('id', '=', $id)
			->and_where('column_id', '=', $this->column->id)
			->find();
	
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
	
		if ($this->delete_element($helper_orm)) {
			Controller_Admin_Structure::clear_structure_cache();
				
			if (empty($this->back_url)) {
				$query_array = array(
					'page' => $this->module_page_id,
					'column' => $this->column->id,
				);
				$list_url = Route::url('modules', array(
					'controller' => 'megamenu_row',
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
			Request::current()->redirect($this->back_url);
		}
	}
	
	public function action_position()
	{
		$id = (int) Request::current()->param('id');
		$mode = Request::current()->query('mode');
		$errors = array();
		
		$helper_orm = ORM_Helper::factory('megamenu_Row');
		$orm = $helper_orm->orm();
	
		try {
			if ($mode !== 'fix') {
				$orm
					->and_where('id', '=', $id)
					->and_where('column_id', '=', $this->column->id)
					->find();
				
				if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
					throw new HTTP_Exception_404();
				}
	
				switch ($mode) {
					case 'up':
						$helper_orm
							->position_move('position', ORM_Position::MOVE_PREV);
						break;
					case 'down':
						$helper_orm
							->position_move('position', ORM_Position::MOVE_NEXT);
						break;
					case 'first':
						$helper_orm
							->position_first('position');
						break;
					case 'last':
						$helper_orm
							->position_last('position');
						break;
				}
			} else {
				if ($this->acl->is_allowed($this->user, $orm, 'fix_positions')) {
					$helper_orm
						->position_fix('position');
				}
			}
	
			Controller_Admin_Structure::clear_structure_cache();
		} catch (ORM_Validation_Exception $e) {
			$errors = $e->errors('');
			$this->template
				->set_filename('layout/error')
				->set('errors', $errors)
				->set('title', __('Error'));
		}
	
		if (empty($errors)) {
				
			if (empty($this->back_url)) {
				$query_array = array(
					'page' => $this->module_page_id,
					'column' => $this->column->id,
				);
				
				if ($mode != 'fix') {
					$p = Request::current()->query( Paginator::QUERY_PARAM );
					if ( ! empty($p)) {
						$query_array[ Paginator::QUERY_PARAM ] = $p;
					}
				}
				
				$this->back_url = Route::url('modules', array(
					'controller' => 'megamenu_row',
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
				
			Request::current()->redirect($this->back_url);
		}
	}
	
} 
