<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {
    
    protected $table = 'nodes';
    public $timestamps = true;

    public $translatedAttributes = ['singular','plural'];
    protected $fillable = ['singular','plural'];
    protected $dates = ['deleted_at'];

    use \Dimsav\Translatable\Translatable;
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /* Creating rules */
    public static $rules_create = array(
        'name'=>'required',
        'location'=>'required',
        'type'=>'required',
        'multilevel'=>'required',
        'dynamic'=>'required',
        'customized'=>'required',
        'translation'=>'required',
        'soft_delete'=>'required',
        'singular'=>'required',
        'plural'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'id'=>'required',
        'name'=>'required',
        'location'=>'required',
        'type'=>'required',
        'multilevel'=>'required',
        'dynamic'=>'required',
        'customized'=>'required',
        'translation'=>'required',
        'soft_delete'=>'required',
        'singular'=>'required',
        'plural'=>'required',
    );

    public function children() {
        return $this->hasMany('Solunes\Master\App\Node', 'parent_id', 'id');
    }

    public function parent() {
        return $this->belongsTo('Solunes\Master\App\Node', 'parent_id');
    }

    public function fields() {
        return $this->hasMany('Solunes\Master\App\Field', 'parent_id')->orderBy('order', 'ASC')->with('translations');
    }

    public function node_extras() {
        return $this->hasMany('Solunes\Master\App\NodeExtra', 'parent_id')->orderBy('order', 'ASC');
    }

    public function node_action_fields() {
        return $this->hasMany('Solunes\Master\App\NodeExtra', 'parent_id')->where('type', 'action_field');
    }

    public function node_action_nodes() {
        return $this->hasMany('Solunes\Master\App\NodeExtra', 'parent_id')->where('type', 'action_node');
    }

    public function node_graphs() {
        return $this->hasMany('Solunes\Master\App\NodeExtra', 'parent_id')->whereIn('type', ['graph','parent_graph']);
    }

    public function filters() {
        return $this->hasMany('Solunes\Master\App\Filter', 'node_id');
    }

    public function indicators() {
        return $this->hasMany('Solunes\Master\App\Indicator', 'node_id');
    }

    public function getLangFolderAttribute() {
        if($this->location=='package'||$this->location=='app'){
          $folder = 'master';
        } else {
          $folder = $this->location;
        }
        return $folder;
    }

}