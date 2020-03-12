<?php 

namespace Solunes\Master\App\Helpers;

use Image;
use Storage;

class Asset {

    public static function get_image($folder, $code, $file, $class = 'img-responsive') {
        $response = Asset::get_image_path($folder, $code, $file);
        if($response){
            return '<img src="'.$response.'" class="'.$class.'" />';
        } else {
            return false;
        }
    }

    public static function get_image_path($folder, $code, $file) {
        $path = $folder.'/'.$code.'/'.$file;
        if(config('solunes.storage_webp_enable')){
            $final_path = \Asset::get_webp_image_path($path);
        } else {
            if(config('filesystems.cloud')=='cloudfront'){
                $final_path = config('filesystems.disks.cloudfront.url').'/'.$path;
            } else {
                $final_path = Storage::url($path);
            }
        }
        if(config('solunes.storage_return_asset')){
            return asset($final_path);
        } else {
            return $final_path;
        }
    }

    public static function get_webp_image_path($source, $force_check_exists = false) {
        $destination_real = $source . '.webp';
        if($force_check_exists||config('solunes.storage_webp_check_exists')){
            $exists = \Storage::exists($destination_real);
        } else {
            $exists = true;
        }
        if(!$exists || config('solunes.storage_webp_regenerate_all')){
            \Asset::upload_webp_image($source);
        }
        if(config('filesystems.cloud')=='cloudfront'){
            $final_path = config('filesystems.disks.cloudfront.url').'/'.$destination_real;
        } else {
            $final_path = Storage::url($destination_real);
        }
        return $final_path;
    }

    public static function get_webp_public_image($source, $real_path = false, $force_check_exists = false) {
        $final_destination = $source;
        if(!$real_path){
            $source = realpath($source);
        } else {
            $replace = realpath('public');
            $final_destination = str_replace($replace, '', $final_destination);
            $final_destination = str_replace('\\', '/', $final_destination);
        }
        $destination_real = $source . '.webp';
        $final_destination .= '.webp';
        if($force_check_exists||config('solunes.storage_webp_check_exists')){
            $exists = file_exists($destination_real);
        } else {
            $exists = true;
        }
        if(!$exists || config('solunes.storage_webp_regenerate_all')){
            \Asset::upload_webp_public_image($source);
        }
        return $final_destination;
    }

    public static function upload_webp_image($source) {
        $storagePath  = \Storage::getDriver()->getAdapter()->getPathPrefix();
        $source = $storagePath.$source;
        if(config('filesystems.cloud')=='cloudfront'||config('solunes.storage_webp_upload_cloud')){
            //$destination = NULL; // TODO: LOCAL FILE PATH TO UPLOAD
        }
        $destination = \Asset::upload_webp_finish($source);
        if(config('filesystems.cloud')=='cloudfront'||config('solunes.storage_webp_upload_cloud')){
            //$new_source = NULL; // TODO: LOCAL FILE PATH UPLOAD TO SERVER
        }
        return $destination;
    }

    public static function upload_webp_public_image($source) {
        $destination = \Asset::upload_webp_finish($source);
        return $destination;
    }

    public static function upload_webp_finish($real_path_source) {
        $destination = $real_path_source . '.webp';
        //\Log::info('Uploading: '.$real_path_source);
        try {
            \WebPConvert::convert($real_path_source, $destination, [
              'fail' => 'original',     // If failure, serve the original image (source). Other options include 'throw', '404' and 'report'
              // 'show-report' => true,  // Generates a report instead of serving an image
              'suppress-warnings' => true,            // if you set to false, make sure that warnings are not echoed out!
              'serve-image' => [
                'headers' => [
                  'cache-control' => true,        
                  'expires' => false,
                  //'expires' => false,
                  //'last-modified' => true,
                  // other headers can be toggled...
                ],
                'cache-control-header' => 'public, max-age=31536000',
              ],
            'convert' => [
              // all convert option can be entered here (ie "quality")
                'quality' => 'auto',
              ],
            ]);
        } catch (Exception $e) {
            \Log::info('Error al generar webpg en: '.$real_path_source);
        }
        return $destination;
    }

