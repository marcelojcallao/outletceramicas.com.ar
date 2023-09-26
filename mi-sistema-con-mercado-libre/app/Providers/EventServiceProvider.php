<?php

namespace App\Providers;

use App\Src\Models\Product;
use App\Src\Models\Customer;
use App\Src\Models\SaleInvoice;
use App\Src\Models\PedidoCliente;
use App\Observers\ProductObserver;
use App\Observers\CustomerObserver;
use App\Events\NewUserWasRegistered;
use App\Observers\PedidoClienteObserver;
use App\Listeners\NewUserWasRegisteredListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        NewUserWasRegistered::class => [
            NewUserWasRegisteredListener::class
        ],

        'App\Events\OrderShipped' => [
            'App\Listeners\SendShipmentNotification',
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        PedidoCliente::observe(PedidoClienteObserver::class);

        Product::observe(ProductObserver::class);
        
        SaleInvoice::observe(SaleInvoiceObserver::class);
        
        Customer::observe(CustomerObserver::class);

    }
}
