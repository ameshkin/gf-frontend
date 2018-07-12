<?php


define( 'GF_FRONTEND_TEMPLATES_VERSION', '2.1' );
add_action( 'gform_loaded', array( 'GFAddOnBootstrap', 'load' ), 5 );


class GFAddOnBootstrap {
  public static function load() {
    if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
      return;
    }
    require_once( 'gfAddOn.class.php' );
    GFAddOn::register( 'GFSimpleAddOn' );
  }
}
function gf_simple_addon() {
  return GFAddOn::get_instance();
}
