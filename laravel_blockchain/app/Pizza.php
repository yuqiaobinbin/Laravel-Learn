<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    protected $casts = [
        'toppings' => 'array'
    ];
    private $name;
    private $type;
    private $base;
    private $toppings;
}
