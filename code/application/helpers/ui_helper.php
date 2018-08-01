<?php
if (!function_exists('_get_error')) {

  /**
   * get the array in pretty format
   *
   * @return array
   */
  function _get_error(string $message, string $type = NULL) {

    $str = '';
    if (isset($message)) :
      $str = '<div class="alert alert-' . $type . '" style="opacity: 1; display: block; transform: translateY(0px);">
            <div class="error">' . $message . '</div>
        </div>';


     endif;
    return $str;

  }
}

if (!function_exists('_load_js')) {

  /**
   * function to add script
   *
   * @param array $js
   */
  function _load_js($js) {

    if ($js) :
      foreach ( $js as $val )
        echo $val;


    endif;

  }
}

if (!function_exists('_load_style')) {

  /**
   * function to add script
   *
   * @param array $js
   */
  function _load_style($style) {

    if ($style) :
    foreach ( $style as $val )
      echo $val;


    endif;

  }
}

if (!function_exists('_orderby')) {

  /**
   * function to create the order by html
   *
   * @param string $label
   *          [header label]
   * @param string $field
   *          [mysql field]
   * @param string $orderby
   *          [current order by field]
   * @param string $order
   *          [current order]
   * @return string|html
   */
  function _orderby($label, $field, $orderby, $order) {

    $orderby_html = $hidden_asc = $hidden_desc = '';
    if ($field == $orderby) {
      if (strtolower($order) == 'asc') {
        $hidden_asc = 'hidden';
      }

      if (strtolower($order) == 'desc') {
        $hidden_desc = 'hidden';
      }
    }
    $orderby_html .= '<div class="orderby-icon orderby ' . $hidden_asc . '" data-orderby="' . $field . '" data-order="ASC"><a href="#" ><span class="ti ti-angle-up"></span></a></div>';

    $orderby_html .= '<div class="orderby-icon orderby ' . $hidden_desc . '" data-orderby="' . $field . '" data-order="DESC"><a href="#"><span class="ti ti-angle-down"></span></a></div>';

    return $label . ' <div class="pull-right">' . $orderby_html . '</div>';

  }
}

if (!function_exists('_grid_hidden_fields')) {

  function _grid_hidden_fields($cur_page = 0, $callback = null, $orderby = null, $order = null) {

    $str = '';
    if ($cur_page >= 0) {
      $str .= '<input type="text" name="page" id="page" value="' . $cur_page . '">';
    }
    if ($callback) {
      $str .= '<input type="text" name="callback" id="callback" value="' . $callback . '">';
    }
    if ($orderby) {
      $str .= '<input type="text" name="orderby" id="orderby" value="' . $orderby . '">';
    }
    if ($order) {
      $str .= '<input type="text" name="order" id="order" value="' . $order . '">';
    }
    return $str;

  }
}

if (!function_exists('_actions_icon')) {

  function _actions_icon($url, $type, $class = 'click',$id = '1',$parent_menu = '') {

    $html = '';

    if ($type == 'view') {
      $html .= '<a href="' . $url . '" class="' . $class . '" id="' . $id . '" data-callback="' . $url . '" parent-callback="'.$parent_menu.'"><span class="ti ti-search"></span> </a>';
    }

    return $html;

  }
}
