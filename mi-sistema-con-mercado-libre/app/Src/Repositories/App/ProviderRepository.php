<?php

namespace App\Src\Repositories\App;

use App\Src\Models\City;
use App\Src\Models\Provider;
use Illuminate\Support\Facades\DB;
use App\Src\Models\ProviderRegimen;

class ProviderRepository
{
    
    public function create($pr)
    {
        DB::beginTransaction();

        try {
            $provider = new Provider;
            
            if (array_key_exists( 'regimen', $pr )) {
                if (is_null($pr['regimen'])) {
                    $regimen = null;
                }else{
                    $regimen = ProviderRegimen::where('code', $pr['regimen']['code'])->get()->first()->id;
                }
            }else{
                $regimen = null;
            }

            $provider->name = $pr['name'];
            $provider->number = $pr['number'];
            $provider->inscription_id = $pr['inscription']['id'];
            $provider->purchaser_document_id = $pr['purchase_document']['id'];
            $provider->afip_data = $pr['afip_data'];
            $provider->accounting_account_id = $pr['accounting_account']['id'];
            $provider->sublevel_accounting_account_id = $pr['sublevel_accounting_account']['id'];
            $provider->regimen_id = $regimen;
            $provider->iibb_exempt = $pr['taxes']['iibb_exempt'];
            $provider->iva_exempt = $pr['taxes']['iva_exempt'];
            $provider->gcias_exempt = $pr['taxes']['gcias_exempt'];
            $provider->suss_exempt = $pr['taxes']['suss_exempt'];
            $provider->has_afip_data = (! empty($pr['afip_data'])) ? true : false;
            $provider->contact = $pr['contact']['name'];
            $provider->email = $pr['contact']['email'];
            $provider->cell_phone = $pr['contact']['cell_phone'];
            $provider->phone_1 = $pr['contact']['phone_1'];
            $provider->phone_2 = $pr['contact']['phone_2'];
            $provider->phone_3 = $pr['contact']['phone_3'];

            $provider->save();
            $provider->refresh();
            
            if (array_key_exists('city', $pr['address'])) { 
                if (is_null($pr['address']['city'])) {
                    $city = null;
                    $city_name = null;
                }else{

                    $c = City::where('name', $pr['address']['city'])->get();

                    if ($c->isEmpty()) {
                        $new_city = new City;
                        $new_city->state_id = $pr['address']['state']['id'];
                        $new_city->name = strtoupper($pr['address']['city']);
                        $new_city->cp = strtoupper($pr['address']['cp']);
                        $new_city->save();
                        $new_city->fresh();

                        $city = $new_city->id;
                        $city_name = $new_city->name;

                    }else{

                        $c = City::where('name', $pr['address']['city'])->get()->first();
                        $city = $c->id;
                        $city_name = $c->name;
                    }
                }
            }else{
                $city = null;
                $city_name = null;
            }
 
            if (! is_null($pr['address']['address']) && ! is_null($pr['address']['number'])) {
            
                $provider->addresses()->create([
                    'country_id' => 1,
                    'type_id' => $pr['address']['type']['id'],
                    'province_id' => 1,
                    'city_id' => $city,
                    'city' => $city_name,
                    'address' => $pr['address']['address'],
                    'number' => $pr['address']['number'],
                    'cp' => $pr['address']['cp'],
                    'between_streets' => array_key_exists('between', $pr['address']) ? $pr['address']['between'] : null,
                    'obs' => array_key_exists('obs', $pr['address']) ? $pr['address']['obs'] : null,
                ]);
            }

            DB::commit();

            return $provider;

        } catch (\Exception  $e) {

            DB::rollback();

            activity('CREAR PROVEEDOR')
                ->withProperties( [ 'Proveedor' => $pr ] )
                ->log( "Mensaje: {$e->getMessage()} - Línea: {$e->getLine()} - Código: {$e->getCode()}" );
                
            throw $e;
        }
    }

    public function find_by_name()
    {
        $providers = Provider::query();

        $providers = $providers->where('name', 'like', '%' . request()->provider . '%')
            ->orWhere('number', 'like', '%' . request()->provider . '%')
            ->get();

        return $providers;
    }
}
