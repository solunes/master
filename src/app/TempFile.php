<?php

namespace Solunes\Master\App;

use Illuminate\Database\Eloquent\Model;

class TempFile extends Model {
	
	protected $table = 'temp_files';
	public $timestamps = true;
    protected $fillable = ['type', 'folder', 'file'];

	/* Creating rules */
	public static $rules_create = array(
		'type'=>'required',
		'folder'=>'required',
		'file'=>'required',
	);

	/* Updating rules */
	public static $rules_edit = array(
		'type'=>'required',
		'folder'=>'required',
		'file'=>'required',
	);

    public function site() {
        return $this->hasOne('Solunes\Master\App\Site');
    }

}
