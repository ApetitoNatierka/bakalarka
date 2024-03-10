<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    public function get_id() {
        return $this->attributes['id'];
    }

    public function get_name() {
        return $this->attributes['name'];
    }

    public function get_description() {
        return $this->attributes['description'];
    }

    public function get_price() {
        return $this->attributes['price'];
    }
}
