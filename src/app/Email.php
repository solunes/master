<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model {
	
	protected $table = 'emails';
	public $timestamps = true;

    public $translatedAttributes = ['title', 'content'];
    protected $fillable = ['name' ,'title', 'content'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
		'title'=>'required',
		'content'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'name'=>'required',
		'title'=>'required',
		'content'=>'required',
	);
	
    public function site() {
        return $this->belongsTo('Solunes\Master\App\Site');
    }

}