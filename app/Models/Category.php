<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function clotches() 
{ 
 //категория содержит много товаров 
    return $this->hasMany (Clotch::class); 
    
}
protected $fillable=['name'];
}
