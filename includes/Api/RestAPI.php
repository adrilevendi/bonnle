<?php
/**
 * API.php
 * User: Daniele Callegaro <daniele.callegaro.90@gmail.com>
 * Created: 11/09/19
 */

namespace Sitelink\Api;

use Sitelink\Repository\SellableRepository;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use WP_REST_Request;

class RestAPI{
    /**
     * @var RestAPI
     */
    private static $_instance = null;
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * RestAPI constructor.
     */
    private function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    public function loadEndpoints()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'choose/v1', '/get_configurable', array(
                'methods' => 'GET',
                'callback' => array($this, 'getConfigurable'),
            ) );
        });
    }

    /**
     * @return RestAPI
     */
    public static function getInstance()
    {
        if(!RestAPI::$_instance)
            RestAPI::$_instance = new RestAPI();

        return RestAPI::$_instance;
    }


    public function getConfigurable(WP_REST_Request $request)
    {
        $id = $request->get_param("id");
        $data = $this->serializer->serialize(SellableRepository::getInstance()->getSellableFromId($id), 'json');
        return json_decode($data);
    }
}
