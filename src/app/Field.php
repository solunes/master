<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {
    
    protected $table = 'fields';
    public $timestamps = true;
    protected $appends = ['options','extras','final_label'];

    public $translatedAttributes = ['label','tooltip','message'];
    protected $fillable = ['label','tooltip','message'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
        'parent_id'=>'required',
        'order'=>'required',
        'name'=>'required',
        'trans_name'=>'required',
        'type'=>'required',
        'display_list'=>'required',
        'display_item'=>'required',
        'relation'=>'required',
        'multiple'=>'required',
        'translation'=>'required',
        'required'=>'required',
        'new_row'=>'required',
        'preset'=>'required',
        'label'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'order'=>'required',
        'name'=>'required',
        'trans_name'=>'required',
        'type'=>'required',
        'display_list'=>'required',
        'display_item'=>'required',
        'relation'=>'required',
        'multiple'=>'required',
        'translation'=>'required',
        'required'=>'required',
        'new_row'=>'required',
        'preset'=>'required',
        'label'=>'required',
    );

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Node');
    }

    public function field_extras() {
        return $this->hasMany('Solunes\Master\App\FieldExtra', 'parent_id');
    }

    public function getExtrasAttribute() {
        $extras = $this->field_extras->lists('value','type')->toArray();
        // Corrección a Extra campos en sub tabla
        if($this->child_table){
            $extras['cols'] = 12;
            $extras['disabled'] = 1;
            if($this->name==$this->child_table.'_count'){
                $class = 'calculate-count';
            } else {
                $class = 'calculate-total';
            }
            $extras['class'] = $class;
        }
        return $extras;
    }

    public function field_conditionals() {
        return $this->hasMany('Solunes\Master\App\FieldConditional', 'parent_id');
    }

    public function field_options() {
        return $this->hasMany('Solunes\Master\App\FieldOption', 'parent_id')->with('translations');
    }

    public function field_options_active() {
        return $this->hasMany('Solunes\Master\App\FieldOption', 'parent_id')->where('active', 1)->with('translations');
    }

    public function getFinalLabelAttribute() {
        if($this->label){
            return $this->label;
        } else {
            return NULL;
        }
    }

    public function getOptionsAttribute() {
        $return = [];
        if($this->relation){
            if($subnode = \Solunes\Master\App\Node::where('name', str_replace('_', '-', $this->value))->first()){
                $submodel = \FuncNode::node_check_model($subnode);
                if(config('solunes.store')&&config('store.get_options_relation')&&$this->relation_cond){
                    $submodel = \CustomStore::get_options_relation($submodel, $this, $subnode, request()->segment(5));
                }
                if(config('solunes.get_options_relation')&&$this->relation_cond){
                    $submodel = \CustomFunc::get_options_relation($submodel, $this, $subnode, request()->segment(5));
                }
                $return = $return+$submodel->get()->sortBy('name')->lists('name', 'id')->toArray();
            }
        } else if($this->type=='select'||$this->type=='radio'||$this->type=='checkbox'){
            foreach($this->field_options_active as $item){
                $return[$item->name] = $item->label;
            }
        }
        return $return;
    }

    public function getChildFieldsAttribute() {
        if($this->type=='subchild'){
            $return = \Solunes\Master\App\Node::where('name', $this->value)->first()->fields()->displayItem(['excel','show'])->whereNotIn('name', ['id', 'parent_id'])->orderBy('order','ASC')->orderBy('id','ASC')->get();
        } else if($this->type=='child'){
            $return = \Solunes\Master\App\Node::where('name', $this->value)->first()->fields()->displayList(['show'])->whereNotIn('name', ['id', 'parent_id'])->orderBy('order','ASC')->orderBy('id','ASC')->get();
        } else {
            $return = NULL;
        }
        return $return;
    }

    public function getChildFieldOptionsAttribute() {
        if($this->type=='child'){
            $display_fields = ['show'];
            $node = \Solunes\Master\App\Node::where('name', $this->value)->first();
            $field_ops = $node->fields()->displayList($display_fields)->has('field_options')->with('field_options')->get();
            $return = [];
            foreach($field_ops as $field_op){
                foreach($field_op->field_options as $field_option){
                    $return[$field_op->name][$field_option->name] = $field_option->label;
                }
            }
        } else {
            $return = NULL;
        }
        return $return;
    }

    public function scopeCheckPermission($query) {
        return $query->where(function ($subquery) {
            $subquery->whereNull('permission');
            if(auth()->check()){
                $permissions = auth()->user()->getPermission();
                if(!empty($permissions)){
                    $permissions = $permissions->toArray();
                    $subquery->orWhereIn('permission', $permissions);
                }
            }
        });
    }

    public function scopeFillables($query) {
        return $query->whereNotIn('type', ['title', 'content', 'custom', 'child', 'subchild', 'field']);
    }

    public function scopeFiles($query) {
        return $query->whereIn('type', ['image','file']);
    }

    public function scopeMaps($query) {
        return $query->where('type', 'map');
    }

    public function scopeBarcode($query) {
        return $query->where('type', 'barcode');
    }

    public function scopeRequired($query) {
        return $query->where('required', 1);
    }

    public function scopePreset($query) {
        return $query->where('preset', 1);
    }

    public function scopeMultiple($query) {
        return $query->where('multiple', 1);
    }

    public function scopeTranslation($query) {
        return $query->where('translation', 1);
    }

    public function scopeFilters($query) {
        $type_array = ['select','relation','radio','checkbox','barcode','date','string','text','field'];
        return $query->whereIn('type', $type_array);
    }

    public function scopeDisplayItem($query, $value) {
        if(is_array($value)){
            return $query->whereIn('display_item', $value);
        } else {
            return $query->where('display_item', $value);
        }
    }

    public function scopeDisplayList($query, $value) {
        if(is_array($value)){
            return $query->whereIn('display_list', $value);
        } else {
            return $query->where('display_list', $value);
        }
    }

}