<?php

namespace Sitelink\Updater;


class Updater{

    /**
     * @var Updater
     */
    private static $_instance = null;
    /**
     * Utils constructor.
     */
    private function __construct()
    {

    }

    /**
     * @return Updater
     */
    public static function getInstance()
    {
        if(!Updater::$_instance)
	        Updater::$_instance = new Updater();

        return Updater::$_instance;
    }

	private function getSectionIdFromCode( $code ) {
    	$code = substr($code,0,2);
		$founds = get_posts(array(
			'post_type'      => 'config_sections', //or a post type of your choosing
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => 'code',
					'value' => $code,
					'compare'   => '=',
				)
			)
		));
		if(count($founds))
		{
			$found = $founds[0];
			return $found->ID;
		}
		return null;
	}


	private function getMaterialIdFromCode( $code ) {
		$code = substr($code,2,2);
		$founds = get_posts(array(
			'post_type'      => 'material', //or a post type of your choosing
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'   => 'code',
					'value' => $code,
					'compare'   => '=',
				)
			)
		));
		if(count($founds))
		{
			$found = $founds[0];
			return $found->ID;
		}
		return null;
	}

	public function updateAvailability($code, $difference)
	{
		$sectionID = $this->getSectionIdFromCode($code);
		$materialId = $this->getMaterialIdFromCode($code);
		$rows = get_field("options", $sectionID);
		$rows = array_filter($rows,function ($row) use ($materialId){
			return $row["material"] == $materialId;
		});
		foreach ($rows as $index => $row)
		{
			$row["qty"] = max($row["qty"] - $difference, 0);
			update_row('options', $index + 1, $row, $sectionID);
			do_action("choose_section_qty_updates", $code, $difference);
		}
	}

}
