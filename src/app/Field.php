<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    
    protected $table = 'fields';
    public $timestamps = true;
    protected $appends = ['options','extras','final_label'];

    public $translatedAttributes = ['label'];
    protected $fillable = ['label'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
        'parent_id'=>'required',
        'name'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'name'=>'required',
    );

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function field_extras() {
        return $this->hasMany('Solunes\Master\App\FieldExtra', 'parent_id');
    }

    public function getExtrasAttribute() {
        return $this->field_extras()->lists('value','type')->toArray();
    }

    public function getFinalLabelAttribute() {
        if($this->label){
            return $this->label;
        } else {
            return NULL;
        }
    }

    public function field_conditionals() {
        return $this->hasMany('Solunes\Master\App\FieldConditional', 'parent_id');
    }

    public function getOptionsAttribute() {
        if($this->required){
            $return = [];
        } else {
            $return[0] = ' ';
        }
        if($this->type=='select'){
            $explode = explode("','", substr($this->value, 1, -1));
            foreach($explode as $item){
                $return[$item] = trans('admin.'.$item);
            }
        } else if($this->type=='relation'||$this->type=='field'){
            if($subnode = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $this->value))->first()){
                $submodel = $subnode->model;
                if($this->type=='relation'){
                    if($this->value=='section'){
                        $return = $return+$submodel::where('node_id', $this->parent_id)->get()->lists('name', 'id')->toArray();
                    } else {
                        $return = $return+$submodel::get()->lists('name', 'id')->toArray();
                    }
                } else {
                    $return = $return+$submodel::lists('name', 'id')->toArray();
                }
            }
        }
        return $return;
    }

    public function getChildFieldsAttribute() {
        if($this->type=='subchild'){
            $return = \Solunes\Master\App\Node::where('name', $this->value)->first()->fields()->where('display_item', '!=', 'none')->whereNotIn('name', ['id', 'parent_id'])->orderBy('order','ASC')->orderBy('id','ASC')->get();
        } else {
            $return = NULL;
        }
        return $return;
    }

}