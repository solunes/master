<?php 

namespace Solunes\Master\App\Helpers;

class External {

    public static function external_share($method_name, $url, $title = NULL, $message = NULL, $image = NULL, $recipient = NULL) {
      $full_method_name = 'share_'.$method_name;
      switch ($method_name) {
        case 'facebook':
        case 'google':
            $final_url = \External::$full_method_name($url);
            break;
        case 'twitter':
            $final_url = \External::$full_method_name($url, $title);
            break;
        case 'linkedin':
            $final_url = \External::$full_method_name($url, $title, $message);
            break;
        case 'email':
            $final_url = \External::$full_method_name($recipient, $title, $message);
            break;
        case 'pinterest':
            $final_url = \External::$full_method_name($url, $message, $image);
            break;
      }
      return $final_url;
    }

    public static function share_url($method_name, $url = NULL, $message = NULL) {
      $full_method_name = 'share_'.$method_name;
      if(!$url){
        $url = request()->fullUrl();
      }
      switch ($method_name) {
        case 'facebook':
        case 'google':
          $final_url = \External::$full_method_name($url);
          break;
        case 'twitter':
          if(!$message){
            $message = 'Miren lo que encontré';
          }
          $final_url = \External::$full_method_name($url, $message);
          break;
        case 'linkedin':
          $final_url = \External::$full_method_name($url);
          break;
        case 'email':
          if(!$message){
            $message = 'Miren lo que encontré';
          }
          $message .= ' - '.$url;
          $final_url = \External::$full_method_name(NULL, NULL, $message);
          break;
        case 'pinterest':
          if(!$message){
            $message = 'Miren lo que encontré';
          }
          $final_url = \External::$full_method_name($url, $message);
          break;
      }
      return $final_url;
    }

    public static function share_facebook($url = NULL) {
      $original_url = 'https://www.facebook.com/sharer/sharer.php?u=';
      $url = $original_url.urlencode($url);
      return $url;
    }

    public static function share_twitter($url, $title = NULL) {
      $original_url = 'https://twitter.com/intent/tweet?';
      if(!$title){
        $title = 'Miren lo que encontré';
      }
      $title .= ' - ';
      $url = $original_url.'text='.$title.'&url='.urlencode($url);
      return $url;
    }

    public static function share_email($recipient, $title = NULL, $message = NULL) {
      $original_url = 'mailto:';
      $url = $original_url.$recipient.'?';
      if(!$title){
        $title = ' ';
      }
      $url .= 'subject='.$title;
      if($message){
        $url .= '&body='.$message;
      }
      return $url;
    }

    public static function share_linkedin($url, $title = NULL, $message = NULL) {
      $original_url = 'https://www.linkedin.com/shareArticle?mini=true&';
      if(!$title){
        $title = 'Miren lo que encontré';
      }
      $url = $original_url.'url='.urlencode($url).'&title='.$title;
      if($message){
        $url .= '&summary='.$message;
      }
      return $url;
    }

    public static function share_pinterest($url, $message = NULL, $image = NULL) {
      $original_url = 'https://pinterest.com/pin/create/button/?';
      if(!$message){
        $message = 'Miren lo que encontré';
      }
      $url = $original_url.'url='.urlencode($url).'&description='.$message;
      if($image){
        $url .= '&media='.$image;
      }
      return $url;
    }

    public static function share_google($url) {
      $original_url = 'https://plus.google.com/share?';
      $url = $original_url.'&url='.urlencode($url);
      return $url;
    }

    public static function reduceName($name) {
      $first_name = $last_name = null;
      $arr = explode(' ', $name);
      $num = count($arr);
      $count = floor($num/2);
      foreach($arr as $key => $arr_item){
          if($key==0||$key==1&&$num>3||$key==2&&$num>5){
              if($first_name){
                  $first_name .= ' ';
              }
              $first_name .= $arr_item;
          } else {
              if($last_name){
                  $last_name .= ' ';
              }
              $last_name .= $arr_item;
          }
      }
      return ['first_name'=>$first_name, 'last_name'=>$last_name];
    }

}