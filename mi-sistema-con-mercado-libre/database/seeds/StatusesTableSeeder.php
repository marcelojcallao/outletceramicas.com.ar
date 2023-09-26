<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('statuses')->delete();
        
        \DB::table('statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'ACTIVO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'PENDIENTE',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'REMITIDO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'PRESUPUESTADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'FACTURADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'PREPARADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'RETIRADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'DESPACHADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'ENTREGADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            9 => 
            array (
                'id' => 11,
                'name' => 'COTIZADO',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'name' => 'DISPONIBLE',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
            11 => 
            array (
                'id' => 13,
                'name' => 'CUMPLIDO
',
                'created_at' => NULL,
                'updated_at' => NULL,
                'level' => NULL,
            ),
        ));
        
        
    }
}