<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model {
	
	protected $table = 'filters';
	public $timestamps = false;
    protected $fillable = ['node_id','type','subtype','parameter'];

	/* Creating rules */
	public static $rules_create = array(
        'category'=>'required',
        'display'=>'required',
        'type'=>'required',
        'subtype'=>'required',
        'node_id'=>'required',
        'parameter'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
        'id'=>'required',
        'category'=>'required',
        'display'=>'required',
        'type'=>'required',
        'subtype'=>'required',
        'node_id'=>'required',
        'parameter'=>'required',
	);

    public function scopeCheckCategory($query, $category) {
        return $query->where('category', $category);
    }

    public function scopeCheckDisplay($query) {
        return $query->where(function ($subquery) {
            if(auth()->check()){
                $subquery->where('display', 'all')->orWhere('user_id', auth()->user()->id);
            } else {
                $subquery->where('display', 'all');
            }
        });
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node', 'node_id');
    }

}