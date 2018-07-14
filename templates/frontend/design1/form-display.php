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

echo '<pre>';
print_r($form);

echo '</pre>';
 */

if(!$form_title) {
  echo '<h1>'.$title.'</h1>';
}

if(!$form_desc) {
  echo '<h3>'.$description.'</h3>';
}


echo '<div id="' . $form['gf_frontend']['gf_frontend_id'] . '" class="' . $form['gf_frontend']['gf_frontend_class'] .'" >';
echo GFFormDisplay::get_form( $form['id'], $form_title, $form_desc, '', '', $ajax, $tabindex );
echo '</div>';

// TODO:  apply custom javascript and css

// TODO:  hook into clicks

?>


<style>


    <?php if($form['gf_frontend']['gf_progress_bar'] === '0'): ?>
    .gform_wrapper .gf_progressbar_wrapper {
        display: none;
    }
    <?php endif; ?>


    <?php if($form['gf_frontend']['gf_preloader'] === '0'  || $form['gf_frontend']['gf_preloader'] === 'none'): ?>

    <?php else: ?>

    body img.gform_ajax_spinner {
        display: none !important;
    }


    /* TODO: add svg spinners */
    .loading {
        background: url(<?php echo '/wp-content/' . Gf_Frontend::APP_TEMPLATE_URI . '/common/spinners/' . $form['gf_frontend']['gf_preloader']; ?>) top center no-repeat;
    }

    <?php endif; ?>

</style>

<script>
  jQuery(document).ready(function($) {

    <?php if($form['gf_frontend']['gf_transition'] != 'none'): ?>

    var b   = $( "body" );
    var pos = $('<?php echo $form['gf_frontend']['gf_preloader_position']; ?>');


    b.on( "click", ".gform_previous_button", function(e) {
      pos.addClass('loading');
      $(this).parent().parent().addClass("animated <?php echo $form['gf_frontend']['gf_transition_previous']; ?>");
      //e.preventDefault();
    });

    // check for animations, and add
    b.on( "click", ".gform_next_button", function(e) {

      pos.addClass('loading');
      $(this).parent().parent().addClass("animated <?php echo $form['gf_frontend']['gf_transition_next']; ?>");
      //e.preventDefault();
    });


    b.on( "click", "#gform_submit_button_<?php echo $form['id']; ?>", function(e) {
      pos.addClass('loading');
      $(this).parent().parent().addClass("animated <?php echo $form['gf_frontend']['gf_transition_submit']; ?>");
      //e.preventDefault();
    });

    //remove loading spinner after next page
    jQuery(document).bind('gform_post_render', function(){
      pos.removeClass('loading');
    });


  <?php endif; ?>

  });
</script>


