<?php
/**
 * Created by PhpStorm.
 * User: Akshay.Mohan
 * Date: 15/11/2018
 * Time: 10:54 AM
 */

namespace App\Modules\URLShortener\Models;

use Illuminate\Database\Eloquent\Model;

class ShortURL extends Model
{
    protected $table ='short_urls';

    public $timestamps = true;
}
