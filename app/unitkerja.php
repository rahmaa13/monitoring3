<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class unitkerja extends Model
{
    protected $primaryKey = 'id_unit';

    protected $table = 'unitkerjas';

    public function pbbj() {
    	return $this->belongsTo('App\pbbj');
    }
}