<?php


class GFLinksListTable extends WP_List_Table
{

  /**
   * Constructor, we override the parent to pass our own arguments
   * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
   */
  function __construct()
  {
    parent::__construct(array(
      'singular' => 'wp_list_gf_template', //Singular label
      'plural' => 'wp_list_gf_templates', //plural label, also this well be one of the table css class
      'ajax' => false //We won't support Ajax for this table
    ));
  }


  /**
   * Add extra markup in the toolbars before or after the list
   * @param string $which , helps you decide if you add the markup after (bottom) or before (top) the list
   */
  function extra_tablenav($which)
  {
    if ($which == "top") {
      //The code that goes before the table is here
      echo "Hello, I'm before the table";
    }
    if ($which == "bottom") {
      //The code that goes after the table is there
      echo "Hi, I'm after the table";
    }
  }


  /**
   * Define the columns that are going to be used in the table
   * @return array $columns, the array of columns to use with the table
   */
  function get_columns()
  {
    return $columns = array(
      'col_id' => __('id'),
      'col_name' => __('Name'),
      'col_url' => __('Url'),
      'col_description' => __('Description'),
      'col_status' => __('Status')
    );
  }


  /**
   * Decide which columns to activate the sorting functionality on
   * @return array $sortable, the array of columns that can be sorted by the user
   */
  public function get_sortable_columns()
  {
    return $sortable = array(
      'col_id' => 'id',
      'col_name' => 'name',
      'col_url' => 'url'
    );
  }


  /**
   * no longer needed
   * Prepare the table with different parameters, pagination, columns and table elements
   */
  function prepare_items()
  {
    global $wpdb, $_wp_column_headers;
    $screen = get_current_screen();

    /*
    echo '<pre>get_current_screen';
    print_r($screen);
    echo '</pre>';

    */

    /* -- Preparing your query -- */
    $query = "SELECT * FROM wp_gf_templates";


    /* -- Ordering parameters -- */
    //Parameters that are going to be used to order the result
    $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
    $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
    if (!empty($orderby) & !empty($order)) {
      $query .= ' ORDER BY ' . $orderby . ' ' . $order;
    }

    /* -- Pagination parameters -- */
    //Number of elements in your table?
    $totalitems = $wpdb->query($query); //return the total number of affected rows
    //How many to display per page?
    $perpage = 5;
    //Which page is this?
    $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
    //Page Number
    if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
      $paged = 1;
    } //How many pages do we have in total?
    //
    $totalpages = ceil($totalitems / $perpage); //adjust the query to take pagination into account
    if (!empty($paged) && !empty($perpage)) {
      $offset = ($paged - 1) * $perpage;
      $query .= ' LIMIT ' . (int)$offset . ',' . (int)$perpage;
    } /* -- Register the pagination -- */
    $this->set_pagination_args(array(
      "total_items" => $totalitems,
      "total_pages" => $totalpages,
      "per_page" => $perpage,
    ));
    //The pagination links are automatically built according to those parameters

    /* -- Register the Columns -- */
    $columns = $this->get_columns();


    /*
    echo '<pre><h1>columns set here</h1>';
    print_r($columns);
    echo '</pre>';

    */


    $_wp_column_headers[$screen->id] = $columns;


    /*
    echo '<pre><h1>query run  here</h1>';
    print_r($query);
    echo '</pre>';

    */

    /* -- Fetch the items -- */
    $this->items = $wpdb->get_results($query);


