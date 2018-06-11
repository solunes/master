<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldRelation extends Model {
	
	protected $table = 'field_relations';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'related_field_code'=>'required',
		'name'=>'required',
		'label'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'related_field_code'=>'required',
		'name'=>'required',
		'label'=>'required',
	);

    public function related_field() {
        return $this->belongsTo('Solunes\Master\App\Field', 'related_field_code');
    }

    public function field() {
        return $this->belongsTo('Solunes\Master\App\Field', 'parent_id');
    }

}