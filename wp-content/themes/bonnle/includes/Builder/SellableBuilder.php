<?php

namespace Sitelink\Builder;

use Sitelink\Classes\Material;
use Sitelink\Classes\Option;
use Sitelink\Classes\Section;
use Sitelink\Classes\Sellable;
use Sitelink\Classes\SellableSection;

class SellableBuilder {

	/**
	 * @var SellableBuilder
	 */
	private static $_instance = null;

	/**
	 * SellableBuilder constructor.
	 */
	private function __construct() {

	}

	private $sections = array();

	/**
	 * @return SellableBuilder
	 */
	public static function getInstance() {
		if ( ! SellableBuilder::$_instance ) {
			SellableBuilder::$_instance = new SellableBuilder();
		}

		return SellableBuilder::$_instance;
	}

	public function buildFromId( $id ) {
		$ret = new Sellable();
		$ret->setId( $id );
		$ret->setImage( get_the_post_thumbnail_url( $id ) );

		$ret->setName( get_the_title( $id ) );
		$ret->setCode( get_field( "code", $id ) );
		$ret->setProduct( get_field( "woocommerce_product", $id ) );
		$_product = wc_get_product( $ret->getProduct() );
		$ret->setBasePrice( floatval( $_product->get_price() ) );

		if ( get_field( "sections", $id ) ) {
			$ret->setSections( array_map( function ( $section ) {
				return $this->buildSection( $section["section"] );
			}, get_field( "sections", $id ) ) );
		}

		return $ret;
	}

	private function buildSection( $section ) {
		$code = get_field( "code", $section );

		if ( array_key_exists( $code, $this->sections ) ) {
			return $this->sections[ $code ];
		}

		$ret = new Section();
		$ret->setName( get_the_title( $section ) );
		$ret->setCode( $code );
		$ret->setId($section);

		$icon = get_field( "icon", $section );
		if ( $icon != null ) {
			$ret->setIcon( get_field( "icon", $section )["url"] );
		}

		$ret->setZindex( get_field( "z-index", $section ) );
		if ( get_field( "options", $section ) ) {
			$ret->setOptions( array_map( function ( $option ) {
				return $this->buildOption( $option );
			}, get_field( "options", $section ) ) );
		}

		$this->sections[ $code ] = $ret;

		return $ret;
	}

	private function buildOption( $option ) {
		$ret = new Option();
		if ( $option["image"] != null ) {
			$ret->setImage( $option["image"]["sizes"]["configurator-size"] );
			$ret->setSmallImage( $option["image"]["sizes"]["medium"] );
			$ret->setMediumImage( $option["image"]["sizes"]["medium_large"] );
			$ret->setImagePath( $this->scaled_image_path( $option["image"]["id"], 'full' ) );
		}
		if ( $option["material"] ) {
			$ret->setMaterial( $this->buildMaterial( $option["material"] ) );
		}
		if ( $option["price"] != null ) {
			$ret->setPrice( floatval( $option["price"] ) );
		} else {
			$ret->setPrice( 0 );
		}

		if ( $option["qty"] != null ) {
			$ret->setQty( floatval( $option["qty"] ) );
		} else {
			$ret->setQty( 0 );
		}

		return $ret;
	}

	private function buildMaterial( $material ) {
		$ret = new Material();
		$ret->setCode( get_field( "code", $material ) );
		$ret->setImage( get_the_post_thumbnail_url( $material ) );
		$ret->setSmallImage( get_the_post_thumbnail_url( $material, 'medium' ) );
		$ret->setMediumImage( get_the_post_thumbnail_url( $material, 'medium_large' ) );
		$ret->setEnabled( get_field( "enabled", $material ) === true );
		$ret->setName( get_field( "name", $material ) );
		$terms = wp_get_post_terms( $material, 'material_category' );
		if ( $terms && count( $terms ) ) {
			$ret->setCategoryId( $terms[0]->term_id );
			$ret->setCategoryName( $terms[0]->name );
		}

		return $ret;
	}

	private function scaled_image_path( $attachment_id, $size = 'thumbnail' ) {
		$file = get_attached_file( $attachment_id, true );
		if ( empty( $size ) || $size === 'full' ) {
			// for the original size get_attached_file is fine
			return realpath( $file );
		}
		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			return false; // the id is not referring to a media
		}
		$info = image_get_intermediate_size( $attachment_id, $size );
		if ( ! is_array( $info ) || ! isset( $info['file'] ) ) {
			return false; // probably a bad size argument
		}

		return realpath( str_replace( wp_basename( $file ), $info['file'], $file ) );
	}
}
