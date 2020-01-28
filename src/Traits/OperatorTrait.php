<?php

namespace glorifiedking\BusTravel\Traits;
use DB,Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

trait OperatorTrait {

        public static function getTableName()
       {
           return with(new static)->getTable();
       }

       /**
        * The "booting" method of the model.
        *
        * @return void
        */
       protected static function boot()
       {
           parent::boot();

           static::bootOperator();
       }

       /**
        * Boot the global scope
        */
       protected static function bootOperator()
       {
           static::addGlobalScope('operator', function (Builder $builder) {
               $table = self::getTableName();
           });

           static::saving(function (Model $model) {

               if (!isset($model->operator_id)) {
                   $model->operator_id = auth()->user()->operator_id;
               }
           });
       }
}
