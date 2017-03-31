<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model {
    
    protected $table = 'pages';
    public $timestamps = true;

    public $translatedAttributes = ['slug','name','meta_title','meta_description'];
    protected $fillable = ['slug','name','meta_title','meta_description'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
        'type'=>'required',
        'name'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'type'=>'required',
        'name'=>'required',
    );

    public function site() {
        return $this->belongsTo('Solunes\Master\App\Site');
    }

    public function children() {
        return $this->hasMany('Solunes\Master\App\Page', 'parent_id', 'id')->orderBy('order','ASC');
    }

}
