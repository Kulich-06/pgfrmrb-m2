<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clotch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'size', 'img', 'category_id', 'color_id', 'season_id', 'user_id'];    
    public function category(){ 
        return $this->belongsTo(Category::class); 
        
    }
    public function color(){ 
        return $this->belongsTo(Color::class); 
    }
    public function season(){ 
        return $this->belongsTo(Season::class); 
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}
