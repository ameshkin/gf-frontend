<?php


class  GFUtility extends GFAddOn {



  /**
   * UTILITY: timer script
   * @return float
   */
  function timer()
  {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
  }



  /**
   * Get folder names of templates but that is all!
   * @param $path
   * @return array
   */
  public static function get_folder_names($path) {

    if ($handle = opendir($path)) {

      $folders = [];

      while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != "..") {

          $folders[] = [ 'label' => $entry, 'value' => $entry ];

        }
      }
      closedir($handle);
    }
    return $folders;
  }

  /**
   * //TODO: allow other animation libraries
   * Get folder names of templates but that is all!
   * @param $path
   * @return array
   */
  public static function get_animation_choices() {

    $choices = [];
    $animations = ['none',
      'bounce',	'flash',	'pulse',	'rubberBand',
      'shake',	'headShake',	'swing',	'tada',
      'wobble',	'jello',	'bounceIn',	'bounceInDown',
      'bounceInLeft',	'bounceInRight',	'bounceInUp',	'bounceOut',
      'bounceOutDown',	'bounceOutLeft',	'bounceOutRight',	'bounceOutUp',
      'fadeIn',	'fadeInDown',	'fadeInDownBig',	'fadeInLeft',
      'fadeInLeftBig',	'fadeInRight',	'fadeInRightBig',	'fadeInUp',
      'fadeInUpBig',	'fadeOut',	'fadeOutDown',	'fadeOutDownBig',
      'fadeOutLeft',	'fadeOutLeftBig',	'fadeOutRight',	'fadeOutRightBig',
      'fadeOutUp',	'fadeOutUpBig',	'flipInX',	'flipInY',
      'flipOutX','flipOutY','lightSpeedIn',	'lightSpeedOut',
      'rotateIn',	'rotateInDownLeft',	'rotateInDownRight',	'rotateInUpLeft',
      'rotateInUpRight',	'rotateOut',	'rotateOutDownLeft',	'rotateOutDownRight',
      'rotateOutUpLeft',	'rotateOutUpRight',	'hinge',	'jackInTheBox',
      'rollIn',	'rollOut',	'zoomIn',	'zoomInDown',
      'zoomInLeft',	'zoomInRight',	'zoomInUp',	'zoomOut',
      'zoomOutDown',	'zoomOutLeft',	'zoomOutRight',	'zoomOutUp',
      'slideInDown',	'slideInLeft',	'slideInRight',	'slideInUp',
      'slideOutDown','slideOutLeft',	'slideOutRight',	'slideOutUp'];


    foreach ($animations as $choice) {

      $choices[] = [ 'label' => $choice, 'value' => $choice ];

    }

    return $choices;
  }


  /**
   * YES/NO dropdown
   * @return array
   */
  public static function get_bool() {


    $choices = [];
    $labels = ['No', 'Yes'];


    foreach ($labels as $key=>$value) {

      $choices[] = [ 'label' => $value, 'value' => $key ];

    }

    return $choices;
  }



  /**
   * UTILITY: Error output
   * @param $input
   */
  public static function _err($input) {

    if(self::APP_DEBUG) {
      if(is_object($input) || is_array($input)) {
        echo '<pre>';
        print_r($input);
        echo '</pre>';
      } else {
        error_log($input);
      }
    }
  }


}

