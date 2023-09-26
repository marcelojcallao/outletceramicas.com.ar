<?php

use App\Src\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        \DB::table('priorities')->delete();
        
        $priorities = [
			[
				'name' 		=> 'BAJA',
				'value'	=> 1,
			],
			[
				'name' 		=> 'MEDIA',
				'value'	=> 500,
			],
			[
				'name' 		=> 'ALTA',
				'value'	=> 1000,
			],
        ];

        $priorities = collect($priorities);

        $priorities->each(function($p){
        	Priority::create([
        		'name'   => $p['name'],
        		'value'  => $p['value'],
        	]);
        });
        
    }
}