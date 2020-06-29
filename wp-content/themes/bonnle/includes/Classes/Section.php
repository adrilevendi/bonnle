<?php
/**
 * Section.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Classes;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Class Section
 * @package Sitelink\Classes
 * @ExclusionPolicy("all")
 */
class Section
{
	/**
	 * @var int
	 * @Expose
	 */
	private $id;

    /**
     * @var string
     * @Expose
     */
    private $icon;
    /**
     * @var string
     * @Expose
     */
    private $name;
    /**
     * @var string
     * @Expose
     */
    private $code;
    /**
     * @var integer
     * @Expose
     */
    private $zindex;
    /**
     * @var Option[]
     * @Expose
     */
    private $options;

    /**
     * Section constructor.
     */
    public function __construct()
    {
        $this->options = [];
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
     * @return int
     */
    public function getZindex()
    {
        return $this->zindex;
    }

    /**
     * @param int $zindex
     */
    public function setZindex($zindex)
    {
        $this->zindex = $zindex;
    }

    /**
     * @return Option[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Option[] $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
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
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}




}
