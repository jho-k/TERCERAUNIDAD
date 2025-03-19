<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    use HasFactory;

    protected $table = 'diagnostico';
    protected $primaryKey = 'id_diagnostico';
    public $timestamps = true;

    protected $fillable = [
        'id_historial',
        'descripcion',
        'fecha_creacion'
    ];

    public function historialClinico()
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial');
    }
}
