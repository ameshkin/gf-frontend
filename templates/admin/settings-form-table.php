<?php


echo ob_get_clean();


//$forms = json_encode(GFAPI::get_forms());

$forms = GFAPI::get_forms();


// $form = GFAPI::get_form( $form_id );

?>

<form>
    <p>Forms</p>


    <div>

      <?php

      //require_once(  '../../../Links_List_Table.class.php' );
      require_once(  ABSPATH . '/wp-content/plugins/gf-frontend/class/GFLinksListTable.class.php' );

      if(!class_exists('WP_List_Table')){

        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
      }

      $wp_list_table = new GFLinksListTable();


      $wp_list_table->prepare_items();

      // prepare items but from $forms = GFAPI::get_forms();
      $wp_list_table->get_data();


      $wp_list_table->display();


      ?>
    </div>

    <br><Br><Br>
    <div>
      <?php
      if ( $forms ) {
        echo '<table border="1">

        <th>Form ID</th><th>Form Title</th><th>Entry Count</th><th>Shortcode</th>';
        foreach ( $forms as $form ) {


          echo '<tr>
<td>' . $form['id'] . '</td>
<td>' . $form['title'] . '</td>
<td>' . $form['entries'] . '</td>
<td>' . $form['shortcode'] . '</td></tr>';

        }
        echo '</table>';
      }
      ?>
    </div>
    <br/>
    <div>JSON Response:<br/><textarea style="vertical-align: top" cols="125" rows="10"> <?php echo json_encode($forms,JSON_PRETTY_PRINT); ?></textarea></div>
</form>
