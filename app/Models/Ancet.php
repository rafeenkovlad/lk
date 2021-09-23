<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Ancet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'about', 'price', 'contact'];

    /*
     * получить город юзера
     */


    public function img()
    {
        return $this->hasOne('App\Models\Img', 'user_id');
    }
}