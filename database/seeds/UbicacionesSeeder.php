<?php

use App\Ciudades;
use App\Departamentos;
use App\Pais;
use Illuminate\Database\Seeder;

class UbicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paises = [
            ['codigo' => 'CO', 'nombre' => 'Colombia'],
            ['codigo' => 'US', 'nombre' => 'Estados Unidos']
        ];

        $departamentosCo = [
            ['codigo' => 'AMA', 'nombre' => 'Amazonas'],
            ['codigo' => 'ANT', 'nombre' => 'Antioquia'],
            ['codigo' => 'ARA', 'nombre' => 'Arauca'],
            ['codigo' => 'ATL', 'nombre' => 'Atlántico'],
            ['codigo' => 'BOG', 'nombre' => 'Bogotá, D.C'],
            ['codigo' => 'BOL', 'nombre' => 'Bolívar'],
            ['codigo' => 'BOY', 'nombre' => 'Boyacá'],
            ['codigo' => 'CAL', 'nombre' => 'Caldas'],
            ['codigo' => 'CAQ', 'nombre' => 'Caquetá'],
            ['codigo' => 'CAS', 'nombre' => 'Casanare'],
            ['codigo' => 'CAU', 'nombre' => 'Cauca'],
            ['codigo' => 'CES', 'nombre' => 'Cesar'],
            ['codigo' => 'CHO', 'nombre' => 'Chocó'],
            ['codigo' => 'COR', 'nombre' => 'Córdoba'],
            ['codigo' => 'CUN', 'nombre' => 'Cundinamarca'],
            ['codigo' => 'GUA', 'nombre' => 'Guainía'],
            ['codigo' => 'GUV', 'nombre' => 'Guaviare'],
            ['codigo' => 'HUI', 'nombre' => 'Huila'],
            ['codigo' => 'LAG', 'nombre' => 'La Guajira'],
            ['codigo' => 'MAG', 'nombre' => 'Magdalena'],
            ['codigo' => 'MET', 'nombre' => 'Meta'],
            ['codigo' => 'NAR', 'nombre' => 'Nariño'],
            ['codigo' => 'NSA', 'nombre' => 'Norte de Santander'],
            ['codigo' => 'PUT', 'nombre' => 'Putumayo'],
            ['codigo' => 'QUI', 'nombre' => 'Quindío'],
            ['codigo' => 'RIS', 'nombre' => 'Risaralda'],
            ['codigo' => 'SAP', 'nombre' => 'San Andrés y Providencia'],
            ['codigo' => 'SAN', 'nombre' => 'Santander'],
            ['codigo' => 'SUC', 'nombre' => 'Sucre'],
            ['codigo' => 'TOL', 'nombre' => 'Tolima'],
            ['codigo' => 'VAC', 'nombre' => 'Valle del Cauca'],
            ['codigo' => 'VAU', 'nombre' => 'Vaupés'],
            ['codigo' => 'VID', 'nombre' => 'Vichada']
        ];

        $ciudadesVAC = [
            ['codigo' => 'CAL', 'nombre' => 'Cali'],
            ['codigo' => 'PAL', 'nombre' => 'Yumbo'],
            ['codigo' => 'JAM', 'nombre' => 'Jamundí']
        ];

        $ciudadesBOG = [
            ['codigo' => 'BOG', 'nombre' => 'Bogotá'],
            ['codigo' => 'SOA', 'nombre' => 'Soacha'],
            ['codigo' => 'CHI', 'nombre' => 'Chía']
        ];

        foreach ($paises as $pais) {
            $nuevoPais = Pais::updateOrCreate($pais);

            if ($pais['codigo'] == 'CO') {
                foreach ($departamentosCo as $departamento) {
                    $departamento['pais_id'] = $nuevoPais->id;
                    $nuevoDepartamento = Departamentos::updateOrCreate($departamento);

                    $ciudadesVar = 'ciudades' . $departamento['codigo'];

                    if (isset(${$ciudadesVar})) {
                        foreach (${$ciudadesVar} as $ciudad) {
                            $ciudad['departamento_id'] = $nuevoDepartamento->id;
                            Ciudades::updateOrCreate($ciudad);
                        }
                    }
                }
            }
        }
    }
}
