<?php

namespace Sitelink\Utils;

use Sitelink\Classes\SellableVariation;

class Utils{

    /**
     * @var Utils
     */
    private static $_instance = null;
    private $cache;
    private $cache_url;
    /**
     * Utils constructor.
     */
    private function __construct()
    {
        $this->cache = get_template_directory() . "/cache_images";
        $this->cache_url = get_template_directory_uri() . "/cache_images";

        if(!file_exists($this->cache))
            mkdir($this->cache);
    }

    /**
     * @return Utils
     */
    public static function getInstance()
    {
        if(!Utils::$_instance)
            Utils::$_instance = new Utils();

        return Utils::$_instance;
    }

    public function getQtyFromCodes($codes)
    {
	    $codes = array_map(function($code){
		    $ret = array();
		    $variations_code = substr( $code, 3 );
		    while ( strlen( $variations_code ) ) {
			    $section_code    = substr( $variations_code, 0, 2 );
			    $material_code   = substr( $variations_code, 2, 2 );
			    $variations_code = substr( $variations_code, 4 );
			    $ret[] = strtoupper($section_code . $material_code);
		    }
		    return $ret;
	    }, $codes);
	    $qtys = array_reduce($codes, function($ret,$data){
		    foreach ($data as $item)
		    {
			    if(array_key_exists($item, $ret))
				    $ret[$item] += 1;
			    else
				    $ret[$item] = 1;
		    }
		    return $ret;
	    },array());

	    return $qtys;
    }

    public function getSellableImage(SellableVariation $variation, $width = 2048)
    {
        $image_path = $this->cache . "/" . $variation->getCode() . "-" . $width . ".png";
        $image_url =  $this->cache_url . "/" . $variation->getCode() . "-" . $width . ".png";

        if(file_exists($image_path))
            return $image_url;

        $images = $variation->getPathImages();
        $merged_image = imagecreatetruecolor($width, $width);
        $transparent = imagecolorallocatealpha($merged_image, 0,0,0,127);
        imagefill($merged_image, 0, 0, $transparent);
        imagesavealpha($merged_image, true);

        foreach ($images as $image)
        {
            $source = imagecreatefrompng($image);
            list($source_width, $source_height) = getimagesize($image);
            imagesavealpha($source, true);
            imagealphablending($merged_image, true);
            imagecopyresampled($merged_image, $source, 0, 0, 0, 0, $width, $width, $source_width, $source_height);
        }

        imagepng($merged_image, $image_path);
        return $image_url;
    }
}
