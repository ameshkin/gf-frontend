<?php

/**
 * TODO: All hooks and actions to be put here
 */

GFForms::include_addon_framework();

add_action('gform_after_save_form', 'gfActions::add_default_fields', 10, 2);


class GFActions extends gfAddOn
{



  public static function append_transition( $next_button, $form ) {


    //print_r($form);
    //$next_button = '<div class="my-custom-class">' . $next_button . '</div>';

    //return $next_button;
  }


  /**
   * @param $button
   * @param $form
   * @return string
   */
  public static function append_submit( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $onclick = $input->getAttribute( 'onclick' );
    $onclick .= " addAdditionalAction('Additional Action'); alert('transition');";
    $input->setAttribute( 'onclick', $onclick );



    return $dom->saveHtml( $input );
  }


  /**
   * Changes the default Gravity Forms AJAX spinner.
   *
   * @since 1.0.0
   *
   * @param string $src  The default spinner URL.
   * @return string $src The new spinner URL.
   */
  function gf_custom_spinner( $src ) {

    // TODO: get this from settings

    //echo Gf_Frontend::APP_TEMPLATE_URI . '/common/spinners/red-bar.gif'; exit;

    return Gf_Frontend::APP_TEMPLATE_URI . '/common/spinners/red-bar.gif';

  }


  public static function add_default_fields($form, $is_new)
  {



    // for adding default fields, no use yet

    /*

    // add default fields to new forms

    if ($is_new) {
      $form['fields'] = array(
        array(
          'label'   => esc_html__( 'Select Template', 'gf-frontend' ),
          'type'    => 'select',
          'name'    => 'template',
          'tooltip' => esc_html__( 'Select a front end template to use for this form', 'gf-frontend' ),
          'choices' => array(
            array(
              'label' => esc_html__( 'First Choice', 'gf-frontend' ),
              'value' => 'first',
            ),
            array(
              'label' => esc_html__( 'Second Choice', 'gf-frontend' ),
              'value' => 'second',
            ),
            array(
              'label' => esc_html__( 'Third Choice', 'gf-frontend' ),
              'value' => 'third',
            ),
          ),
        ),
        array(
          'type' => 'text',
          'label' => 'Email',
          'id' => 2,
          'defaultValue' => 'user:user_email',
          'formId' => $form['id'],
        ),
      );
      GFAPI::update_form($form);


    }

    */
  }





}
