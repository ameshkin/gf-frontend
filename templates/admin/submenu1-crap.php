<div class="admin-wrap">

    <h2>Form Templates</h2>

  <?php



  //Public API Key
  //ce87f3162f

  //Private API Key
  //73d032fe9c0f6dd

  //set API keys
  $api_key = 'ce87f3162f';
  $private_key = '73d032fe9c0f6dd';

  //set route
  $route = 'forms';

  // http://localhost:3333/gravityformsapi/forms/
  //creating request URL
  $expires = strtotime( '+60 mins' );
  $string_to_sign = sprintf( '%s:%s:%s:%s', $api_key, 'GET', $route, $expires );
  $sig = calculate_signature( $string_to_sign, $private_key );
  //$url = 'http://localhost:3333/gravityformsapi/' . $route . '?api_key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires;

  $url = 'http://localhost:3333/gravityformsapi/' . $route . '?api_key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires;

  $url2 = 'http://localhost:3333/gravityformsapi/forms';




  echo $url;

  echo '<br>';

  //retrieve data
  $response = wp_remote_request( $url2, array( 'method' => 'GET' ) );




  echo '<pre>';
   var_dump($response);

  echo '<br>';

  if ( wp_remote_retrieve_response_code( $response ) != 200 || ( empty( wp_remote_retrieve_body( $response ) ) ) ){
    //http request failed
    die( 'There was an error attempting to access the API.' );
  }

  //result is in the response "body" and is json encoded.
  $body = json_decode( wp_remote_retrieve_body( $response ), true );

  echo '<pre>';
  var_dump($body);

  echo '<br>';

  if( $body['status'] > 202 ){
    die( "Could not retrieve forms." );
  }

  //forms retrieved successfully
  $forms = $body['response'];

  //To access each form, loop through the $forms array.
  foreach ( $forms as $form ){
    $form_id     = $form['id'];
    $form_title  = $form['title'];
    $entry_count = $form['entries'];
  }

  function calculate_signature( $string, $private_key ) {
    $hash = hash_hmac( 'sha1', $string, $private_key, true );
    $sig = rawurlencode( base64_encode( $hash ) );
    return $sig;
  }
  ?>



</div>
