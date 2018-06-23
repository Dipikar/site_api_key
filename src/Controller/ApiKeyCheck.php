<?php
/**
 * @file
 * Contains \Drupal\site_api_key\Controller\ApiKeyCheck
 */
namespace Drupal\site_api_key\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\HeaderBag;


class ApiKeyCheck extends ControllerBase {
  public function apicheck($key,$nid) {
    $output = array(
      'status' => false,
      'data' => '',
    );
    $path = \Drupal::request()->getpathInfo();
    $arg  = explode('/',$path);
    $key = '';
    if( false == empty( $arg[2] ) ) {
      $key = $arg[2];
      $siteapikey = \Drupal::config('api_key.settings')->get('siteapikey');
      if ( $key != $siteapikey ) { 
        $output['data'] = 'Access Denied';
        return new JsonResponse($output);
      }
    } 
    $nid = $arg[3];
    $node = \Drupal\node\Entity\Node::load($nid);
    if( true == empty( $node ) ) {
      $output['data'] = 'not a node';
      $output['status'] = false;

    } else {
      $serializer = \Drupal::service('serializer');
      $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
      $output['data'] = $data;
      $output['status'] = true;

    }
    return new JsonResponse($output);
  }
}