<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class NodeExtra extends Model {
	
	protected $table = 'node_extras';
	public $timestamps = false;
    protected $fillable = ['parent_id', 'order', 'display', 'type', 'parameter', 'value_array'];

	/* Creating rules */
	public static $rules_create = array(
		'display'=>'required',
		'type'=>'required',
		'parameter'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'display'=>'required',
		'type'=>'required',
		'parameter'=>'required',
	);

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node', 'parent_id');
    }

}