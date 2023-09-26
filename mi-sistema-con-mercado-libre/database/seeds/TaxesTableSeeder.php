<?php

use Illuminate\Database\Seeder;

class TaxesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('taxes')->delete();
        
        \DB::table('taxes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'PERCEP. IVA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => NULL,
                'updated_at' => '2021-01-28 17:02:37',
                'state_id' => 1,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'PERCEP. IIBB CABA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 1,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'PERCEP. IIBB BUENOS AIRES',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 2,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            3 => 
            array (
                'id' => 6,
                'name' => 'PERCEP. IIBB CATAMARCA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 3,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            4 => 
            array (
                'id' => 7,
                'name' => 'PERCEP. IIBB CóRDOBA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 4,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            5 => 
            array (
                'id' => 8,
                'name' => 'PERCEP. IIBB CORRIENTES',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 5,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            6 => 
            array (
                'id' => 9,
                'name' => 'PERCEP. IIBB ENTRE RíOS',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 6,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            7 => 
            array (
                'id' => 10,
                'name' => 'PERCEP. IIBB JUJUY',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 7,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            8 => 
            array (
                'id' => 11,
                'name' => 'PERCEP. IIBB MENDOZA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 8,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            9 => 
            array (
                'id' => 12,
                'name' => 'PERCEP. IIBB LA RIOJA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 9,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            10 => 
            array (
                'id' => 13,
                'name' => 'PERCEP. IIBB SALTA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 10,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            11 => 
            array (
                'id' => 14,
                'name' => 'PERCEP. IIBB SAN JUAN',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 11,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            12 => 
            array (
                'id' => 15,
                'name' => 'PERCEP. IIBB SAN LUIS',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 12,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            13 => 
            array (
                'id' => 16,
                'name' => 'PERCEP. IIBB SANTA FE',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 13,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            14 => 
            array (
                'id' => 17,
                'name' => 'PERCEP. IIBB SANTIAGO DEL ESTERO',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 14,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            15 => 
            array (
                'id' => 18,
                'name' => 'PERCEP. IIBB TUCUMáN',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 15,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            16 => 
            array (
                'id' => 19,
                'name' => 'PERCEP. IIBB CHACO',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 16,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            17 => 
            array (
                'id' => 20,
                'name' => 'PERCEP. IIBB CHUBUT',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 17,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            18 => 
            array (
                'id' => 21,
                'name' => 'PERCEP. IIBB FORMOSA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 18,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            19 => 
            array (
                'id' => 22,
                'name' => 'PERCEP. IIBB MISIONES',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 19,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            20 => 
            array (
                'id' => 23,
                'name' => 'PERCEP. IIBB NEUQUéN',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 20,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            21 => 
            array (
                'id' => 24,
                'name' => 'PERCEP. IIBB LA PAMPA',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 21,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            22 => 
            array (
                'id' => 25,
                'name' => 'PERCEP. IIBB RíO NEGRO',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 22,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            23 => 
            array (
                'id' => 26,
                'name' => 'PERCEP. IIBB SANTA CRUZ',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 23,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            24 => 
            array (
                'id' => 27,
                'name' => 'PERCEP. IIBB TIERRA DEL FUEGO',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 24,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            25 => 
            array (
                'id' => 28,
                'name' => 'IMPUESTOS INTERNOS',
                'accounting_account_id' => 3,
                'tax_type_id' => 3,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 1,
                'movement_type_id' => 1,
                'active' => 1,
            ),
            26 => 
            array (
                'id' => 29,
                'name' => 'ITC',
                'accounting_account_id' => 3,
                'tax_type_id' => 2,
                'created_at' => '2023-03-14 18:29:23',
                'updated_at' => '2023-03-14 18:29:23',
                'state_id' => 1,
                'movement_type_id' => 1,
                'active' => 1,
            ),
        ));
        
        
    }
}