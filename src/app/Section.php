<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {
	
	protected $table = 'sections';
	public $timestamps = true;
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'page_id'=>'required',
		'node_id'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'page_id'=>'required',
		'node_id'=>'required',
	);

	public function page() {
	    return $this->belongsTo('Solunes\Master\App\Page');
	}

	public function node() {
	    return $this->belongsTo('Solunes\Master\App\Node');
	}

	public function setPageIdAttribute($value) {
        if(!isset($this->attributes['name'])){
        	\App::setLocale('es');
            if($page = \Solunes\Master\App\Page::find($value)){
                $page_name = $page->name;
            } else {
                $page_name = '-';
            }
            $this->attributes['name'] = $page_name;
        }
        $this->attributes['page_id'] = $value;
	}

}