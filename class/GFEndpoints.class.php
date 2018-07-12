<?php

/**
 * TODO: All custom endpoints go here
 */


header("Access-Control-Allow-Origin: *");

GFForms::include_addon_framework();



class GFEndpoints
{




  public function __construct()
  {



  /*
    register_rest_route( 'home/v1', '/masonary', array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => array( $this, 'get_home_page' ),
    ) );

*/

    /*
    add_action( 'rest_api_init', function () {
      register_rest_route( 'home/masonary', '/author/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'my_awesome_func',
      ) );
    } );

    */

  }







}
