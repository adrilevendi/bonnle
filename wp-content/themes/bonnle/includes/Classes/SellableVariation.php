<?php
/**
 * SellbaleVariation.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Classes;

/**
 * Class SellableVariation
 * @package Sitelink\Classes
 */
class SellableVariation
{
    /**
     * @var Sellable
     */
    private $sellable;

    /**
     * @var Section[]
     */
    private $selectedSections;

    /**
     * SellableVariation constructor.
     */
    public function __construct()
    {
        $this->selectedSections = array();
    }

    /**
     * @return Sellable
     */
    public function getSellable()
    {
        return $this->sellable;
    }

    /**
     * @param Sellable $sellable
     */
    public function setSellable($sellable)
    {
        $this->sellable = $sellable;
    }

    /**
     * @return Section[]
     */
    public function getSelectedSections()
    {
        return $this->selectedSections;
    }

    public function addSection(Section $section)
    {
        $this->selectedSections[] = $section;

        // Mary at the First Place
        usort($this->selectedSections, function($a,$b){
			if(strtolower($a->getName()) === "mary")
				return -1;
	        if(strtolower($b->getName()) === "mary")
		        return 1;
	        return 0;
        });

        return $this;
    }

    public function getImages($size = "full")
    {
        usort($this->selectedSections, function($a,$b){
            return $a->getZindex() -  $b->getZindex();
        });
        return array_map(function($section) use ($size){
            if($size == "medium")
                return $section->getOptions()[0]->getMediumImage();
            if($size == "small")
                return $section->getOptions()[0]->getSmallImage();
            return $section->getOptions()[0]->getImage();
        },$this->selectedSections);
    }

    public function getPathImages()
    {
        usort($this->selectedSections, function($a,$b){
            return $a->getZindex() -  $b->getZindex();
        });
        return array_map(function($section){
            return $section->getOptions()[0]->getImagePath();
        },$this->selectedSections);
    }

    public function getHtmlImages($size, $length)
    {
        $html = "<div style=\"position: relative;width: ${length}px;height: ${length}px\">";
        foreach ($this->getImages($size) as $image)
        {
            $html.="<div style=\"position: absolute;top:0;left: 0;width: ${length}px;height: ${length}px;background-size: cover;background-image: url('$image')\"></div>";
        }
        $html.="</div>";
        return $html;
    }

    public function getPrice()
    {
        return array_reduce($this->getSelectedSections(),function($counter,$current){
            return $counter + $current->getOptions()[0]->getPrice();
        },$this->sellable->getBasePrice());
    }

    public function getCode()
    {
        $options_code = array_map(function($section){
            return $section->getCode() . $section->getOptions()[0]->getMaterial()->getCode();
        },$this->selectedSections);
        return $this->getSellable()->getCode() . join("",$options_code);
    }
}
