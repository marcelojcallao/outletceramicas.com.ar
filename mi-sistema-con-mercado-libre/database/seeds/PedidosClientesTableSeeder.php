<?php

use Illuminate\Database\Seeder;

class PedidosClientesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pedidos_clientes')->delete();
        
        
        
    }
}