    public static function upload_image($file, $folder, $encode = false, $width = NULL, $height = NULL, $extension = NULL) {
        $filename = $folder.'_'.substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
        $image_folder = \Solunes\Master\App\ImageFolder::where('name', $folder)->first();
        if($image_folder&&count($image_folder->image_sizes)>0){
          $size_extension = $image_folder->extension;
          if($extension){
            $size_extension = $extension;
          }
          $new_filename = public_path('tmp/'.$filename.'.'.$size_extension);
          $image_sizes = $image_folder->image_sizes()->get(['code','type','width','height'])->toArray();
          array_push($image_sizes, ['code'=>'mini','type'=>'fit','width'=>150,'height'=>150]);
          $image_quality = config('solunes.image_quality');
          foreach($image_sizes as $size){
            $type = $size['type'];
            if($size['code']=='custom'&&$width){
                $size['width'] = $width;
            }
            if($size['code']=='custom'&&$height){
                $size['height'] = $height;
            }
            if($encode===true){
                if(config('app.system')=='linux'){
                    $encoded_file = utf8_decode(utf8_encode($file));
                } else {
                    $encoded_file = utf8_decode($file);
                }
            } else {
                $encoded_file = $file;
            }
            if($type=='original'){
                $img = \Image::make($encoded_file)->encode($size_extension)->save($new_filename, $image_quality);
            } else {
                try {
                    $img = \Image::make($encoded_file)->$type($size['width'], $size['height'], function ($constraint) {
                        $constraint->aspectRatio();
                        //$constraint->upsize();
                    })->encode($size_extension)->save($new_filename, $image_quality);
                } catch (\Intervention\Image\Exception\NotReadableException $e) {
                    return false;
                }
            }
            $handle = fopen($new_filename, 'r+');
            Storage::put($folder.'/'.$size['code'].'/'.$filename.'.'.$size_extension, $handle);
            if(config('solunes.storage_webp_enable')){
                \Asset::get_webp_image_path($folder.'/'.$size['code'].'/'.$filename.'.'.$size_extension, true);
            }
            fclose($handle);
            unlink($new_filename);
          }
          return $filename.'.'.$size_extension;
        } else {
            return false;
        }
    }

    public static function get_file($folder, $file) {
        $path = $folder.'/'.$file;
        if(config('filesystems.cloud')=='cloudfront'){
            $final_path = config('filesystems.disks.cloudfront.url').'/'.$path;
        } else {
            $final_path = Storage::url($path);
        }
        return $final_path;
    }

    public static function upload_file($file, $folder, $encode = false) {
        if(is_object($file)){
            $file_info = pathinfo($file->getClientOriginalName());
            $filename = time().'_'.\Illuminate\Support\Str::slug($file_info['filename']).'.'.$file->getClientOriginalExtension();
            $file->move('tmp', $filename);
        } else {
            $file_info = pathinfo($file);
            if($encode===true){
                if(config('app.system')=='linux'){
                    $file = utf8_decode(utf8_encode($file_info['dirname'].'/'.$file_info['basename']));
                } else {
                    $file = utf8_decode($file_info['dirname'].'/'.$file_info['basename']);
                }
            } else {
                $file = $file_info['dirname'].'/'.$file_info['basename'];
            }
            $filename = time().'_'.\Illuminate\Support\Str::slug($file_info['filename']).'.'.$file_info['extension'];
            copy($file, 'tmp/'.$filename);
        }
        $handle = fopen('tmp/'.$filename, 'r+');
        Storage::put($folder.'/'.$filename, $handle);
        fclose($handle);
        unlink('tmp/'.$filename);
        return $filename;
    }

    public static function seed($folder, $extension = 'JPG') {
        $files = glob('seed/'.$folder . '/*.'.$extension);
        $file = array_rand($files);
        return $files[$file];
    }

    public static function delete_temp($type = NULL, $folder = NULL, $file = NULL) {
        if($type&&$folder&&$file){
            $temp_files = \Solunes\Master\App\TempFile::where('type', $type)->where('folder', $folder)->where('file', $file)->get();
        } else {
            $date = date('Y-m-d H:i:s', strtotime(' -1 day'));
            $temp_files = \Solunes\Master\App\TempFile::where('created_at', '<', $date)->get();
        }
        if(count($temp_files)>0){
            foreach($temp_files as $temp){
                \Asset::delete_file($temp->type, $temp->folder, $temp->file);
                $temp->delete();
            }
        } else if($type&&$folder&&$file){
            \Asset::delete_file($type, $folder, $file);
        }
        return true;
    }

    public static function delete_saved_files($file_fields, $item) {
        if(count($file_fields)>0){
            foreach($file_fields as $field){
                $file_name = $field->name;
                $folder = $field->field_extras()->where('type','folder')->first()->value;
                if($item->$file_name){
                    if($field->multiple){
                        foreach(json_decode($item->$file_name) as $subfile){
                            \Asset::delete_file($field->type, $folder, $subfile);
                        }
                    } else {
                        \Asset::delete_file($field->type, $folder, $item->$file_name);
                    }
                }
            }
        }
        return true;
    }

