<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    protected $fillable = ['name', 'description', 'image', 'slug', 'is_active', 'parent_id'];
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'category_id');
    }
   
   
   
   
   
   
    // public static function categoriesRecursive($parentId = 0, $id = 0, $text = '')
    // {
    //     $result = []; // Array to store the result
    //     $data = static::where('parent_id', '=', $parentId)->get(); // Filter categories based on parent_id
    
    //     foreach ($data as $value) {
    //         if ($value->parent_id == $id) {
    //             $category = [
    //                 'id' => $value->id,
    //                 'name' => $text . $value->name,
    //                 'parent_id' => $value->parent_id,
    //                 'is_selected' => $parentId == $value->id, // Mark the selected category
    //             ];
    
    //             // No recursion, just add children manually if needed
    //             $children = static::where('parent_id', '=', $value->id)->get();
    //             if (!empty($children)) {
    //                 $category['children'] = $children;
    //             }
    
    //             $result[] = $category; // Add category to the result
    //         }
    //     }
    
    //     return $result;
    // }
    
    
}
