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
        'type'=>'required',
        'data'=>'required',
        'result'=>'required',
        'color'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'node_id'=>'required',
        'name'=>'required',
        'type'=>'required',
        'data'=>'required',
        'result'=>'required',
        'color'=>'required',
    );

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function indicator_alerts() {
        return $this->hasMany('Solunes\Master\App\IndicatorAlert', 'parent_id');
    }

    public function indicator_graphs() {
        return $this->hasMany('Solunes\Master\App\IndicatorGraph', 'parent_id');
    }

    public function indicator_values() {
        return $this->hasMany('Solunes\Master\App\IndicatorValue', 'parent_id')->orderBy('date','DESC');
    }

    public function getValueAttribute() {
        if($value = $this->indicator_values->first()){
            return $value->value;
        } else {
            return 0;
        }
    }

}