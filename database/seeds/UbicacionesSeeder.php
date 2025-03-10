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
            ['codigo' => null, 'nombre' => 'Amazonas'],
            ['codigo' => null, 'nombre' => 'Antioquia'],
            ['codigo' => null, 'nombre' => 'Arauca'],
            ['codigo' => null, 'nombre' => 'Atlántico'],
            ['codigo' => null, 'nombre' => 'Bogotá, D.C'],
            ['codigo' => null, 'nombre' => 'Bolívar'],
            ['codigo' => null, 'nombre' => 'Boyacá'],
            ['codigo' => null, 'nombre' => 'Caldas'],
            ['codigo' => null, 'nombre' => 'Caquetá'],
            ['codigo' => null, 'nombre' => 'Casanare'],
            ['codigo' => null, 'nombre' => 'Cauca'],
            ['codigo' => null, 'nombre' => 'Cesar'],
            ['codigo' => null, 'nombre' => 'Chocó'],
            ['codigo' => null, 'nombre' => 'Córdoba'],
            ['codigo' => null, 'nombre' => 'Cundinamarca'],
            ['codigo' => null, 'nombre' => 'Guainía'],
            ['codigo' => null, 'nombre' => 'Guaviare'],
            ['codigo' => null, 'nombre' => 'Huila'],
            ['codigo' => null, 'nombre' => 'La Guajira'],
            ['codigo' => null, 'nombre' => 'Magdalena'],
            ['codigo' => null, 'nombre' => 'Meta'],
            ['codigo' => null, 'nombre' => 'Nariño'],
            ['codigo' => null, 'nombre' => 'Norte de Santander'],
            ['codigo' => null, 'nombre' => 'Putumayo'],
            ['codigo' => null, 'nombre' => 'Quindío'],
            ['codigo' => null, 'nombre' => 'Risaralda'],
            ['codigo' => null, 'nombre' => 'San Andrés y Providencia'],
            ['codigo' => null, 'nombre' => 'Santander'],
            ['codigo' => null, 'nombre' => 'Sucre'],
            ['codigo' => null, 'nombre' => 'Tolima'],
            ['codigo' => null, 'nombre' => 'Valle del Cauca'],
            ['codigo' => null, 'nombre' => 'Vaupés'],
            ['codigo' => null, 'nombre' => 'Vichada']
        ];

        $ciudadesVAC = [
            ['codigo' => null, 'nombre' => 'Cali'],
            ['codigo' => null, 'nombre' => 'Yumbo'],
            ['codigo' => null, 'nombre' => 'Jamundí']
        ];

        $ciudadesBOG = [
            ['codigo' => null, 'nombre' => 'Bogotá'],
            ['codigo' => null, 'nombre' => 'Soacha'],
            ['codigo' => null, 'nombre' => 'Chía']
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
