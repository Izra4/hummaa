<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'kategori_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Relationships
    public function materials()
    {
        return $this->hasMany(Material::class, 'kategori_id');
    }

    // Scopes
    public function scopeWithMaterialCount($query)
    {
        return $query->withCount('materials');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('materials');
    }
}