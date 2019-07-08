<?php 

namespace Solunes\Master\App\Helpers;

use GuzzleHttp\Client;

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

  public static function generateTrigger($name, $date, $time, $internal_url) {
    $trigger = new \Solunes\Master\App\Trigger;
    $trigger->name = $name;
    $trigger->internal_url = $internal_url;
    $trigger->date = $date;
    $trigger->time = $time;
    $trigger->save();
    \External::sendTrigger($trigger->id);
    return $trigger;
  }

  public static function sendTrigger($trigger_id) {
    if($trigger = \Solunes\Master\App\Trigger::find($trigger_id)){
      $token = config('solunes.scheduler_api_key');
      if(config('solunes.test_enabled')){
        $type = 'test';
      } else {
        $type = 'production';
      }
      \External::guzzleGet(config('solunes.scheduler_url'), 'create-trigger/'.$token.'/'.$trigger->name.'/'.$trigger->internal_url.'/'.$trigger->date.'/'.$trigger->time.'/'.$type, []);
      return true;
    } else {
      return false;
    }
  }

  public static function cancelTrigger($trigger_id) {
    $token = config('solunes.scheduler_api_key');
    if($trigger = \Solunes\Master\App\Trigger::find($trigger_id)){
      \External::guzzleGet(config('solunes.scheduler_url'), 'cancel-trigger/'.$token.'/'.$trigger_id, []);
      return true;
    } else {
      return false;
    }
  }

  public static function guzzleGet($url, $action, $parameters, $headers = []) {
    $url .= '/'.$action;
    $rand_code = rand(100000, 999999);
    if(!isset($headers['Content-Type'])){
      $headers['Content-Type'] = 'application/json';
    }
    \Log::info('GuzGetQuery '.$rand_code.': '.$url.' - '.json_encode($parameters));
    $count = 0;
    foreach($parameters as $parameter_key => $parameter_val){
      if($count==0){
        $url .= '?';
      } else {
        $url .= '&';
      }
      $url .= $parameter_key.'='.$parameter_val;
      $count++;
    }
    $params = ['headers' => $headers, 'http_errors'=>false];
    if(isset($headers['auth_username'])&&isset($headers['auth_password'])){
      $params['auth'] = [$headers['auth_username'],$headers['auth_password']];
      unset($headers['auth_username']);
      unset($headers['auth_password']);
    }
    \Log::info(json_encode($params));
    try{
      $client = new Client($params);
      $response  = $client->get($url);
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      $response = $e->getResponse();
    }
    $content = $response->getBody()->getContents();
    $status = $response->getStatusCode();
    if($status>399){
      \Log::info('GuzGetError '.$rand_code.': '.$status.' - '.json_encode($content));
    } else {
      \Log::info('GuzGetResponse '.$rand_code.': '.$status.' - '.json_encode($content));
    }
    return json_decode($content, true);
  }

  public static function guzzlePost($url, $action, $parameters, $headers = []) {
    $url .= '/'.$action;
    $rand_code = rand(100000, 999999);
    if(!isset($headers['Content-Type'])){
      $headers['Content-Type'] = 'application/json';
    }
    \Log::info('GuzPostQuery '.$rand_code.': '.$url.' - '.json_encode($parameters));
   
    $params = ['headers' => $headers, 'http_errors'=>false];
    if(isset($headers['auth_username'])&&isset($headers['auth_password'])){
      $params['auth'] = [$headers['auth_username'],$headers['auth_password']];
      unset($headers['auth_username']);
      unset($headers['auth_password']);
    }
    \Log::info(json_encode($params));
    try{
      $client = new Client($params);
      if($headers['Content-Type']=='application/json'){
        $response  = $client->post($url,  [\GuzzleHttp\RequestOptions::JSON =>$parameters]);
      } else {
        $response  = $client->post($url,  ['form_params'=>$parameters]);
      }
    } catch (GuzzleHttp\Exception\BadResponseException $e) {
      $response = $e->getResponse();
    }
    $content = $response->getBody()->getContents();
    $status = $response->getStatusCode();
    if($status>399){
      \Log::info('GuzPostError '.$rand_code.': '.$status.' - '.json_encode($content));
    } else {
      \Log::info('GuzPostResponse '.$rand_code.': '.$status.' - '.json_encode($content));
    }
    return json_decode($content, true);
  }

  public static function curlGet($url, $action, $parameters, $headers = []) {
    $url .= '/'.$action;
    $rand_code = rand(100000, 999999);
    if(!isset($headers['Content-Type'])){
      $headers['Content-Type'] = 'application/json';
    }
    \Log::info('CurlGetQuery '.$rand_code.': '.$url.' - '.json_encode($parameters));
    $count = 0;
    foreach($parameters as $parameter_key => $parameter_val){
      if($count==0){
        $url .= '?';
      } else {
        $url .= '&';
      }
      $url .= $parameter_key.'='.$parameter_val;
      $count++;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    if(isset($headers['CURLOPT_USERPWD'])){
      curl_setopt($ch, CURLOPT_USERPWD, $headers['CURLOPT_USERPWD']);
      unset($headers['CURLOPT_USERPWD']);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, false);
    $new_headers = [];
    foreach($headers as $header_key => $header_value){
      $new_headers[] = $header_key.': '.$header_value;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $new_headers);
    $content = curl_exec($ch); 
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($error_msg = curl_error($ch)) {
      \Log::info('CurlGetError '.$rand_code.': '.$status.' - '.json_encode($error_msg));
      curl_close($ch);
      return NULL;
    } else {
      \Log::info('CurlGetResponse '.$rand_code.': '.$status.' - '.json_encode($content));
    }
    curl_close($ch);
    return $content;
  }

  public static function curlPost($url, $action, $parameters, $headers = []) {
    $url .= '/'.$action;
    $rand_code = rand(100000, 999999);
    if(!isset($headers['Content-Type'])){
      $headers['Content-Type'] = 'application/json';
    }
    \Log::info('CurlPostQuery '.$rand_code.': '.$url.' - '.json_encode($parameters));

    $new_parameters = '';
    foreach($parameters as $parameter_key => $parameter_value){
      $new_parameters .= $parameter_key.'='.$parameter_value;
    }
    \Log::info($new_parameters);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    if(isset($headers['CURLOPT_USERPWD'])){
      curl_setopt($ch, CURLOPT_USERPWD, $headers['CURLOPT_USERPWD']);
      unset($headers['CURLOPT_USERPWD']);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $new_parameters);
    $new_headers = [];
    foreach($headers as $header_key => $header_value){
      $new_headers[] = $header_key.': '.$header_value;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $new_headers);
    $content = curl_exec($ch); 
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if($error_msg = curl_error($ch)) {
      \Log::info('CurlPostError '.$rand_code.': '.$status.' - '.json_encode($error_msg));
      curl_close($ch);
      return NULL;
    } else {
      \Log::info('CurlPostResponse '.$rand_code.': '.$status.' - '.json_encode($content));
    }
    curl_close($ch);
    return $content;
  }

}