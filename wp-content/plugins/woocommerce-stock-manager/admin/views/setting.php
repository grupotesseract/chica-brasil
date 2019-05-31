<?php 

if( isset( $_POST['save'] ) ){
	if( isset( $_POST['limit'] ) ){
    	update_option( 'woocommerce_stock_limit', sanitize_text_field( $_POST['limit'] ) );
	}	
	if( isset( $_POST['variable'] ) ){
    	update_option( 'woocommerce_stock_variable_stock', sanitize_text_field( $_POST['variable'] ) );
	}else{
		delete_option( 'woocommerce_stock_variable_stock' );
	}
	if( isset( $_POST['step'] ) ){
    	update_option( 'woocommerce_stock_qty_step', sanitize_text_field( $_POST['step'] ) );
	}
}

$limit = get_option( 'woocommerce_stock_limit' );
if( empty( $limit ) ){ $limit = 100; }
$variable = get_option( 'woocommerce_stock_variable_stock' );
if( empty( $variable ) ){ $variable = 'no'; }
$step = get_option( 'woocommerce_stock_qty_step' );
if( empty( $step ) ){ $step = '1'; }
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  
	<div class="t-col-6">
  		<div class="toret-box box-info">
    		<div class="box-header">
      			<h3 class="box-title"><?php _e('Stock manager setting','woocommerce-stock-manager'); ?></h3>
    		</div>
  			<div class="box-body">
  			<div class="clear"></div>
    			<form method="post" action="" style="position:relative;">
      				<table class="table-bordered">
      					<tr>
      						<th><?php _e('Products limit','woocommerce-stock-manager'); ?></th>
      						<td><input type="number" name="limit" value="<?php echo $limit; ?>" start="1" step="1" /></td>
      					</tr>
      					<tr>
      						<th><?php _e('Allow stock for variable products','woocommerce-stock-manager'); ?></th>
      						<td><input type="checkbox" name="variable" value="ok" <?php if( $variable == 'ok' ){ echo 'checked="checked"'; } ?> /></td>
      					</tr>
      					<tr>
      						<th><?php _e('Qty input step','woocommerce-stock-manager'); ?></th>
      						<td><input type="text" name="step" value="<?php echo $step; ?>" /></td>
      					</tr>
      				</table>
      				<input type="submit" name="save" class="btn btn-danger" />
      			</form>
  			</div>
 		</div>
	</div>
</div>