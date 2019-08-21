<?php


namespace App\Models\Traits;
use \Ramsey\Uuid\uuid as RamseyUuid;


trait Uuid
{

    public static function boot() {

        parent::boot();
        static::creating(function($obj){
            $obj->id =  RamseyUuid::uuid4();

        });
    }

}
