<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model {
	
	protected $table = 'variables';
	public $timestamps = true;

    public $translatedAttributes = ['value'];
    protected $fillable = ['name','value'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'type'=>'required',
		'value'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'name'=>'required',
		'type'=>'required',
		'value'=>'required',
	);

    public function site() {
        return $this->belongsTo('Solunes\Master\App\Site');
    }

}