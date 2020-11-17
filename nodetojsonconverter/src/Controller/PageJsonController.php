<?php
/**
 * @file
 * Return json format of the basic page nodes.
 * Reference: drupal.org,https://drupal.stackexchange.com/questions/191419/drupal-8-node-serialization-to-json?answertab=active#tab-top
 * Enable serialization module.
 */


namespace Drupal\nodetojsonconverter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for node page json module route.
 */
class PageJsonController extends ControllerBase {

   /**
   * Render node as json
   *
   * @param $api_key 
   * @param $nid
   * The feed source URL.
   * @return json response of the node
   */

  /**
   * {@inheritdoc}
   */
  	public function __construct() {
    	$config_factory = \Drupal::configFactory();
    	$this->config = $config_factory->getEditable('system.site')->get('siteapikey');
    	$entitystore = \Drupal::entityTypeManager();
    	$this->nodestorage = $entitystore->getStorage('node');
    	$this->serializer = \Drupal::service('serializer');
  	}
   
	public function getPageJson($api_key, $nid) {
    //Match the key set in site config
		if($this->config == $api_key) {
			$node = $this->nodestorage->load($nid);		
		    if(isset($node)) {
		      	$type_name = $node->type->entity->label();
                if($type_name == 'Basic page') {
					$data = $this->serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
					return new JsonResponse(json_decode($data));
				}
				else {
					return new JsonResponse('Nid is incorrect');
			}
           }
			else {
				 return new JsonResponse('Node does not exist');
			}

		}
		else {
			 return new JsonResponse('Api key is incorrect');
		}
	}
}