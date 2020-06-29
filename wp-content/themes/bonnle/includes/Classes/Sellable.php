<?php
/**
 * Sellable.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Classes;


use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Class Sellable
 * @package Sitelink\Classes
 * @ExclusionPolicy("all")
 */
class Sellable
{

    /**
     * @var string
     * @Expose
     */
    private $id;

    /**
     * @var string
     * @Expose
     */
    private $name;

    /**
     * @var string
     * @Expose
     */
    private $image;

    /**
     * @var integer
     * @Expose
     */
    private $product;
    /**
     * @var string
     * @Expose
     */
    private $code;
    /**
     * @var Section[]
     * @Expose
     */
    private $sections;

    /**
     * @var float
     * @Expose
     */
    private $baseprice;

    /**
     * Sellable constructor.
     */
    public function __construct()
    {
        $this->sections = [];
    }

    /**
     * @return int
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param int $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
     * @return Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param Section[] $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getBasePrice()
    {
        return $this->baseprice;
    }

    /**
     * @param mixed $baseprice
     */
    public function setBasePrice($baseprice)
    {
        $this->baseprice = $baseprice;
    }

    /**
     * @VirtualProperty
     * @SerializedName("price_from")
     * @return float
     */
    public function getMinPrice()
    {
        $prices = array_map(function($section){
            $prices = array_map(function($option) use ($section){
                return $option->getPrice();
            },$section->getOptions());
            return min($prices);
        },$this->getSections());

        return array_reduce($prices,function($counter,$current){
            return $counter + $current;
        },$this->getBasePrice());
    }

    public function getTitleParts()
    {
        $parts = array_map(function($section){
            $ret = new \StdClass();
            $ret->name = $section->getName();
            $ret->order = $section->getZindex();
            return $ret;
        }, $this->getSections());
        usort($parts, function($a,$b){
            return $b->order - $a->order;
        });
        return array_map(function($part){
            return $part->name;
        }, $parts);
    }
}
