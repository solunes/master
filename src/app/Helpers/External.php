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

    public static function share_facebook($url) {
      $original_url = 'https://www.facebook.com/sharer/sharer.php?u=';
      $url = $original_url.urlencode($url);
      return $url;
    }

    public static function share_twitter($url, $title) {
      $original_url = 'https://twitter.com/intent/tweet?';
      if(!$title){
        $title = 'Comparte tu contenido en twitter';
      }
      $title .= ' - ';
      $url = $original_url.'text='.$title.'&url='.urlencode($url);
      return $url;
    }

    public static function share_email($recipient, $title, $message) {
      $original_url = 'mailto:';
      $url = $original_url.$recipient.'?';
      if($title){
        $url .= '&subject='.$title;
      }
      if($message){
        $url .= '&body='.$message;
      }
      return $url;
    }

    public static function share_linkedin($url, $title, $message) {
      $original_url = 'https://www.linkedin.com/shareArticle?mini=true&';
      if(!$title){
        $title = 'Comparte tu contenido en linkedin';
      }
      $url = $original_url.'url='.urlencode($url).'&title='.$title;
      if($message){
        $url .= '&summary='.$message;
      }
      return $url;
    }

    public static function share_pinterest($url, $message, $image) {
      $original_url = 'https://pinterest.com/pin/create/button/?';
      if(!$message){
        $message = 'Comparte tu contenido en Pinterest';
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

}