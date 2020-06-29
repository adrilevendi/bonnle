<?php
/**
 * Option.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Classes;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Class Option
 * @package Sitelink\Classes
 * @ExclusionPolicy("all")
 */
class Option
{
    /**
     * @var Material
     * @Expose
     */
    private $material;

	/**
	 * @var integer
	 * @Expose
	 */
	private $qty;

    /**
     * @var string
     */
    private $image_path;

    /**
     * @var string
     * @Expose
     */
    private $image;

    /**
     * @var string
     * @Expose
     */
    private $small_image;

    /**
     * @var string
     * @Expose
     */
    private $medium_image;

    /**
     * @var float
     * @Expose
     */
    private $price;

    /**
     * @return Material
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param Material $material
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSmallImage()
    {
        return $this->small_image;
    }

    /**
     * @param string $small_image
     */
    public function setSmallImage($small_image)
    {
        $this->small_image = $small_image;
    }

    /**
     * @return string
     */
    public function getMediumImage()
    {
        return $this->medium_image;
    }

    /**
     * @param string $medium_image
     */
    public function setMediumImage($medium_image)
    {
        $this->medium_image = $medium_image;
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        return $this->image_path;
    }

    /**
     * @param string $image_path
     */
    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }

	/**
	 * @return int
	 */
	public function getQty() {
		return $this->qty;
	}

	/**
	 * @param int $qty
	 */
	public function setQty( $qty ) {
		$this->qty = $qty;
	}

	/**
	 * @param int $qtyInCart
	 *
	 * @return bool
	 */
	public function isSpent($qtyInCart=0)
	{
		return $this->qty - $qtyInCart < 1;
	}

}
