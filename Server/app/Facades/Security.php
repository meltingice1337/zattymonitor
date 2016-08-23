<?php
/**
 * Created by PhpStorm.
 * User: n0impossible
 * Date: 6/14/15
 * Time: 1:47 PM
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class Security extends Facade{
    protected static function getFacadeAccessor() { return 'security'; }
}