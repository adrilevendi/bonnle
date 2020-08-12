<?php

namespace Sitelink\Repository;

use Sitelink\Builder\SellableBuilder;
use Sitelink\Classes\Section;
use Sitelink\Classes\Sellable;
use Sitelink\Classes\SellableVariation;
use Sitelink\Utils\Utils;

class SellableRepository {

	/**
	 * @var SellableRepository
	 */
	private static $_instance = null;
	private $cache = array();
	private $inventory = array();
	/**
	 * @var bool
	 */
	private $cacheLoaded = false;

	/**
	 * SellableRepository constructor.
	 */
	private function __construct() {
		$this->loadCache();
	}

	public function onDestruct() {
		set_transient( "choose_cache", $this->cache, 0 );
		set_transient( "choose_inventory", $this->inventory, 0 );

	}

	public function loadHooks() {
		add_action( 'save_post', array( $this, 'onPostSavedOrUpdated' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'onPostDeleted' ), 10, 3 );
		add_action( 'choose_section_qty_updates', array( $this, 'onQtyUpdated' ), 10, 2 );

	}

	public function onPostDeleted( $post_ID ) {
		$type = get_post_type( $post_ID );
		if (
			$type === "material" ||
			$type === "sellable" ||
			$type === "config_sections" ) {
			$this->emptyCache();
		}
	}

	public function onPostSavedOrUpdated( $post_ID, $post, $update ) {
		$type = get_post_type( $post_ID );

		if (
			$type === "material" ||
			$type === "sellable" ) {
			$this->emptyCache();
		}

		if ( $type === "config_sections" && !$update ) {
			$this->emptyCache();
		}
	}

	private function emptyCache() {
		$this->cache     = array();
		$this->inventory = array();
		delete_transient( "choose_cache" );
		delete_transient( "choose_inventory" );
	}

	public function onQtyUpdated( $code, $difference ) {
		if ( array_key_exists( $code, $this->inventory ) ) {
			$this->setInventoryItem( $code, $this->inventory[ $code ] - $difference );
		}
	}

	/**
	 * @return SellableRepository
	 */
	public static function getInstance() {
		if ( ! SellableRepository::$_instance ) {
			SellableRepository::$_instance = new SellableRepository();
		}

		return SellableRepository::$_instance;
	}

	public function getSellableFromId( $id ) {
		$key = "sellable_" . $id;

		if ( $this->inCache( $key ) ) {
			return $this->getFromCache( $key );
		}

		$ret = SellableBuilder::getInstance()->buildFromId( $id );
		$this->setCache( $key, $ret );

		return $ret;
	}

	public function getAllSellable() {
		$key = "all_sellable";
		if ( $this->inCache( $key ) ) {
			return $this->getFromCache( $key );
		}

		$sellables = get_posts( array(
			"post_type"        => "sellable",
			"numberposts"      => - 1,
			"suppress_filters" => true,
		) );

		$data = array_map( function ( $sellable ) {
			return SellableBuilder::getInstance()->buildFromId( $sellable->ID );
		}, $sellables );
		$this->setCache( $key, $data );

		$this->rebuildInventory( $data );

		return $data;
	}

	private function rebuildInventory( $sellables ) {
		$this->inventory = [];
		foreach ( $sellables as $sellable ) {
			$sections = $sellable->getSections();
			foreach ( $sections as $section ) {
				$this->updateInventory( $section );
			}
		}
	}

	private function updateInventory( $section ) {
		$options = $section->getOptions();
		foreach ( $options as $option ) {
			$key = strtoupper( $section->getCode() . $option->getMaterial()->getCode() );
			$this->setInventoryItem( $key, $option->getQty() );
		}
	}

	/**
	 * @param $code
	 *
	 * @return Sellable|null
	 */
	public function getSellableFromCode( $code ) {
		if ( $this->inCache( $code ) ) {
			return $this->getFromCache( $code );
		}

		$sellables = $this->getAllSellable();
		$sellable  = array_filter( $sellables, function ( $sellable ) use ( $code ) {
			return $sellable->getCode() == $code;
		} );
		if ( count( $sellable ) ) {
			$this->setCache( $code, array_values( $sellable )[0] );

			return array_values( $sellable )[0];
		} else {
			return null;
		}
	}

	public function getSpentFromCodes( $codes ) {
		$qtys = Utils::getInstance()->getQtyFromCodes( $codes );

		$spent = false;
		foreach ( $qtys as $key => $qty ) {
			$spent = $spent || $this->getInventoryItem( $key ) < $qty;
		}

		return $spent;
	}

	/**
	 * @param $code
	 *
	 * @return SellableVariation
	 */
	public function getSellableVariationFromCode( $code ) {
		if ( $this->inCache( $code ) ) {
			return $this->getFromCache( $code );
		}

		$ret           = new SellableVariation();
		$sellable_code = substr( $code, 0, 3 );
		$sellable      = $this->getSellableFromCode( $sellable_code );
		$ret->setSellable( $sellable );

		$variations_code = substr( $code, 3 );
		while ( strlen( $variations_code ) ) {
			$section_code    = substr( $variations_code, 0, 2 );
			$material_code   = substr( $variations_code, 2, 2 );
			$variations_code = substr( $variations_code, 4 );

			$section = array_filter( $sellable->getSections(), function ( $section ) use ( $section_code ) {
				return $section->getCode() == $section_code;
			} );

			$section = count( $section ) ? array_values( $section )[0] : null;

			$option = array_filter( $section->getOptions(), function ( $option ) use ( $material_code ) {
				return $option->getMaterial()->getCode() == $material_code;
			} );

			$selected_section = new Section();
			$selected_section->setId( $section->getId() );
			$selected_section->setIcon( $section->getIcon() );
			$selected_section->setName( $section->getName() );
			$selected_section->setCode( $section->getCode() );
			$selected_section->setZindex( $section->getZindex() );
			$selected_section->setOptions( array_values( $option ) );
			$ret->addSection( $selected_section );
		}

		$this->setCache( $code, $ret );

		return $ret;
	}

	private function loadCache() {
		if ( ! $this->cacheLoaded ) {
			if ( defined( 'ENABLE_SELLABLE_CACHE' ) && ENABLE_SELLABLE_CACHE ) {
				add_action( 'shutdown', array( $this, 'onDestruct' ) );
				$this->cache     = get_transient( "choose_cache" );
				$this->inventory = get_transient( "choose_inventory" );
				if ( false === $this->cache ) {
					$this->cache = array();
				}
				if ( false === $this->inventory ) {
					$this->inventory = array();
				}
				$this->cacheLoaded = true;
			}
		}
	}

	private function inCache( $key ) {
		return array_key_exists( $key, $this->cache );
	}

	private function getFromCache( $key ) {
		return $this->cache[ $key ];
	}

	private function setCache( $key, $value ) {
		$this->cache[ $key ] = $value;
	}

	private function setInventoryItem( $key, $value ) {
		$this->inventory[ $key ] = $value;
	}

	private function getInventoryItem( $key ) {

		if ( ! array_key_exists( $key, $this->inventory ) ) {
			$this->rebuildInventory( $this->getAllSellable() );
		}

		return $this->inventory[ $key ];
	}
}
