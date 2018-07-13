<?php

/**
 * TODO: All custom endpoints go here
 */


//header("Access-Control-Allow-Origin: *");

GFForms::include_addon_framework();



class GFEndpoints
{

  /**
   * @param $data
   * @return mixed
   */
  public static function get_one_form( $data ) {

    return GFAPI::get_form($data['id']);

  }


  /**
   *
   */
  public static function get_all_forms() {

    return GFAPI::get_forms();

  }

}
