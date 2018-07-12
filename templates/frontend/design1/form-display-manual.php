<?php

/*
Template Name: Design 1
Description: Design 1 uses a basic design based on gravity forms default
Slug: design1
Version: 1.0
Author: Amir Meshkin
Author URI: http://amir-meshkin.com
*/


//TODO: add required

//TODO:

/*
 *
 *  TODO: CONDITIONAL LOGIC
 * add_filter( 'gform_pre_render', 'set_conditional_logic' );
add_filter( 'gform_pre_process', 'set_conditional_logic' );
function set_conditional_logic( $form ) {

    //Set conditional logic only for form 14
    if ( $form['id'] !== 14 ) {
        return $form;
    }

    foreach ( $form['fields'] as &$field ) {
        if ( $field->id == 2 ) {
            $field->conditionalLogic =
                array(
                    'actionType' => 'show',
                    'logicType' => 'all',
                    'rules' =>
                        array( array( 'fieldId' => 1, 'operator' => 'is', 'value' => 'First Choice' ) )
                );
        }
    }
    return $form;
}
 */


// function call
// https://docs.gravityforms.com/embedding-a-form/#shortcode-manually
// gravity_form( $id_or_title, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false, $tabindex, $echo = true );



// get footer
// gform_footer( $form, $class, $ajax, $field_values, $previous_button, $display_title, $display_description, $tabindex = 1 )


/*
 * TODO: FORM PRE RENDER
 *  https://docs.gravityforms.com/gform_pre_render/
 * add_filter( 'gform_pre_render', 'populate_choices' );

//Note: when changing choice values, we also need to use the gform_pre_validation so that the new values are available when validating the field.
add_filter( 'gform_pre_validation', 'populate_choices' );

//Note: when changing choice values, we also need to use the gform_admin_pre_render so that the right values are displayed when editing the entry.
add_filter( 'gform_admin_pre_render', 'populate_choices' );

//Note: this will allow for the labels to be used during the submission process in case values are enabled
add_filter( 'gform_pre_submission_filter', 'populate_choices' );
function populate_choices( $form ) {

    //only populating drop down for form id 5
    if ( $form['id'] != 5 ) {
       return $form;
    }

    //Reading posts for "Business" category;
    $posts = get_posts( 'category=' . get_cat_ID( 'Business' ) );

    //Creating item array.
    $items = array();

    //Add a placeholder to field id 8, is not used with multi-select or radio, will overwrite placeholder set in form editor.
    //Replace 8 with your actual field id.
    $fields = $form['fields'];
    foreach( $form['fields'] as &$field ) {
      if ( $field->id == 8 ) {
        $field->placeholder = 'This is my placeholder';
      }
    }

    //Adding post titles to the items array
    foreach ( $posts as $post ) {
        $items[] = array( 'value' => $post->post_title, 'text' => $post->post_title );
    }

    //Adding items to field id 8. Replace 8 with your actual field id. You can get the field id by looking at the input name in the markup.
    foreach ( $form['fields'] as &$field ) {
        if ( $field->id == 8 ) {
            $field->choices = $items;
        }
    }

    return $form;
}
 */

//echo GFFormDisplay::get_form( $atts['id'], $atts['title'], $atts['description'], '', '', $atts['ajax'], $atts['tabindex'] );
?>


<div class="gf-frontend-wrapper animated <?php echo  $form->gf_frontend->gf_transitions; ?>">
  <div class="row">
    <div class="col-md-12">

      <form method="post" id="gf-form-<?php echo $form['fields'][0]->formId; ?>" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="<?php echo $form->gf_frontend->gf_template . ' ' . $form->cssClass; ?>">
        <div class="gform_heading">
          <h3 class="gform_title"><?php echo $title; ?></h3>
          <span class="gform_description"><?php echo $description; ?></span>
        </div>


        <div class="gform_body">
          <ul id="gform_fields_2" class="gform_fields top_label form_sublabel_below description_below">



            <?php foreach($form['fields'] as $field): ?>

              <li id="li-<?php echo $field->id; ?>" class="gfield field_sublabel_below field_description_below gfield_visibility_visible">


                <label class="gfield_label" for="input-<?php echo $field->id; ?>"><?php echo $field->label; ?></label>
                <div class="ginput_container ginput_container_<?php echo $field->type; ?>">


                  <?php


                  switch ($field->type) {
                    case 'text':

                      ?>
                      <input name="<?php echo $field->inputName; ?>" id="input-<?php echo $field->id; ?>" type="<?php echo $field->type; ?>" value="" maxlength="<?php echo $field->maxLength; ?>" class="<?php echo $field->cssClass; ?>" aria-invalid="false" placeholder="<?php echo $field->placeholder; ?>">
                      <?php

                      break;
                    case 'select':


                      ?>
                      <select name="<?php echo $field->inputName; ?>" id="input-<?php echo $field->id; ?>" class="<?php echo $field->size; ?> gfield_<?php echo $field->type; ?>" aria-invalid="false">


                        <?php foreach($field->choices as $choice): ?>

                          <option value="<?php echo $choice['value']; ?>"><?php echo $choice['text']; ?></option>
                        <?php endforeach; ?>
                      </select>

                      <?php
                      break;
                    case 2:
                      echo "i equals 2";
                      break;
                  }
                  ?>

                </div>
              </li>


            <?php endforeach; ?>

          </ul>
        </div>

        <?php

        echo GFFormDisplay::gform_footer( $form, $class, $ajax, $field_values, $previous_button, $display_title, $display_description, $tabindex = 1 );
        ?>
        <!--
        <div class="gform_footer top_label">

            <input type="submit" id="gform_submit_button_2" class="gform_button button" value="Submit" onclick="if(window[&quot;gf_submitting_2&quot;]){return false;}  window[&quot;gf_submitting_2&quot;]=true;  " onkeypress="if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_2&quot;]){return false;} window[&quot;gf_submitting_2&quot;]=true;  jQuery(&quot;#gform_2&quot;).trigger(&quot;submit&quot;,[true]); }">


            <input type="hidden" class="gform_hidden" name="is_submit_2" value="1">
            <input type="hidden" class="gform_hidden" name="gform_submit" value="2">

            <input type="hidden" class="gform_hidden" name="gform_unique_id" value="">
            <input type="hidden" class="gform_hidden" name="state_2" value="WyJbXSIsIjgyY2MyZGExNzc4MzNkMTY3MzQyZDRhNjhmN2I5MjM0Il0=">
            <input type="hidden" class="gform_hidden" name="gform_target_page_number_2" id="gform_target_page_number_2" value="0">
            <input type="hidden" class="gform_hidden" name="gform_source_page_number_2" id="gform_source_page_number_2" value="1">
            <input type="hidden" name="gform_field_values" value="">

        </div>

        -->
      </form>

      <textarea id="code1">
            <?php echo json_encode($form,JSON_PRETTY_PRINT); ?>
        </textarea>
      <script>
        jQuery(document).ready(function() {
          CodeMirror.fromTextArea(document.getElementById('code1'), {
            lineNumbers: true,
            matchBrackets: true,
            styleActiveLine: true,
            theme:"tomorrow-night-bright"
          });
        });
      </script>

    </div>
  </div>

</div>
