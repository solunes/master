<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model {
  
    protected $table = 'menus';
    public $timestamps = true;

    public $translatedAttributes = ['name', 'link'];
    protected $fillable = ['type', 'menu_type', 'permission', 'order', 'parent_id', 'level', 'icon', 'name', 'link'];
    protected $appends = ['real-link', 'final-link'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
      'menu_type'=>'required',
      'active'=>'required',
      'type'=>'required',
      'name'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
      'id'=>'required',
      'menu_type'=>'required',
      'active'=>'required',
      'type'=>'required',
      'name'=>'required',
    );

    public function scopeMenuQuery($query, $type, $level, $menu_includes = NULL) {
        if(!$menu_includes){
            $menu_includes = ['translations', 'page', 'page.translations', 'children', 'children.translations', 'children.page', 'children.page.translations', 'children.children', 'children.children.translations', 'children.children.page', 'children.children.page.translations'];
        }
        return $query->where('menu_type', $type)->where('level', $level)->with($menu_includes)->orderBy('order','ASC');
    }

    public function scopeIsActive($query) {
        return $query->where('active', 1);
    }

    public function children() {
        return $this->hasMany('Solunes\Master\App\Menu', 'parent_id', 'id')->where('active', 1)->orderBy('order','ASC');
    }

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Menu', 'parent_id', 'id');
    }

    public function page() {
        return $this->belongsTo('Solunes\Master\App\Page');
    }

    public function getRealLinkAttribute(){
      if($this->page){
        return url($this->page->translate()->slug);
      } else if($this->type=='external'){
        return $this->translate()->link;
      } else if($this->type=='normal'&&$this->translate()&&$this->translate()->link&&$this->translate()->link!='#'){
        return url($this->translate()->link);
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