<?php
/**
 * Material.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Classes;


use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Class Material
 * @package Sitelink\Classes
 * @ExclusionPolicy("all")
 */
class Material
{
    /**
     * @var string
     * @Expose
     */
    private $code;
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
     * @var string
     * @Expose
     */
    private $category_name;

    /**
     * @var integer
     * @Expose
     */
    private $category_id;

    /**
     * @var string
     * @Expose
     */
    private $name;

	/**
	 * @var bool
	 * @Expose
	 */
    private $enabled;

    /**
     * Material constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $category_name
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
	 * @return bool
	 */
	public function getEnabled() {
		return $this->enabled;
	}

	/**
	 * @param bool $enabled
	 */
	public function setEnabled( $enabled ) {
		$this->enabled = $enabled;
	}




}
