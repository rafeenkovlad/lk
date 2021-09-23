<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Img extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'img', 'count_img'];

    protected $table = 'ancetimgs';

    public function ancet()
    {
        return $this->belongsTo('App\Models\Ancet', 'user_id');
    }

}