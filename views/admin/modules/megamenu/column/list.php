<?php defined('SYSPATH') or die('No direct access allowed.'); 

	echo View_Admin::factory('layout/page_select');
	
	if ($list->count() > 0): 
		$query_array = array(
			'page' => $MODULE_PAGE_ID,
		);
		if ( ! empty($BACK_URL)) {
			$query_array['back_url'] = $BACK_URL;
		}
		$delete_tpl = Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'delete',
			'id' => '{id}',
			'query' => Helper_Page::make_query_string($query_array),
		));

		$p = Request::current()->query( Paginator::QUERY_PARAM );
		if ( ! empty($p)) {
			$query_array[ Paginator::QUERY_PARAM ] = $p;
		}
		$edit_tpl = Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'edit',
			'id' => '{id}',
			'query' => Helper_Page::make_query_string($query_array),
		));
		
		// Position link templates
		$query_array['mode'] = 'up';
		$up_tpl	= Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'position',
			'id' => '{id}',
			'query' => Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'down';
		$down_tpl = Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'position',
			'id' => '{id}',
			'query' => Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'first';
		$first_tpl =  Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'position',
			'id' => '{id}',
			'query'	=> Helper_Page::make_query_string($query_array),
		));
		
		$query_array['mode'] = 'last';
		$last_tpl =  Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'position',
			'id' => '{id}',
			'query'	=> Helper_Page::make_query_string($query_array),
		));
		
?>
		<table class="table table-bordered table-striped">
			<colgroup>
				<col class="span1">
				<col class="span6">
				<col class="span2">
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('ID'); ?></th>
					<th><?php echo __('Title'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
<?php 
			foreach ($list as $_orm):
?>
			<tr>
				<td><?php echo $_orm->id ?></td>
				<td>
<?php
					if ( (bool) $_orm->active) {
						echo '<i class="icon-eye-open"></i>&nbsp;';
					} else {
						echo '<i class="icon-eye-open" style="background: none;"></i>&nbsp;';
					}
					echo HTML::chars($_orm->title)
?>
				</td>
				<td>
<?php
				if ($ACL->is_allowed($USER, $_orm, 'edit')) {
					
					echo '<div class="btn-group">';
					
						echo HTML::anchor(str_replace('{id}', $_orm->id, $edit_tpl), '<i class="icon-edit"></i> '.__('Edit'), array(
							'class' => 'btn',
							'title' => __('Edit'),
						));
					
						echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
						echo '<ul class="dropdown-menu">';
						
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $first_tpl), '<i class="icon-arrow-first"></i> '.__('Move first'), array(
								'title' => __('Move first'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $up_tpl), '<i class="icon-arrow-up"></i> '.__('Move up'), array(
								'title' => __('Move up'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $down_tpl), '<i class="icon-arrow-down"></i> '.__('Move down'), array(
								'title' => __('Move down'),
							)), '</li>';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $last_tpl), '<i class="icon-arrow-last"></i> '.__('Move last'), array(
								'title' => __('Move last'),
							)), '</li>';
						
							echo '<li class="divider"></li>';
							
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $delete_tpl), '<i class="icon-remove"></i> '.__('Delete'), array(
								'class' => 'delete_button',
								'title' => __('Delete'),
							)), '</li>';
						echo '</ul>';
					echo '</div>';
				}
?>
				</td>
			</tr>
<?php 
		endforeach;
?>
		</tbody>
	</table>
<?php
	if (empty($BACK_URL)) {
		$link = Route::url('modules', array(
			'controller' => 'megamenu_column',
		));
	} else {
		$link = $BACK_URL;
	}
	
	echo $paginator->render($link);
endif;
