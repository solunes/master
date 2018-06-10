<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class IndicatorGraph extends Model {
	
	protected $table = 'indicator_graphs';
	public $timestamps = false;

	/* Creating rules */
	public static $rules_create = array(
		'graph'=>'required',
		'meta'=>'integer',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'graph'=>'required',
		'meta'=>'integer',
	);

    public function indicator() {
        return $this->belongsTo('Solunes\Master\App\Indicator', 'parent_id');
    }

    public function indicator_graph_users() {
        return $this->belongsToMany('App\User','indicator_graph_users');
    }

    public function setGraphAttribute($value) {
        $this->attributes['name'] = $this->indicator->name;
        $this->attributes['graph'] = $value;
    }

}