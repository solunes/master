<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class FieldOption extends Model {
    
    protected $table = 'field_options';
    public $timestamps = false;

    public $translatedAttributes = ['label'];
    protected $fillable = ['name','label'];

    use \Dimsav\Translatable\Translatable;

    /* Creating rules */
    public static $rules_create = array(
        'name'=>'required',
        'label'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'name'=>'required',
        'label'=>'required',
    );

    public function field() {
        return $this->belongsTo('Solunes\Master\App\Field', 'parent_id');
    }

}