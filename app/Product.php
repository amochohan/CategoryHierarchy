<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    public function parent()
    {
        return $this->hasOne('App\Product', 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Product', 'parent_id', 'id');
    }

}
