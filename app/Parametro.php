<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametro extends Model
{
    use SoftDeletes;

    protected $table = 'parametros';

    protected $fillable = [
        'llave',
        'valor'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Obtener un parámetro por su llave
     *
     * @param string $llave
     * @return Parametro|null
     */
    public static function obtenerPorLlave($llave)
    {
        return self::where('llave', $llave)->first();
    }

    /**
     * Obtener o crear el parámetro TASA_USURA
     *
     * @return Parametro
     */
    public static function obtenerTasaUsura()
    {
        $parametro = self::obtenerPorLlave('TASA_USURA');

        if (!$parametro) {
            $parametro = self::create([
                'llave' => 'TASA_USURA',
                'valor' => '0.00'
            ]);
        }

        return $parametro;
    }

    /**
     * Actualizar el valor del parámetro TASA_USURA
     *
     * @param float|string $nuevoValor
     * @return Parametro
     */
    public static function actualizarTasaUsura($nuevoValor)
    {
        $parametro = self::obtenerTasaUsura();
        $parametro->valor = $nuevoValor;
        $parametro->save(); // Esto automáticamente actualiza updated_at

        return $parametro;
    }

    /**
     * Verificar si TASA_USURA necesita actualización (más de 30 días)
     *
     * @return bool
     */
    public static function tasaUsuraNecesitaActualizacion()
    {
        $parametro = self::obtenerTasaUsura();

        if (!$parametro->updated_at) {
            return true;
        }

        $diasDesdeActualizacion = now()->diffInDays($parametro->updated_at);

        return $diasDesdeActualizacion > 30;
    }

    /**
     * Obtener el valor de TASA_USURA como número
     *
     * @return float
     */
    public static function obtenerValorTasaUsura()
    {
        $parametro = self::obtenerTasaUsura();
        return (float) $parametro->valor;
    }
}
