<?php

GFForms::include_addon_framework();

class GFTemplateAddOn  extends GFAddOn {

  /**
   * TODO: combine settings
   * @var string
   */
  protected $_version = '2.1';
  protected $_min_gravityforms_version = '1.9';
  protected $_slug = 'gf_frontend';
  protected $_path = 'gf-frontend/app-loader.php';
  protected $_full_path = __FILE__;
  protected $_short_title = 'Frontend Templates';

  private static $_instance = null;

  /**
   * Get an instance of this class.
   *
   * @return GFSimpleAddOn
   */
  public static function get_instance() {
    if ( self::$_instance == null ) {
      self::$_instance = new GFTemplateAddOn();
    }


    error_log("ASDFasdfadfasd");
    return self::$_instance;
  }

  /**
   * Handles hooks and loading of language files.
   */
  public function init() {
    parent::init();
    add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 10, 2 );
    add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );
  }


  // # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

  /**
   * Return the scripts which should be enqueued.
   *
   * @return array
   */
  public function scripts() {

    //TODO: scripts need to load from wordpress/wp-content/plugins/gf-frontend/templates

    // get template here, and automatically import main.js if it exists


    $scripts = array(
      array(
        'handle'  => 'my_script_js',
        'src'     => $this->get_base_url() . '/js/my_script.js',
        'version' => $this->_version,
        'deps'    => array( 'jquery' ),
        'strings' => array(
          'first'  => esc_html__( 'First Choice', 'gf-frontend' ),
          'second' => esc_html__( 'Second Choice', 'gf-frontend' ),
          'third'  => esc_html__( 'Third Choice', 'gf-frontend' )
        ),
        'enqueue' => array(
          array(
            'admin_page' => array( 'form_settings' ),
            'tab'        => 'gf-frontend'
          )
        )
      ),

    );

    return array_merge( parent::scripts(), $scripts );
  }

  /**
   * Return the stylesheets which should be enqueued.
   *
   * @return array
   */
  public function styles() {

    //TODO: styles need to load from wordpress/wp-content/plugins/gf-frontend/templates


    $styles = array(
      array(
        'handle'  => 'my_styles_css',
        'src'     => $this->get_base_url() . '/css/my_styles.css',
        'version' => $this->_version,
        'enqueue' => array(
          array( 'field_types' => array( 'poll' ) )
        )
      )
    );

    return array_merge( parent::styles(), $styles );
  }


  // # FRONTEND FUNCTIONS --------------------------------------------------------------------------------------------

  /**
   * Add the text in the plugin settings to the bottom of the form if enabled for this form.
   *
   * @param string $button The string containing the input tag to be filtered.
   * @param array $form The form currently being displayed.
   *
   * @return string
   */
  function form_submit_button( $button, $form ) {
    $settings = $this->get_form_settings( $form );
    if ( isset( $settings['enabled'] ) && true == $settings['enabled'] ) {
      $text   = $this->get_plugin_setting( 'mytextbox' );
      $button = "<div>{$text}</div>" . $button;
    }

    return $button;
  }


  // # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------

  /**
   * Creates a custom page for this add-on.
   */
  public function plugin_page() {



    $data['title'] = 'Gravity Forms Frontend';
    $data['active_tab'] = 'home';
    $active_tab    = 'home';



    include(WP_CONTENT_DIR . Gf_Frontend::PLUGIN_DIR . Gf_Frontend::APP_DIR . '/templates/admin/tabs.php');
    include(WP_CONTENT_DIR . Gf_Frontend::PLUGIN_DIR . Gf_Frontend::APP_DIR . '/templates/admin/home.php');


    //TODO: show templates here





  }

  /**
   * TODO: finish main plugin settings page
   * http://localhost:3333/wp-admin/admin.php?page=gf_settings&subview=gf-frontend
   * Configures the settings which should be rendered on the add-on settings tab.
   *
   * @return array
   */
  public function plugin_settings_fields() {
    return array(
      array(
        'title'  => esc_html__( 'Gravity Forms Frontend Settings', 'gf-frontend' ),
        'fields' => array(
          array(
            'name'              => 'mytextbox',
            'tooltip'           => esc_html__( '', 'gf-frontend' ),
            'label'             => esc_html__( 'General Plugin Options', 'gf-frontend' ),
            'type'              => 'text',
            'class'             => 'small',
            'feedback_callback' => array( $this, 'is_valid_setting' ),
          )
        )
      )
    );
  }

  /**
   * TODO: settings for the form itself
   * http://localhost:3333/wp-admin/admin.php?page=gf_edit_forms&view=settings&subview=gf-frontend&id=8
   * Configures the settings which should be rendered on the Form Settings > Simple Add-On tab.
   *
   * @return array
   */
  public function form_settings_fields( $form ) {


    //TODO: need function for automatically getting templates in plugin folder



    // TODO: perhaps hard coded folder paths can be put into the main plugin settings
    $folders    = GFUtility::get_folder_names(ABSPATH.'/wp-content/plugins/gf-frontend/templates/frontend/');
    $animations = GFUtility::get_animation_choices(ABSPATH.'/wp-content/plugins/gf-frontend/templates/frontend/');
    $bool       = GFUtility::get_bool(ABSPATH.'/wp-content/plugins/gf-frontend/templates/frontend/');
    $spinners   = GFUtility::get_folder_names(ABSPATH.'/wp-content/plugins/gf-frontend/templates/common/spinners');

    //[ 'label' => $entry, 'value' => $entry ]

    $none_arr  = [ 'label' => 'None', 'value' => 'none' ];

    //add none
    $folders[] = $none_arr;
    $folders = array_reverse($folders,1);

    $spinners[] = $none_arr;
    $spinners = array_reverse($spinners,1);


    return array(
      array(
        'title'  => esc_html__( 'Frontend Template Settings', 'gf-frontend' ),
        'fields' => array(

          array(
            'label'   => esc_html__( 'Select Template', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_template',
            'tooltip' => 'Place Templates in /wp-content/plugins/gf-frontend/templates',
            'description' => esc_html__( 'Select a template from template directory', 'gf-frontend' ),
            'choices' => $folders
          ),


          array(
            'label'   => esc_html__( 'Wrapper ID', 'gf-frontend' ),
            'type'    => 'text',
            'name'    => 'gf_frontend_id',
            'description' => esc_html__( 'An ID to wrap the entire form in.', 'gf-frontend' ),
          ),

          array(
            'label'   => esc_html__( 'Wrapper Class', 'gf-frontend' ),
            'type'    => 'text',
            'name'    => 'gf_frontend_class',
            'description' => esc_html__( 'A CLASS to wrap the entire form in.', 'gf-frontend' ),

          ),

          // use ajax on submission or not
          array(
            'label'   => esc_html__( 'AJAX Form', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_enable_ajax',
            'description' => esc_html__( 'Should we enable AJAX for this form?', 'gf-frontend' ),
            'choices' => $bool
          ),


          // previous button transition
          array(
            'label'   => esc_html__( 'Back Transition', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_transition_previous',
            'description' => esc_html__( 'Choose a transition when someone clicks on the BACK button', 'gf-frontend' ),
            'choices' => $animations
          ),


          array(
            'label'   => esc_html__( 'Next Transition', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_transition_next',
            'description' => esc_html__( 'Choose a transition when someone clicks on the NEXT button', 'gf-frontend' ),
            'choices' => $animations
          ),


          array(
            'label'   => esc_html__( 'Submit Transition', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_transition_submit',
            'description' => esc_html__( 'Choose a transition for the submit button', 'gf-frontend' ),
            'choices' => $animations
          ),


          array(
            'label'   => esc_html__( 'Progress Bar', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_progress_bar',
            'description' => esc_html__( 'Should we show the progress bar?', 'gf-frontend' ),
            'choices' => $bool
          ),


          // PHASE II:  choose page template
          /*
          array(
            'label'   => esc_html__( 'Page Template', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_preloader',
            'tooltip' => esc_html__( 'Choose a template', 'gf-frontend' ),
            'choices' => $animations
          ),
          */


          // TODO:  on drop down change, display the spinner chosen
          array(
            'label'   => esc_html__( 'AJAX Preloader', 'gf-frontend' ),
            'type'    => 'select',
            'name'    => 'gf_preloader',
            'description' => esc_html__( 'Choose a custom ajax preloader for this form.', 'gf-frontend' ),
            'choices' => $spinners
          ),

          // TODO:  on drop down change, display the spinner chosen
          array(
            'label'   => esc_html__( 'Preloader Position', 'gf-frontend' ),
            'type'    => 'text',
            'name'    => 'gf_preloader_position',
            'tooltip' => 'Place spinners in /wp-content/plugins/gf-frontend/templates/common/spinners',
            'description' => esc_html__( 'Enter an element: .class OR #id', 'gf-frontend' ),s
          ),
        ),
      ),
    );
  }



  /**
   * Define the markup for the my_custom_field_type type field.
   *
   * @param array $field The field properties.
   * @param bool|true $echo Should the setting markup be echoed.
   */
  public function settings_my_custom_field_type( $field, $echo = true ) {
    echo '<div>' . esc_html__( 'My custom field contains a few settings:', 'gf-frontend' ) . '</div>';

    // get the text field settings from the main field and then render the text field
    $text_field = $field['args']['text'];
    $this->settings_text( $text_field );

    // get the checkbox field settings from the main field and then render the checkbox field
    $checkbox_field = $field['args']['checkbox'];
    $this->settings_checkbox( $checkbox_field );
  }


  // # SIMPLE CONDITION EXAMPLE --------------------------------------------------------------------------------------

  /**
   * Define the markup for the custom_logic_type type field.
   *
   * @param array $field The field properties.
   * @param bool|true $echo Should the setting markup be echoed.
   */
  public function settings_custom_logic_type( $field, $echo = true ) {

    // Get the setting name.
    $name = $field['name'];

    // Define the properties for the checkbox to be used to enable/disable access to the simple condition settings.
    $checkbox_field = array(
      'name'    => $name,
      'type'    => 'checkbox',
      'choices' => array(
        array(
          'label' => esc_html__( 'Enabled', 'gf-frontend' ),
          'name'  => $name . '_enabled',
        ),
      ),
      'onclick' => "if(this.checked){jQuery('#{$name}_condition_container').show();} else{jQuery('#{$name}_condition_container').hide();}",
    );

    // Determine if the checkbox is checked, if not the simple condition settings should be hidden.
    $is_enabled      = $this->get_setting( $name . '_enabled' ) == '1';
    $container_style = ! $is_enabled ? "style='display:none;'" : '';

    // Put together the field markup.
    $str = sprintf( "%s<div id='%s_condition_container' %s>%s</div>",
      $this->settings_checkbox( $checkbox_field, false ),
      $name,
      $container_style,
      $this->simple_condition( $name )
    );

    echo $str;
  }

  /**
   * Build an array of choices containing fields which are compatible with conditional logic.
   *
   * @return array
   */
  public function get_conditional_logic_fields() {
    $form   = $this->get_current_form();
    $fields = array();
    foreach ( $form['fields'] as $field ) {
      if ( $field->is_conditional_logic_supported() ) {
        $inputs = $field->get_entry_inputs();

        if ( $inputs ) {
          $choices = array();

          foreach ( $inputs as $input ) {
            if ( rgar( $input, 'isHidden' ) ) {
              continue;
            }
            $choices[] = array(
              'value' => $input['id'],
              'label' => GFCommon::get_label( $field, $input['id'], true )
            );
          }

          if ( ! empty( $choices ) ) {
            $fields[] = array( 'choices' => $choices, 'label' => GFCommon::get_label( $field ) );
          }

        } else {
          $fields[] = array( 'value' => $field->id, 'label' => GFCommon::get_label( $field ) );
        }

      }
    }

    return $fields;
  }

  /**
   * Evaluate the conditional logic.
   *
   * @param array $form The form currently being processed.
   * @param array $entry The entry currently being processed.
   *
   * @return bool
   */
  public function is_custom_logic_met( $form, $entry ) {
    if ( $this->is_gravityforms_supported( '2.0.7.4' ) ) {
      // Use the helper added in Gravity Forms 2.0.7.4.

      return $this->is_simple_condition_met( 'custom_logic', $form, $entry );
    }

    // Older version of Gravity Forms, use our own method of validating the simple condition.
    $settings = $this->get_form_settings( $form );

    $name       = 'custom_logic';
    $is_enabled = rgar( $settings, $name . '_enabled' );

    if ( ! $is_enabled ) {
      // The setting is not enabled so we handle it as if the rules are met.

      return true;
    }

    // Build the logic array to be used by Gravity Forms when evaluating the rules.
    $logic = array(
      'logicType' => 'all',
      'rules'     => array(
        array(
          'fieldId'  => rgar( $settings, $name . '_field_id' ),
          'operator' => rgar( $settings, $name . '_operator' ),
          'value'    => rgar( $settings, $name . '_value' ),
        ),
      )
    );

    return GFCommon::evaluate_conditional_logic( $logic, $form, $entry );
  }

  /**
   * Performing a custom action at the end of the form submission process.
   *
   * @param array $entry The entry currently being processed.
   * @param array $form The form currently being processed.
   */
  public function after_submission( $entry, $form ) {

    // Evaluate the rules configured for the custom_logic setting.
    $result = $this->is_custom_logic_met( $form, $entry );

    if ( $result ) {
      // Do something awesome because the rules were met.
    }
  }


  // # HELPERS -------------------------------------------------------------------------------------------------------

  /**
   * The feedback callback for the 'mytextbox' setting on the plugin settings page and the 'mytext' setting on the form settings page.
   *
   * @param string $value The setting value.
   *
   * @return bool
   */
  public function is_valid_setting( $value ) {
    return strlen( $value ) < 10;
  }


  /**
   * TODO: duplicate?
   * @param $settings
   * @param $form
   * @return mixed
   */
  function my_custom_form_setting( $settings, $form ) {
    $settings['Form Basics']['my_custom_setting'] = '
    <tr>
        <th><label for="my_custom_setting">My Custom Label  GFTEmplateAddOn</label></th>
        <td><input value="' . rgar( $form, 'my_custom_setting' ) . '" name="my_custom_setting"></td>
    </tr>';

    return $settings;
  }

  function save_my_custom_form_setting( $form ) {
    $form['my_custom_setting'] = rgpost( 'my_custom_setting' );
    return $form;
  }





}
