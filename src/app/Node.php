<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {
	
	protected $table = 'nodes';
	public $timestamps = true;

    public $translatedAttributes = ['singular','plural'];
    protected $fillable = ['singular','plural'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

	/* Creating rules */
	public static $rules_create = array(
		'name'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'id'=>'required',
		'name'=>'required',
	);

    public function children() {
        return $this->hasMany('Solunes\Master\App\Node', 'parent_id', 'id');
    }

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Node', 'parent_id');
    }

    public function fields() {
        return $this->hasMany('Solunes\Master\App\Field', 'parent_id')->where('type', '!=', 'site')->orderBy('order','ASC')->orderBy('id','ASC');
    }

    public function node_requests() {
        return $this->hasMany('Solunes\Master\App\NodeRequest', 'parent_id')->orderBy('order', 'ASC');
    }

    public function node_extras() {
        return $this->hasMany('Solunes\Master\App\NodeExtra', 'parent_id')->orderBy('order', 'ASC');
    }

    public function deadline() {
        return $this->hasOne('App\Deadline');
    }

}