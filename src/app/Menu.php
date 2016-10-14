<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
  
    protected $table = 'menus';
    public $timestamps = true;

    public $translatedAttributes = ['name', 'link'];
    protected $fillable = ['type', 'menu_type', 'permission', 'parent_id', 'level', 'icon', 'name', 'link'];
    protected $appends = ['real-link', 'final-link'];
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

    public function children() {
        return $this->hasMany('Solunes\Master\App\Menu', 'parent_id', 'id')->orderBy('order','ASC');
    }

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Menu', 'parent_id', 'id');
    }

    public function page() {
        return $this->belongsTo('Solunes\Master\App\Page');
    }

    public function setLevelAttribute($value){
        if(!isset($this->attributes['order'])){
            if(\Solunes\Master\App\Menu::where("level", $value)->count()>0){
                $order = \Solunes\Master\App\Menu::where("level", $value)->orderBy("order", "DESC")->first()->order;
            } else {
                $order = 0;
            }
            $order = $order+1;
            $this->attributes['order'] = $order;
        }
        $this->attributes['level'] = $value;
    }

    /*public function setOrderAttribute($value){
        if($value==''||$value==NULL){
            $level = $this->attributes['level'];
            if(\Solunes\Master\App\Menu::where("level", $level)->count()>0){
                $order = \Solunes\Master\App\Menu::where("level", $level)->orderBy("order", "DESC")->first()->order;
            } else {
                $order = 0;
            }
            $order = $order+1;
            $this->attributes['order'] = $order;
        }
    }*/

    public function getRealLinkAttribute(){
      if($this->page){
        return url($this->page->translate()->slug);
      } else if(($this->type=='normal'&&$this->type=='external')||$this->translate()->link){
        return $this->translate()->link;
      } else if($this->menu_type=='admin') {
        return NULL;
      } else {
        return '#';
      }
    }

    public function getFinalLinkAttribute(){
      if($this->page){
        return '<a href="'.url($this->page->translate()->slug).'">'.$this->name.'</a>';
      } else if($this->type=='external'){
        return '<a target="_blank" href="'.$this->translate()->link.'">'.$this->name.'</a>';
      } else {
        return $this->name;
      }
    }

}