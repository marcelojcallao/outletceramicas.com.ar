<?php

use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_types')->delete();
        
        \DB::table('payment_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'CHEQUE',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'MERCADO PAGO',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 21,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'EFECTIVO ENTREGADO',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 0,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'TRANSFERENCIA',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 0,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'TARJ. CRÉDITO',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 10.50,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'TARJ. DÉBITO',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => 0,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'EFECTIVO RETIRADO',
                'status_id' => 1,
                'created_at' => '2021-04-03 12:02:54',
                'updated_at' => '2021-04-03 12:02:54',
                'percentage' => -8,
            ),
        ));
        
        
    }
}