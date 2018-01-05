<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Item extends Model {

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function isValid($data = array())
    {
        return Validator::make($data,  array(
            'id'      => 'exists:items|sometimes|required',
            'title'   => 'string|between:1,200|sometimes|required',
            'user_id' => 'exists:users,id|sometimes|required',
        ))->passes();
    }
}
