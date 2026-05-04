<?php

namespace Database\Seeders\Support;

class LocalLeagueCatalog
{
    /**
     * @return array<int, array{name:string,categoria:string,es_local:bool,ciudad:string,pabellon:string}>
     */
    public static function clubs(): array
    {
        return [
            ['name' => 'Bellreguard Basket Club', 'categoria' => 'Senior Masculino', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'CB Gandia', 'categoria' => 'Senior Masculino', 'es_local' => false, 'ciudad' => 'Gandia', 'pabellon' => 'Pabellon del Raval de Gandia'],
            ['name' => 'Basket Oliva', 'categoria' => 'Senior Masculino', 'es_local' => false, 'ciudad' => 'Oliva', 'pabellon' => 'Poliesportiu Municipal d Oliva'],
            ['name' => 'Denia Basquet', 'categoria' => 'Senior Masculino', 'es_local' => false, 'ciudad' => 'Denia', 'pabellon' => 'Pavello Joan Fuster de Denia'],

            ['name' => 'Bellreguard Basket Femeni', 'categoria' => 'Senior Femenino', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'NB Xativa', 'categoria' => 'Senior Femenino', 'es_local' => false, 'ciudad' => 'Xativa', 'pabellon' => 'Pabellon Francisco Ballester de Xativa'],
            ['name' => 'CB Alzira', 'categoria' => 'Senior Femenino', 'es_local' => false, 'ciudad' => 'Alzira', 'pabellon' => 'Palau d Esports d Alzira'],
            ['name' => 'Cullera Basquet', 'categoria' => 'Senior Femenino', 'es_local' => false, 'ciudad' => 'Cullera', 'pabellon' => 'Pabellon Cobert de Cullera'],

            ['name' => 'Bellreguard Junior', 'categoria' => 'Junior', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'Sueca Basket', 'categoria' => 'Junior', 'es_local' => false, 'ciudad' => 'Sueca', 'pabellon' => 'Pabellon Cubierto de Sueca'],
            ['name' => 'Tavernes Basket', 'categoria' => 'Junior', 'es_local' => false, 'ciudad' => 'Tavernes de la Valldigna', 'pabellon' => 'Pabellon Municipal de Tavernes'],
            ['name' => 'CB Pego', 'categoria' => 'Junior', 'es_local' => false, 'ciudad' => 'Pego', 'pabellon' => 'Pabellon Ausias March de Pego'],

            ['name' => 'Bellreguard Cadete Rojo', 'categoria' => 'Cadete', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'Bellreguard Cadete Negro', 'categoria' => 'Cadete', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'Xeraco Basquet', 'categoria' => 'Cadete', 'es_local' => false, 'ciudad' => 'Xeraco', 'pabellon' => 'Pabellon Municipal de Xeraco'],
            ['name' => 'Carcaixent Basquet', 'categoria' => 'Cadete', 'es_local' => false, 'ciudad' => 'Carcaixent', 'pabellon' => 'Pabellon Cubierto de Carcaixent'],

            ['name' => 'Bellreguard Infantil', 'categoria' => 'Infantil', 'es_local' => true, 'ciudad' => 'Bellreguard', 'pabellon' => 'Pabellon Municipal de Bellreguard'],
            ['name' => 'Algemesi Basquet', 'categoria' => 'Infantil', 'es_local' => false, 'ciudad' => 'Algemesi', 'pabellon' => 'Pabellon 9 d Octubre d Algemesi'],
            ['name' => 'Canals Basket', 'categoria' => 'Infantil', 'es_local' => false, 'ciudad' => 'Canals', 'pabellon' => 'Pabellon Ricardo Tormo de Canals'],
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function categories(): array
    {
        return collect(self::clubs())
            ->pluck('categoria')
            ->unique()
            ->values()
            ->all();
    }

    public static function venueForTeam(string $teamName): string
    {
        return collect(self::clubs())
            ->firstWhere('name', $teamName)['pabellon'] ?? 'Pabellon Municipal';
    }
}
