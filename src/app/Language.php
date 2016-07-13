<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {
	
	protected $table = 'languages';
	public $timestamps = true;
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'code'=>'required',
		'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'code'=>'required',
		'name'=>'required',
	);

    public function getImagePathAttribute(){
        return asset('assets/flags/'.$this->image);
    }

}