    /*
    echo '<pre><h1>items set  here</h1>';
    print_r($this->items);
    echo '</pre>';
    */
  }

  function get_data()
  {
    global $wpdb, $_wp_column_headers;
    $screen = get_current_screen();


    $columns = $this->get_columns();


    $_wp_column_headers[$screen->id] = $columns;


    $formdata = GFAPI::get_forms();

    /*
    echo '<pre><h1>get_data: items set  here</h1>';
    print_r($formdata);
    echo '</pre>';
*/

    $this->items = self::transform_data($formdata);

    /*
    echo '<pre><h1>get_data: items set  here</h1>';
    print_r($this->items);
    echo '</pre>';

    */

  }


  /*
   * Set data after transforming
   */
  function transform_data($formdata)
  {


    $arr = [];
    foreach ($formdata as $form) {
      $arr[] = [
        'id' => $form['id'],
        'name' => $form['title'],
      ];


      /*
            $arr['name'] = $form['name'];

            $arr['url'] = $form['url'];
            $arr['description'] = $form['description'];
            $arr['status'] = $form['status'];

      */

    }
    // need to change gfapi format to my format

    /*
     *    (
            [id] => 1
            [name] => test
            [url] => sdfasdf
            [description] => sadfasdfasdf
            [status] => 1
        )
     */


    return $arr;
  }

  /**
   * Display the rows of records in the table
   * @return string, echo the markup of the rows
   */
  function display_rows()
  {

    //Get the records registered in the prepare_items method
    $records = $this->items;


    //$column_info = array_filter($this->get_column_info());
    /*
        echo '<pre><h1>get_column_info run  here</h1>';
        print_r($column_info);
        echo '</pre>';
    */

    //Get the columns registered in the get_columns and get_sortable_columns methods
    //list( $columns, $hidden ) = $column_info;

    $columns = [
      "col_id" => "ID",
      "col_name" => "Name",
      "col_edit" => "Edit"];

    $hidden = ["col_description"];

    /*
        echo '<pre><h1>columns here</h1>';
        print_r($columns);
        echo '</pre>';


    */
    //Loop for each record
    $i = 0;
    if (!empty($records)) {
      foreach ($records as $rec) {
        /*
              echo '<pre><p>record here</p>';
              print_r($rec);
              echo '</pre>';
        */


        /*

        echo '<pre><p>record here</p>';
        print_r($rec);
        echo '</pre>';
        */
        //exit;


        // headers

        if ($i === 0) {
          echo '<th>id</th>';
          echo '<th>Name</th>';
          echo '<th>Edit</th>';
        }

        //Open the line
        echo '<tr id="record_' . $rec->id . '">';
        //foreach ( $columns as $column_name => $column_display_name ) {


        foreach ($columns as $column_name => $column_display_name) {

          //echo "column_name: ".$column_name;

          //Style attributes for each col
          $class = "class='$column_name column-$column_name'";
          $style = "";
          if (in_array($column_name, $hidden)) $style = ' style="display:none;"';
          $attributes = $class . $style;

          //Display the cell
          switch ($column_name) {
            case "col_id":


              echo '<td ' . $attributes . '>' . stripslashes($rec['id']) . '</td>';


              break;


            case "col_name":

              echo '<td ' . $attributes . '>' . stripslashes($rec['name']) . '</td>';


              break;


            case "col_edit":

              echo '<td ' . $attributes . '><a href="/wp-admin/admin.php?page=gf_edit_forms&id=1' . (int)$rec['id'] . '">EDIT FORM</td>';


              break;
          }

          //exit;


        }

        //Close the line
        echo '</tr>';

        $i++;
      }
    }


    /**
     * Display forms from WFAPI
     */
    function display_form_rows()
    {

      //Get the records registered in the prepare_items method
      $records = $this->items;


      //$column_info = array_filter($this->get_column_info());
      /*
          echo '<pre><h1>get_column_info run  here</h1>';
          print_r($column_info);
          echo '</pre>';
      */

      //Get the columns registered in the get_columns and get_sortable_columns methods
      //list( $columns, $hidden ) = $column_info;

      $columns = [
        "col_id" => "ID",
        "col_name" => "Name",
        "col_edit" => "Edit"];

      $hidden = ["col_description"];

      /*
          echo '<pre><h1>columns here</h1>';
          print_r($columns);
          echo '</pre>';


      */
      //Loop for each record
      $i = 0;
      if (!empty($records)) {
        foreach ($records as $rec) {
          /*
                echo '<pre><p>record here</p>';
                print_r($rec);
                echo '</pre>';
          */


          /*

          echo '<pre><p>record here</p>';
          print_r($rec);
          echo '</pre>';
          */
          //exit;


          // headers

          if ($i === 0) {
            echo '<th>id</th>';
            echo '<th>Name</th>';
            echo '<th>Edit</th>';
          }

          //Open the line
          echo '<tr id="record_' . $rec->id . '">';
          //foreach ( $columns as $column_name => $column_display_name ) {


          foreach ($columns as $column_name => $column_display_name) {

            //echo "column_name: ".$column_name;

            //Style attributes for each col
            $class = "class='$column_name column-$column_name'";
            $style = "";
            if (in_array($column_name, $hidden)) $style = ' style="display:none;"';
            $attributes = $class . $style;

            //Display the cell
            switch ($column_name) {
              case "col_id":


                echo '<td ' . $attributes . '>' . stripslashes($rec['id']) . '</td>';


                break;


              case "col_name":

                echo '<td ' . $attributes . '>' . stripslashes($rec['name']) . '</td>';


                break;


              case "col_edit":

                echo '<td ' . $attributes . '><a href="/wp-admin/admin.php?page=gf_edit_forms&id=' . (int)$rec['id'] . '">GRAVITY FORMS LINK </td>';


                break;
            }

            //exit;


          }

          //Close the line
          echo '</tr>';

          $i++;
        }
      }
    }

  }

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

}
