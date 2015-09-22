<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

	$query_array = array(
		'page' => $MODULE_PAGE_ID
	);
	if ( ! empty($BACK_URL)) {
		$query_array['back_url'] = $BACK_URL;
	}
	if ($orm->loaded()) {
		$p = Request::current()->query( Paginator::QUERY_PARAM );
		if ( ! empty($p)) {
			$query_array[ Paginator::QUERY_PARAM ] = $p;
		}
		$action = Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'edit',
			'id' => $orm->id,
			'query' => Helper_Page::make_query_string($query_array),
		));
	} else {
		$action = Route::url('modules', array(
			'controller' => 'megamenu_column',
			'action' => 'edit',
			'query' => Helper_Page::make_query_string($query_array),
		));
	}

	echo View_Admin::factory('layout/error')
		->set('errors', $errors);
?>
	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" class="form-horizontal kr-form-horizontal" >
		<div class="tabbable">
			<ul class="nav nav-tabs kr-nav-tsbs" id="nav-controls">
<?php
				echo '<li class="active">', HTML::anchor('#tab-main', __('Main'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				echo '<li>', HTML::anchor('#tab-rows', __('Rows'), array(
					'data-toggle' => 'tab',
				)), '</li>'; 
// 				echo '<li class="hidden">', HTML::anchor('#tab-rows', __('Rows'), array(
// 					'data-toggle' => 'tab',
// 				)), '</li>'; 
?>
			</ul>
			<div class="tab-content kr-tab-content">
				<div class="tab-pane kr-tab-pane active" id="tab-main">
<?php
					echo View_Admin::factory('modules/megamenu/column/tab/main', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
					)); 
?>
				</div>
				<div class="tab-pane kr-tab-pane" id="tab-rows">
<?php
					echo $rows_html; 
?>
				</div>
			</div>
			<script type="text/javascript">
			$(function(){
				var hash = location.hash;
				if (hash.length > 0) {
					$('#nav-controls :not(.hidden) [href="'+hash+'"]').click();
				}
			});
			</script>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit" value="save" ><?php echo __('Save'); ?></button>
			<button class="btn btn-primary" type="submit" name="submit" value="save_and_exit" ><?php echo __('Save and Exit'); ?></button>
			<button class="btn" name="cancel" value="cancel"><?php echo __('Cancel'); ?></button>
		</div>
	</form>
