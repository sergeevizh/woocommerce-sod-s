<?php
/*
Plugin Name: Simple order delivery for WooCommerce
Description: Simple order delivery
Author: Systemo
Author URI: http://systemo.biz
Version: 0.1
Plugin URI: https://github.com/systemo-biz/woocommerce-sod-s
*/


// Отключаем выбор типа оплаты
add_filter('woocommerce_cart_needs_payment', '__return_false');
// Отключаем выбор способа доставки
add_filter('woocommerce_cart_needs_shipping', '__return_false');


// Убираем ненужные поля
add_filter( 'woocommerce_checkout_fields' , 'remove_extra_checkout_fields' );
function remove_extra_checkout_fields( $fields ) {
  //var_dump($fields);die;

	unset( $fields['billing']['billing_last_name'] );
	unset( $fields['billing']['billing_company'] );
	//unset( $fields['billing']['billing_address_1'] );
	unset( $fields['billing']['billing_address_2'] );
	unset( $fields['billing']['billing_city'] );
  unset( $fields['billing']['billing_email'] );
	unset( $fields['billing']['billing_postcode'] );
	unset( $fields['billing']['billing_country'] );
	unset( $fields['billing']['billing_state'] );
	unset( $fields['shipping']['shipping_first_name'] );
	unset( $fields['shipping']['shipping_last_name'] );
	unset( $fields['shipping']['shipping_company'] );
	unset( $fields['shipping']['shipping_address_1'] );
	unset( $fields['shipping']['shipping_address_2'] );
	unset( $fields['shipping']['shipping_city'] );
	unset( $fields['shipping']['shipping_postcode'] );
	unset( $fields['shipping']['shipping_country'] );
	unset( $fields['shipping']['shipping_state'] );
	unset( $fields['account']['account_username'] );
	unset( $fields['account']['account_password'] );
	unset( $fields['account']['account_password-2'] );
	unset( $fields['order']['order_comments'] );
    return $fields;
}

// Убираем из корзины переход в чекаут и ставим свою форму
function custom_checkout_form() {
	//load_template( plugin_dir_path( __FILE__ ) . 'templates/form-checkout.php', true );

  remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

  ?>

  <h2 class="entry-title">Оформление заказа</h2>

  <form name="checkout" method="post" class="checkout woocommerce-checkout form-horizontal" action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" enctype="multipart/form-data">

    <div class="form-group">
    	<label for="billing_first_name" class="control-label"><?php _e( 'First name', 'woocommerce' ); ?></label>
    	<input
        type="text"
        id="billing_firts_name" class="input-text form-control"
        name="billing_first_name"
        required="required"
        placeholder=""
        value=""
      />
    </div>

    <div class="form-group">
    	<label for="billing_phone" class="control-label col-md-3"><?php _e( 'Phone', 'woocommerce' ); ?></label>
    	<input type="text" class="input-text form-control" name="billing_phone" id="billing_phone" required="required" placeholder=""
        value=""
      />
    </div>

    <div class="form-group">
      <?php
        $billing_address_1 = get_user_meta( get_current_user_id(), 'billing_address_1', true );
      ?>
    	<label for="billing_address_1" class="control-label"><?php _e( 'Shipping address', 'woocommerce' ); ?></label>
    	<input type="text" class="input-text form-control" name="billing_address_1" id="billing_address_1" required="required" placeholder=""
        value="<?php esc_attr_e( $billing_address_1 ); ?>"
      />
    </div>

    <div class="form-group">
    	<div class="checkout-submit">
    		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
    		<input type="submit" class="checkout-button button alt wc-forward" name="woocommerce_checkout_place_order" id="place_order" value="Оформить заказ" data-value="Оформить заказ" />
    	</div>
    </div>

  </form>
  <?php
}
add_action( 'woocommerce_cart_collaterals', 'custom_checkout_form', 1 );
