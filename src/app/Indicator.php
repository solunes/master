<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model {
    
    protected $table = 'indicators';
    public $timestamps = true;

    protected $fillable = ['node_id'];
    protected $dates = ['deleted_at'];

    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
        'node_id'=>'required',
        'name'=>'required',
        //'user_id'=>'required',
        'graph_type'=>'required',
        'default_date'=>'required',
        'color'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'node_id'=>'required',
        'name'=>'required',
        //'user_id'=>'required',
        'graph_type'=>'required',
        'default_date'=>'required',
        'color'=>'required',
    );

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function field() {
        return $this->belongsTo('Solunes\Master\App\Field');
    }

    public function indicator_users() {
        return $this->hasMany('Solunes\Master\App\IndicatorUser', 'parent_id');
    }

    public function my_indicator_value() {
        if(auth()->check()){
            $user_id = auth()->user()->id;
        } else {
            $user_id = 0;
        }
        return $this->hasOne('Solunes\Master\App\IndicatorUser', 'parent_id')->where('user_id', $user_id);
    }

}