<?php


if ( !defined( 'ABSPATH' ) ) die(); //keep from direct access
header("Access-Control-Allow-Origin: *");
define('CG_APP_CLASS_NAME', 'Gf_Frontend');


if (!class_exists('GFForms')) {
  return false;
}

if (!class_exists(CG_APP_CLASS_NAME)) {
  class Gf_Frontend
  {
    const APP_VERSION             = '.1';
    const APP_DEBUG               = 1;
    const APP_NAMESPACE           = 'cg_';
    const APP_LOADER              = 'gf-frontend/app-loader.php';
    const APP_NAME                = 'Gravity Forms Frontend';
    const APP_SLUG                = 'gf_frontend';
    const PLUGIN_DIR              = '/plugins';
    const APP_DIR                 = '/gf-frontend';
    const SETTINGS_SECTION_ID     = 'cg_main';
    const OPTIONS_PAGE            = 'cg_options_page';
    const OPTIONS_PREFIX          = 'cg_options';
    const APP_OPTION_CLASS_NAME   = 'Gf_Frontend_Options';
    const SETTINGS_PAGE_TITLE     = 'Gravity Forms Frontend Options';
    const SETTINGS_PAGE1          = 'cg_settings';
    const SETTINGS_PAGE_SUBTITLE1 = '';
    const APP_TEMPLATE_FOLDER     = '';
    const APP_TEMPLATE_URI        = self::PLUGIN_DIR . self::APP_DIR .'/templates/';




    var $settings, $options_page;

    function __construct()
    {

      if (is_admin()) {

        $settings_class  = CG_APP_CLASS_NAME . '_Settings';
        $options_class   = CG_APP_CLASS_NAME . '_Options';


        if (!class_exists($settings_class))
          require(WP_CONTENT_DIR . self::PLUGIN_DIR . self::APP_DIR . '/app-settings.php');
        $this->settings = new $settings_class();

        if (!class_exists($options_class))
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/app-options.php');
        $this->options_page = new $options_class();


        if (!class_exists('GFTemplateAddOn')) {
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/class/GFTemplateAddOn.class.php');
          $this->gf_add_on = new GFTemplateAddOn();
        }

        if (!class_exists('GFActions')) {
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/class/GFActions.class.php');
          $this->gf_actions = new GFActions();
        }


        if (!class_exists('GFAxax')) {
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/class/GFAjax.class.php');
          $this->gf_ajax = new GFAjax();
        }


        if (!class_exists('GFEndpoints')) {
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/class/GFEndpoints.class.php');
          $this->gf_endpoints = new GFEndpoints();
        }

        if (!class_exists('GFUtility')) {
          require(WP_CONTENT_DIR . self::PLUGIN_DIR  . self::APP_DIR . '/class/GFUtility.class.php');
          $this->gf_utility = new GFUtility();
        }

        //action for seettings
        add_filter('plugin_row_meta', array(&$this, '_app_settings_link'), 10, 2);

        // handle spinner //TODO: NOT WORKING!!!
        //add_filter( 'gform_ajax_spinner_url', 'gf_custom_spinner' );

        // custom css for admin screens
        wp_enqueue_style( self::APP_NAMESPACE.'admin',  plugin_dir_url(__FILE__) . 'templates/common/css/admin.css');

      } else {


        // core css
        $css1 = plugin_dir_url(__FILE__)  . 'templates/common/css/gf-frontend-core.css';
        wp_enqueue_style( 'gf-template-core',$css1, '', rand(0,343) );

        // animate.css library
        $css2 = plugin_dir_url(__FILE__) . 'templates/common/css/animate.css';
        wp_enqueue_style( self::APP_NAMESPACE.'animate',  $css2);

      }

      $this->app_defaults = array(
        'all' => array(
          'debug' => self::APP_DEBUG
        )
      );

      add_action('init', array($this, 'init'));
      add_action('admin_init', array($this, 'admin_init'));
      add_action('admin_menu', array($this, 'admin_menu'));
      //add_action('wp_dashboard_setup', 'dashboard_home');

      //add_action( 'plugins_loaded', 'GFEndpoints::register_rest' );

      add_action( 'plugins_loaded', array($this, 'register_routes' ));

    }



    /**
     * Register endpoints which are not being used now
     */
    public static function register_routes() {

      /*
      if(  current_user_can('administrator') ) {

        // won't work if user doesn't have access

        // custom API endpoint for gettin gall forms
        // http://localhost:3333/wp-json/gf-frontend/v1/forms
        register_rest_route( 'gf-frontend/v1', '/forms', array(
          'methods' => WP_REST_Server::READABLE,
          'callback' =>  ['GFEndpoints::get_all_forms'],
          //'callback' =>  [$this,'get_all_forms'],
        ) );

        // custom API endpoint for getting one form by ID
        register_rest_route( 'gf-frontend/v1', '/form/(?P<id>\d+)', array(
          'methods' => 'GET',
          'callback' =>  ['GFEndpoints::get_one_form'],
          //'callback' =>  [$this,'get_one_form'],
        ) );
      }

      */
    }

    /**
     *
     */
    function dashboard_home() {
      global $wp_meta_boxes;

      wp_add_dashboard_widget('custom_help_widget', 'Theme Support', 'custom_dashboard_help');
    }

    /**
     *
     */
    function custom_dashboard_help() {
      echo '<p>Welcome to Custom Blog Theme! Need help? Contact the developer <a href="mailto:yourusername@gmail.com">here</a>. For WordPress Tutorials visit: <a href="https://www.wpbeginner.com" target="_blank">WPBeginner</a></p>';
    }


    /**
     * TODO: new shortcode for returning gravity form data via ajax json
     * get template and use template css and js to completely customize
     * [gravityform id=1 title=false description=false ajax=true tabindex=49]
     */
    public static function gravityform_frontend_func( $atts ) {

      $atts = shortcode_atts( array(
        'id' => '',
        'title' => true,
        'description' => true,
        'ajax' => true,
        'tabindex' => true,
        'template' => 'default',
      ), $atts );


      //TODO: same options and attributes as gravity forms
      //title=false description=false ajax=true tabindex=49

      // get form with internal API
      $form = GFAPI::get_form($atts['id']);

      // process form attribute overrides here instead of in template
      if($atts['title'] === "false"  || is_null($atts['title'])) {
        $title = $form['title'];
        $form_title   = true;
      } else {
        $title = $atts['title'];
        $form_title   = false;
      }

      if($atts['description'] === "false"  || is_null($atts['description'])) {
        $description = $form['description'];
        $form_desc   = true;
      } else {
        $description = $atts['description'];
        $form_desc   = false;
      }

      //echo "description: $description";
      // template override
      if($atts['template'] === "false"  || is_null($atts['template'])) {
        $template = $form['gf_frontend']['gf_template'];
      } else {
        $template = $atts['template'];
      }

      // ajax override
      if($atts['ajax'] === "false"  || is_null($atts['ajax'])) {
        $ajax = false;
      } else {
        $ajax = $atts['ajax'];
      }

      // tabindex override
      if($atts['tabindex'] === "false"  || is_null($atts['tabindex'])) {
        $tabindex = false;
      } else {
        $tabindex = $atts['tabindex'];
      }

      if(!$form['gf_frontend']['gf_template']) {
        $form['gf_frontend']['gf_template'] = 'default';
      }

      $template_folder = WP_CONTENT_DIR . self::PLUGIN_DIR . self::APP_DIR . '/templates/frontend/'. $form['gf_frontend']['gf_template'].'/';
      $template_uri    = plugins_url() . self::APP_DIR. '/templates/frontend/'. $form['gf_frontend']['gf_template'];


      // always load core css

      // TODO: check if files exist first, form-display and css/js files
      // hard coding template names allows for convention over configurartion
      // if you want to inject more custom js/css you can use the gravity forms feature
      $source_css = $template_folder.'/css/main.css';
      $source_js  = $template_folder.'/js/main.js';

      if(file_exists($source_css)) {
        wp_enqueue_style( 'gf-template-'.$template, $template_uri.'/css/main.css', '', rand(0,343) );
      }

      if(file_exists($source_js)) {
        wp_enqueue_script( 'gf-template-'.$template, $template_uri.'/js/main.js', '', rand(0,2343), true );
      }


      // hardcode template names for now
      // we can expand to have templates for entries as well
      include($template_folder .'form-display.php');

    }

    /**
     * @param $data
     * @return null
     */
    function home_masonary($data) {


      // TODO: need to get all images, all sizes for SRCSET
      //TODO: temporary for home masonary perosnal page

      $args = [
        'post_type'     => 'masonary',
        'numberposts'   => 10,
        //'meta_value_num'=> 'NUMERIC',
        'orderby'        => 'rand',
      ];

      $posts = get_posts($args);


      //TODO: get images

      $i = 0;
      foreach($posts as $post) {

        //$posts[$i]['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );

        // get featured image
        $posts[$i]->image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );


        // get category
        $posts[$i]->cat  = get_the_category( $post->ID );

        // find out if this is a video
        $posts[$i]->meta = get_post_meta( $post->ID);


        //get_post_meta( $post_id, $key = '', $single = false )

        $i++;

      }

      if ( empty( $posts ) ) {
        return null;
      }

      //return $posts[0]->post_title;




      wp_send_json( $posts ) ;


    }

    /**
     * Settings link
     * @param $links
     * @param $file
     * @return mixed
     */
    public function _app_settings_link($links, $file)
    {
      if ($file != self::APP_LOADER) return $links;
      $settings_link = '<a href="options-general.php?page=' . self::APP_SLUG . '">Settings</a>';
      //$view_link = '<a href="/cpa-verify-search" target="new">View Page</a>';
      //array_unshift($links, $settings_link, $view_link);
      return $links;
    }


    function network_propagate($pfunction, $networkwide)
    {
      global $wpdb;

      if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function
        // for each blog id
        if ($networkwide) {
          $old_blog = $wpdb->blogid;
          $blogids  = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
          foreach ($blogids as $blog_id) {
            switch_to_blog($blog_id);
            call_user_func($pfunction, $networkwide);
          }
          switch_to_blog($old_blog);
          return;
        }
      }
      call_user_func($pfunction, $networkwide);
    }

    function activate($networkwide)
    {
      $this->network_propagate(array($this, '_activate'), $networkwide);
    }

    function deactivate($networkwide)
    {
      $this->network_propagate(array($this, '_deactivate'), $networkwide);
    }


    function _activate()
    {

    }

    function _deactivate()
    {

    }

    function init()
    {

    }

    function admin_init()
    {

    }

    function admin_menu()
    {

      $icon =<<<EOS
data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSItMTUgNzcgNTgxIDY0MCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAtMTUgNzcgNTgxIDY0MCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGcgaWQ9IkxheWVyXzIiPjxwYXRoIGZpbGw9IiM4Mjg3OGMiIGQ9Ik00ODkuNSwyMjdMNDg5LjUsMjI3TDMxNS45LDEyNi44Yy0yMi4xLTEyLjgtNTguNC0xMi44LTgwLjUsMEw2MS44LDIyN2MtMjIuMSwxMi44LTQwLjMsNDQuMi00MC4zLDY5Ljd2MjAwLjVjMCwyNS42LDE4LjEsNTYuOSw0MC4zLDY5LjdsMTczLjYsMTAwLjJjMjIuMSwxMi44LDU4LjQsMTIuOCw4MC41LDBMNDg5LjUsNTY3YzIyLjItMTIuOCw0MC4zLTQ0LjIsNDAuMy02OS43VjI5Ni44QzUyOS44LDI3MS4yLDUxMS43LDIzOS44LDQ4OS41LDIyN3ogTTQwMSwzMDAuNHY1OS4zSDI0MXYtNTkuM0g0MDF6IE0xNjMuMyw0OTAuOWMtMTYuNCwwLTI5LjYtMTMuMy0yOS42LTI5LjZjMC0xNi40LDEzLjMtMjkuNiwyOS42LTI5LjZzMjkuNiwxMy4zLDI5LjYsMjkuNkMxOTIuOSw0NzcuNiwxNzkuNiw0OTAuOSwxNjMuMyw0OTAuOXogTTE2My4zLDM1OS43Yy0xNi40LDAtMjkuNi0xMy4zLTI5LjYtMjkuNnMxMy4zLTI5LjYsMjkuNi0yOS42czI5LjYsMTMuMywyOS42LDI5LjZTMTc5LjYsMzU5LjcsMTYzLjMsMzU5Ljd6IE0yNDEsNDkwLjl2LTU5LjNoMTYwdjU5LjNIMjQxeiIvPjwvZz48L3N2Zz4=
EOS;

      // admin menu
      add_menu_page(self::APP_NAME, self::APP_NAME, 'manage_options', self::APP_SLUG.'-settings', self::APP_OPTION_CLASS_NAME.'::admin_home',  $icon);
      add_submenu_page( self::APP_SLUG, 'Options', 'Themes', 'manage_options',  self::APP_SLUG.'-help', self::APP_OPTION_CLASS_NAME.'::submenu1' );
      //add_submenu_page( self::APP_SLUG, 'Themes', 'Settings', 'manage_options', self::APP_SLUG.'-submenu2', self::APP_OPTION_CLASS_NAME.'::submenu2' );

    }


    /**
     *
     */
    function settings_init() {
      error_log(__FILE__.':'.__LINE__.' - frontend  ');
      //register_setting( 'wp_cap_map', 'gp_options', array(&$this, 'sanitize_settings') );
      //register_setting( 'wp_cap_map', 'gp_options', array(&$this, 'sanitize_settings') );


      register_setting('cg_chart_save_callback', self::OPTIONS_PREFIX);
    }

    /**
     * @param $input
     *
     * @return mixed
     */
    function sanitize_settings($input) {
      return $input;
    }

    /**
     * @param $links
     * @param $file
     *
     * @return mixed
     */
    function settings_link( $links, $file ) {
      static $this_plugin;
      if( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__);
      if ( $file == $this_plugin ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=gp_options' ) . '">' . __('Settings', 'gp_options') . '</a>';
        array_unshift( $links, $settings_link ); // before other links
      }
      return $links;
    }

  }
}

global $Gf_Frontend;
if (class_exists(CG_APP_CLASS_NAME) && !$Gf_Frontend) {
  $Gf_Frontend = new Gf_Frontend();

  if ( is_admin() ) {


    // TODO: need new shortcodes for overriding temlates completely
    add_action( 'wp_ajax_cg_svg_action', 'Gf_Frontend::cg_svg_action_callback' );  //ajax for new svg
    add_action( 'wp_ajax_nopriv_cg_svg_action', 'Gf_Frontend::cg_svg_action_callback' );   //ajax for new svg
    add_action( 'wp_ajax_cg_file_save_action', 'Gf_Frontend::cg_file_save_action_callback' );  //ajax for saving files
    add_action( 'wp_ajax_nopriv_cg_file_save_action', 'Gf_Frontend::cg_file_save_action_callback' );   //ajax for saving files



  } else {


    // new shortcode for gravity forms
    //  [gravityform id=1 title=false description=false ajax=true tabindex=49]

    add_shortcode( 'gravityform_frontend', 'Gf_Frontend::gravityform_frontend_func' );

  }
}
