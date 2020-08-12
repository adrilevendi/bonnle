<?php
/**
 * API.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Functions;

use Sitelink\Repository\SellableRepository;
use Sitelink\Updater\Updater;
use Sitelink\Utils\Utils;

class WooCommerceFunctions {
	/**
	 * @var WooCommerceFunctions
	 */
	private static $_instance = null;

	/**
	 * WooCommerceFunctions constructor.
	 */
	private function __construct() {

	}

	public function loadHooks() {
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_custom_item_data' ), 10, 2 );
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'get_custom_product_code' ), 30, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'add_code_order_item_meta' ), 20, 4 );
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_price' ), 20, 1 );
		add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'generate_product_thumbnail' ), 10, 3 );
		add_filter( 'woocommerce_admin_order_item_thumbnail', array( $this, 'admin_order_product_thumb' ), 20, 3 );
		add_filter( 'woocommerce_order_item_thumbnail', array( $this, 'order_product_thumb' ), 20, 2 );
		add_action( 'woocommerce_after_order_itemmeta', array( $this, 'print_item_order_data' ), 10, 3 );
		add_action( 'woocommerce_order_item_meta_end', array( $this, 'order_item_meta_end' ), 10, 4 );
		add_filter( 'woocommerce_email_order_items_table', array( $this, 'add_image_to_emails' ), 10, 2 );
		add_action( 'woocommerce_new_order', array($this, 'on_new_order'), 10, 2 );
		add_action( 'woocommerce_order_refunded', array($this, 'on_order_refound'), 10, 2 );
		//add_action( 'woocommerce_new_order_item', array($this,'new_order_item'), 10, 2);

	}

	/**
	 * @return WooCommerceFunctions
	 */
	public static function getInstance() {
		if ( ! WooCommerceFunctions::$_instance ) {
			WooCommerceFunctions::$_instance = new WooCommerceFunctions();
		}

		return WooCommerceFunctions::$_instance;
	}


	public function get_custom_product_code( $cart_item_data, $product_id ) {
		if ( isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ) {
			$cart_item_data['code'] = sanitize_text_field( $_GET['code'] );
		}

		return $cart_item_data;
	}


	// Display note in cart and checkout pages as cart item data - Optional
	public function display_custom_item_data( $cart_item_data, $cart_item ) {
		if ( isset( $cart_item['code'] ) ) {
			$sellable = SellableRepository::getInstance()->getSellableVariationFromCode( $cart_item["code"] );

			$cart_item_data[] = array(
				'key'   => __( 'code', 'choose' ),
				'value' => wc_clean( $cart_item['code'] )
			);

			foreach ( $sellable->getSelectedSections() as $section ) {
				$cart_item_data[] = array(
					'key'   => $section->getName(),
					'value' => $section->getOptions()[0]->getMaterial()->getCategoryName() . " - " . $section->getOptions()[0]->getMaterial()->getName()
				);
			}
		}

		return $cart_item_data;
	}

	// Save and display product note in orders and email notifications (everywhere)
	public function add_code_order_item_meta( $item, $cart_item_key, $values, $order ) {
		if ( isset( $values['code'] ) ) {
			$item->update_meta_data( 'code', $values['code'] );
		}
	}

	public function calculate_price( $cart ) {

		// This is necessary for WC 3.0+
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		// Avoiding hook repetition (when using price calculations for example)
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}

		// Loop through cart items
		foreach ( $cart->get_cart() as $item ) {
			$sellable = SellableRepository::getInstance()->getSellableVariationFromCode( $item["code"] );
			$item['data']->set_price( $sellable->getPrice() );
		}
	}

	public function generate_product_thumbnail( $_product_img, $cart_item, $cart_item_key ) {
		$html = "";
		if(isset($cart_item["code"]) && $cart_item["code"] != "") {
			$sellable = SellableRepository::getInstance()->getSellableVariationFromCode( $cart_item["code"] );
			$images   = $sellable->getImages( 'small' );
			$i        = 0;
			$html     = "<div style='display:block;position:relative;width:150px;height:150px'>";
			foreach ( $images as $image ) {
				$html .= '<div style="background-size:cover;position:absolute;top:0;left:0;width:150px;height:150px;background-image:url(\'' . $image . '\');z-index:' . $i . '"></div>';
				$i    += 10;
			}
			$html .= "</div>";
		}
		return $html;
	}


	public function admin_order_product_thumb( $image, $item_id, $item ) {
		$code     = $item->get_meta( "code" );
		if(isset($code) && $code != "" ) {
			$sellable = SellableRepository::getInstance()->getSellableVariationFromCode( $code );
			$img      = Utils::getInstance()->getSellableImage( $sellable, 300 );

			return "<img src='" . $img . "' />";
		}
		return "";
	}

	public function print_item_order_data( $item_id, $item, $product ) {
		$code     = $item->get_meta( "code" );
		if(isset($code) && $code != "" ) {
			$sellable = SellableRepository::getInstance()->getSellableVariationFromCode( $code );
			foreach ( $sellable->getSelectedSections() as $section ) {
				echo "<div><strong>" . $section->getName() . "</strong>: " . $section->getOptions()[0]->getMaterial()->getCategoryName() . " - " . $section->getOptions()[0]->getMaterial()->getName() . "</div>";
			}
		}
	}

	public function order_item_meta_end( $item_id, $item, $order, $plain_text ) {
		$this->print_item_order_data( $item_id, $item, null );
	}

	public function order_product_thumb( $image, $item ) {
		return $this->admin_order_product_thumb( null, null, $item );
	}

	public function add_image_to_emails( $output, $order ) {
		static $run = 0;
		if ( $run ) {
			return $output;
		}
		$args = array(
			'show_image' => true
		);
		$run ++;

		return $order->email_order_items_table( $args );
	}

	public function on_new_order($order_id, $order)
	{
		$codes = array();
		foreach ($order->get_items() as $item_id => $item_data) {
			for($i=0;$i<$item_data->get_quantity();$i++)
				$codes[] = $item_data->get_meta( 'code', true );
		}
		$qtys = Utils::getInstance()->getQtyFromCodes($codes);
		foreach ($qtys as $key => $value)
		{
			Updater::getInstance()->updateAvailability($key,$value);
		}
	}

	public function on_order_refound($order_id, $refound_id)
	{
		$codes = array();
		$order = wc_get_order($order_id);
		foreach ($order->get_items() as $item_id => $item_data) {
			for($i=0;$i<$item_data->get_quantity();$i++)
				$codes[] = $item_data->get_meta( 'code', true );
		}
		$qtys = Utils::getInstance()->getQtyFromCodes($codes);
		foreach ($qtys as $key => $value)
		{
			$value = $value * -1;
			Updater::getInstance()->updateAvailability($key,$value);
		}
	}

	public function woocommerce_new_order_item($item_id, $order_id)
	{
	}
}
