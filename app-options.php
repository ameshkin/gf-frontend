<?php
if ( !defined( 'ABSPATH' ) ) die(); //keep from direct access
if (!function_exists('is_admin')) {
  header('Status: 403 Forbidden');
  header('HTTP/1.1 403 Forbidden');
  exit();
}

if (!class_exists("Gf_Frontend_Options")) {

  class Gf_Frontend_Options extends Gf_Frontend
  {

    var $page = '';
    var $message = 0;

    function __construct()
    {




    }

    /**
     * Build options page
     */
    function option_function()
    {

      $messages[1] = __('Action Taken.', parent::APP_SLUG);

      if (isset($_GET['message']) && (int)$_GET['message']) {
        $message = $messages[$_GET['message']];
        $_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
      }

      $title = __(parent::SETTINGS_PAGE_TITLE, parent::APP_SLUG);
      ?>
        <div class="wrap">
            <h2><?php echo esc_html($title); ?></h2>

          <?php
          if (!empty($message)) :
            ?>
              <div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
          <?php
          endif;
          ?>

            <form method="post" action="options.php">
              <?php
              settings_fields(parent::OPTIONS_PAGE);
              do_settings_sections(parent::SETTINGS_PAGE1);
              ?>
                <p>
                    <input type="submit" class="button button-primary" name="save_options" value="<?php esc_attr_e('Save Options'); ?>"/>
                </p>
            </form>
            <p class="note"><?php print parent::APP_NAME; ?> by Amir Meshkin
                version <?php print parent::APP_VERSION; ?></p>
            <span> debug: <?php print parent::APP_DEBUG; ?></span>
        </div>
      <?php
    }


    /**
     * Main Plugin Settings page
     */
    function admin_home()
    {
      $data['title'] = 'Gravity Forms Frontend Settings';
      $active_tab    = 'home';

      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/tabs.php');
      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/settings.php');
    }



    /**
     * Themes Options
     * Return items in themes directory
     */
    function submenu1()
    {

      $data['title'] = "Gravity Forms Frontend Help";
      $active_tab    = 'submenu1';

      // get number of forms and pass along
      $forms =  GFAPI::get_forms();


      $num_forms = count($forms);




      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/tabs.php');
      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/help.php');

    }


    /**
     * Not Using
     */
    function submenu2() {

      $active_tab    = 'submenu2';
      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/tabs.php');
      include(WP_CONTENT_DIR . parent::PLUGIN_DIR . parent::APP_DIR . '/templates/admin/submenu2.php');

    }


  }
}
