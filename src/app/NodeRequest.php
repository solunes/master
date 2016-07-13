<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class NodeRequest extends Model {
    
    protected $table = 'node_requests';
    public $timestamps = false;

    /* Creating rules */
    public static $rules_create = array(
        'action'=>'required',
        'col'=>'required',
    );

    /* Updating rules */
    public static $rules_edit = array(
        'action'=>'required',
        'col'=>'required',
    );

    public function node() {
        return $this->belongsTo('Solunes\Master\App\Node', 'parent_id');
    }

}