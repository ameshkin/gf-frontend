<?php

/**
 * TODO: All custom endpoints go here
 */


//header("Access-Control-Allow-Origin: *");

GFForms::include_addon_framework();



class GFEndpoints
{


  /**
   * Get one form called from ajax function
   * @param $data
   */
  public static function get_one_form( $data ) {

    $form = GFAPI::get_form($data['id']);

  }


  /**
   *
   */
  public static function get_all_forms() {

    wp_send_json(GFAPI::get_forms() );

  }






}