    public static function delete_file($type, $folder, $file) {
        if($folder&&$file&&$file!=''&&$file!=NULL){
            if($type=='image'){
                if($image_folder = \Solunes\Master\App\ImageFolder::where('name', $folder)->first()){
                    $image_sizes = $image_folder->image_sizes->toArray();
                    array_push($image_sizes, ['code'=>'mini']);
                    foreach($image_sizes as $size){
                        if(\Storage::has($folder.'/'.$size["code"].'/'.$file)){
                            \Storage::delete($folder.'/'.$size["code"].'/'.$file);
                        }
                    }
                } 
            } else {
                if(\Storage::has($folder.'/'.$file)){
                    \Storage::delete($folder.'/'.$file);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function generate_barcode($node_id, $id = NULL) {
        $value = '777';
        $node_id = str_pad($node_id, 3, '0', STR_PAD_LEFT);
        if($id==NULL){
            $id = rand(0, 999999);
        }
        $id = str_pad($id, 6, '0', STR_PAD_LEFT);
        $value .= $node_id.$id;
        $value_array = str_split($value);
        $odd = true;
        $total = 0;
        foreach($value_array as $val){
            if($odd === true){
                $multiplier = 1;
                $odd = false;
            } else {
                $multiplier = 3;
                $odd = true;
            }
            $total += $val * $multiplier;
        }
        $total = (10 - $total % 10) % 10;
        $keys = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $value .= $keys[$total];
        if(\Asset::check_barcode($node_id, $value)!==0){
            $value = \Asset::generate_barcode($node_id, $id);
        }
        return $value;
    }

    public static function check_barcode($node_id, $barcode) {
        $node = \Solunes\Master\App\Node::find($node_id);
        $model = \FuncNode::node_check_model($node);
        if($item = $model->where('barcode', $barcode)->first()){
            return $item->id;
        } else {
            return 0;
        }
    }

    public static function generate_barcode_image($value) {
        $barcode = new \BarcodeGenerator;
        if(is_numeric($value)&&strlen($value)==13){
            $value = substr($value, 0, 12);
            $type = \BarcodeGenerator::Ean13;
        } else {
            $type = \BarcodeGenerator::Code11;
        }
        $barcode->setText($value);
        $barcode->setType($type);
        $barcode->setThickness(30);
        $barcode->setFontSize(14);
        $barcode->setScale(2);
        $code = $barcode->generate();
        return $code;
    }

    public static function apply_pdf_template($pdf, $title, $custom_options = []) {
        $site = \Solunes\Master\App\Site::first();
        $site_title = $site->name;
        $array = ['title'=>$title,'site_name'=>$site_title];
        $custom_array = [];
        foreach($custom_options as $custom_option_key => $custom_option_val){
            $custom_array[$custom_option_key] = $custom_option_val;
        }
        if(!isset($custom_array['margin-top'])&&config('solunes.pdf_margin_top')){
            $custom_array['margin-top'] = config('solunes.pdf_margin_top');
        }
        if(!isset($custom_array['margin-bottom'])&&config('solunes.pdf_margin_bottom')){
            $custom_array['margin-bottom'] = config('solunes.pdf_margin_bottom');
        }
        if(!isset($custom_array['margin-right'])&&config('solunes.pdf_margin_right')){
            $custom_array['margin-right'] = config('solunes.pdf_margin_right');
        }
        if(!isset($custom_array['margin-left'])&&config('solunes.pdf_margin_left')){
            $custom_array['margin-left'] = config('solunes.pdf_margin_left');
        }
        foreach($custom_array as $custom_option_key => $custom_option_val){
            $pdf = $pdf->setOption($custom_option_key, $custom_option_val);
        }
        if(config('solunes.pdf_custom_data')){
            $array = \CustomFunc::pdf_custom_data($array);
        }
        $array['pdf_options'] = $custom_array;
        if(config('solunes.pdf_header')){
            $header = \view('master::pdf.header', $array);
            $pdf = $pdf->setOption('header-html', $header);
        }
        if(config('solunes.pdf_footer')){
            $header = \view('master::pdf.footer', $array);
            $pdf = $pdf->setOption('footer-html', $header);
        }
        $pdf = $pdf->setPaper(config('solunes.pdf_default_paper'));
        return $pdf;
    }

    public static function upload_pdf_template($pdf, $folder, $file) {
        $temp_file = 'tmp/'.$file.'-'.rand(10000000,99999999).'.pdf';
        $pdf->save($temp_file);
        $file_name = \Asset::upload_file(asset($temp_file), $folder.'-'.$file);
        unlink($temp_file);
        return $file_name;
    }

    public static function getDirContents($dir, $results = array()) {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = $dir . DIRECTORY_SEPARATOR . $value;
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $results = \Asset::getDirContents($path, $results);
            }
        }
        return $results;
    }

    public static function isImageFile($path) {
        $image_array = ['.png','.jpg','jpeg'];
        $path = strtolower($path);
        foreach($image_array as $image_type){
            if(stripos($path,$image_type) !== false){
                return true;
            }
        }
        return false;
    }

}