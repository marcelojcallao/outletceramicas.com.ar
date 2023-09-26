<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::post('callbacks', 'MeliNotificationsController@web_hooks');
Route::post('mercadopago_callback', 'Web\CartController@mercadopago_callback');

Route::group(['namespace' => 'App'], function () {
	Route::get('get_ivas', 'IvaController@index');
	Route::get('get_moneys', 'MoneyController@index');
});
Route::group(['prefix' => 'iva'], function () {
	Route::get('index', 'Api\IvaComprasController@index');
	Route::post('comprobantes', 'Api\IvaComprasController@comprobantes');
	Route::post('alicuotas', 'Api\IvaComprasController@alicuotas');
	Route::post('to_excel', 'Api\IvaComprasController@to_excel');
});
Route::group(['middleware' => ['auth:api']], function () {

	Route::post('logout', 'Api\UserController@logout');

	Route::group(['prefix' => 'publication'], function () {

		Route::get('index', 'Api\PublicationController@index');
		Route::post('publish', 'Api\PublicationController@publish');
		Route::post('add_variation', 'Api\PublicationController@add_variation');
		Route::put('modify_quantity', 'Api\PublicationController@modify_quantity');
		Route::put('update_available_quantity', 'Api\PublicationController@update_available_quantity');
		Route::put('update_price', 'Api\PublicationController@update_price');
		Route::get('publications_total', 'Api\PublicationController@publications_total');
		Route::get('get_publications_id', 'Api\PublicationController@get_publications_id');
	});

	Route::group(['prefix' => 'products'], function () {

		Route::post('fetchlistingtypes', 'Api\ProductController@fetchlistingtypes');
		Route::post('fetchattributes', 'Api\ProductController@fetchattributes');
		Route::post('upload/image', 'Api\ProductController@upload_image');
		Route::post('store', 'Api\ProductController@store');
		Route::post('index', 'Api\ProductController@index');
		Route::get('update_ok', 'Api\ProductController@update_ok');
		Route::post('iva_update', 'Api\ProductController@iva_update');
		Route::post('find_by_name', 'Api\ProductController@findProductByName');
		Route::post('find_by_id', 'Api\ProductController@findById');
		Route::post('delete_picture', 'Api\ProductController@delete_picture');
		Route::post('update', 'Api\ProductController@update');
		Route::post('image_remove', 'Api\ProductController@image_remove');
		Route::post('add_stock', 'Api\ProductController@add_stock');
		Route::get('sheet_metal_cuttings', 'Api\SheetMetalCuttingController@index');
		Route::post('sheet_metal_store', 'Api\SheetMetalCuttingController@store');
		Route::post('delete_sheet_metal_cuttings', 'Api\SheetMetalCuttingController@delete_sheet_metal_cuttings');
		Route::post('delete_single_sheet_metal_cutting', 'Api\SheetMetalCuttingController@delete_single_sheet_metal_cutting');
		Route::post('get_sheet_metal_cuttings', 'Api\SheetMetalCuttingController@get_sheet_metal_cuttings');
		Route::post('delete_product', 'Api\ProductController@delete_product');
		Route::post('critical_stock', 'Api\ProductController@critical_stock');
		Route::post('getStockMovements', 'Api\ProductController@getStockMovements');
	});

	Route::group(['prefix' => 'suppliers'], function () {

		Route::post('index', 'Api\SupplierController@index');
		Route::post('brands', 'Api\SupplierController@brands');
	});

	Route::group(['prefix' => 'categories'], function () {

		Route::post('fetch_main_categories', 'Api\CategoriesController@fetch_main_categories');
		Route::post('fetch_children_categories', 'Api\CategoriesController@fetch_children_categories');
		Route::post('store', 'Api\CategoriesController@store');
		Route::post('parent', 'Api\CategoriesController@parent');
		Route::post('child', 'Api\CategoriesController@child');
		Route::post('index', 'Api\CategoriesController@index');
		Route::post('set_status', 'Api\CategoriesController@set_status');
		Route::post('update_name', 'Api\CategoriesController@update_name');
	});

	Route::group(['prefix' => 'variations'], function () {

		Route::post('store', 'Api\VariationController@store');
	});

	Route::group(['prefix' => 'colours'], function () {

		Route::get('get_colours', 'Api\ColourController@index');
	});


	Route::group(['prefix' => 'mercadolibre'], function () {

		Route::get('publication_ids', 'Api\MercadoLibre\PublicationController@index');
		Route::get('publication_headers', 'Api\MercadoLibre\PublicationController@publication_headers');
		Route::put('change_publication_status', 'Api\MercadoLibre\PublicationController@change_status');
		Route::put('avalilable_quantity_change', 'Api\MercadoLibre\PublicationController@avalilable_quantity_change');
		Route::put('update_available_quantity_on_publication', 'Api\MercadoLibre\PublicationController@update_available_quantity_on_publication');
		Route::put('update_available_quantity_on_variation', 'Api\MercadoLibre\PublicationController@update_available_quantity_on_variation');
		Route::put('update_price_on_publication', 'Api\MercadoLibre\PublicationController@update_price_on_publication');
		Route::put('update_price_on_variation', 'Api\MercadoLibre\PublicationController@update_price_on_variation');
		Route::post('syncro', 'Api\MercadoLibre\PublicationController@syncro');
		Route::post('get_products_id', 'Api\MercadoLibre\PublicationController@get_products_id');
		Route::post('save_product_by_id', 'Api\MercadoLibre\PublicationController@save_product_by_id');
		Route::get('orders_by_seller_recent', 'Api\MeliNotificationController@orders_by_seller_recent');
		Route::post('answer_question', 'Api\MercadoLibre\MeliQuestionController@answer_question');
		Route::get('visits_by_publication', 'Api\MercadoLibre\PublicationController@visits_by_publication');
		Route::get('visits_by_publication_between_dates', 'Api\MercadoLibre\PublicationController@visits_by_publication_between_dates');
		Route::post('publish_message', 'Api\MercadoLibre\MessageController@publish_message');
	});

	Route::group(['prefix' => 'notifications'], function () {

		Route::get('myfeeds', 'Api\MeliNotificationController@myfeeds');
	});

	Route::group(['prefix' => 'orders'], function () {

		Route::post('getorders', 'Api\MeliNotificationController@orders_by_seller');
		Route::post('get_all_orders', 'Api\MeliNotificationController@get_all_orders');
		Route::post('order', 'Api\MeliNotificationController@order');
		Route::post('pedidos_clientes_from_meli', 'Api\MeliNotificationController@pedidos_clientes_from_meli');
		Route::post('save_order_from_meli', 'Api\MeliNotificationController@save_order_from_meli');
		Route::post('get_billing_info', 'Api\MeliNotificationController@get_billing_info');
	});

	Route::group(['prefix' => 'pedidos'], function () {

		Route::get('getpedidos', 'Api\OrdersController@index');
		Route::post('bill', 'Api\OrdersController@bill');
	});

	Route::group(['prefix' => 'vouchers'], function () {

		Route::get('index', 'Api\VoucherController@index');
	});

	Route::group(['prefix' => 'afip'], function () {

		Route::post('get_persona', 'Api\Afip\WSPUCController@getPersona');

		Route::group(['prefix' => 'comprobantes'], function () {

			Route::post('ultimo_autorizado', 'Api\Afip\WSFEController@ultimo_autorizado');
			Route::post('generate', 'Api\Afip\WSFEController@generate');
			Route::post('tipo_tributos', 'Api\Afip\WSFEController@tipoTributos');
		});
	});

	Route::group(['prefix' => 'arba'], function () {

		Route::post('alicuota_por_sujeto', 'Api\Arba\ArbaController@alicuota_por_sujeto');
	});


	Route::group(['prefix' => 'company'], function () {

		Route::post('store', 'Admin\CompanyController@store');
		Route::post('update', 'Admin\CompanyController@update');
		Route::get('show', 'Admin\CompanyController@show');
		Route::post('logo_base_64', 'Admin\CompanyController@logo_base_64');
		Route::post('upload_logo', 'Admin\CompanyController@upload_logo');

		Route::group(['prefix' => 'user'], function () {
			Route::get('index', 'Admin\AdminController@index');
			Route::put('rol/update', 'Api\UserController@rol_update');
			Route::put('active', 'Api\UserController@active');
		});

		Route::group(['prefix' => 'rol'], function () {
			Route::get('index', 'Admin\RolController@index');
		});
	});

	Route::group(['prefix' => 'customer'], function () {

		Route::post('store_basic_data', 'Api\CustomerController@store_basic_data');
		Route::post('search_customer', 'Api\CustomerController@search_customer');
		Route::get('index', 'Api\CustomerController@index');
		Route::post('show', 'Api\CustomerController@show');
		Route::post('store', 'Api\CustomerController@store');
		Route::put('update', 'Api\CustomerController@update');
		Route::post('delete', 'Api\CustomerController@delete');
		Route::post('save_customer_address', 'Api\CustomerController@save_customer_address');
	});

	Route::group(['prefix' => 'customer'], function () {

		Route::get('type', 'Api\CustomerTypeController@index');
	});

	Route::group(['prefix' => 'pedido_cliente'], function () {

		Route::get('index', 'Api\PedidoClienteController@index');

		Route::post('store', 'Api\PedidoClienteController@store');
		Route::post('show', 'Api\PedidoClienteController@show');
		Route::post('save_comment', 'Api\PedidoClienteController@save_comment');
		Route::post('print_invoice', 'Api\PedidoClienteController@print_invoice');
		Route::post('cotizacion_print', 'Api\PedidoClienteController@cotizacion_print');
		Route::post('add_delivery_address_to_pedido', 'Api\PedidoClienteController@add_delivery_address_to_pedido');
		Route::post('delete_product', 'Api\PedidoClienteController@delete_product');
		Route::post('add_item_to_pedido', 'Api\PedidoClienteController@add_item_to_pedido');
		Route::post('update_items_of_pedido', 'Api\PedidoClienteController@update_items_of_pedido');
		Route::post('findCode', 'Api\PedidoClienteController@findCode');
		Route::post('findPedidoByCode', 'Api\PedidoClienteController@findPedidoByCode');
		Route::post('order_update_save_current_order', 'Api\PedidoClienteController@order_update_save_current_order');
		Route::post('isEditing', 'Api\PedidoClienteController@isEditing');
		Route::post('restoreIsEditing', 'Api\PedidoClienteController@restoreIsEditing');

		Route::put('delete', 'Api\PedidoClienteController@delete');
		Route::put('restore_pedido', 'Api\PedidoClienteController@restore_pedido');
		Route::put('status_update', 'Api\PedidoClienteController@status_update');
		Route::post('update_delivery_date', 'Api\PedidoClienteController@update_delivery_date');
		Route::put('who_prepared_update', 'Api\PedidoClienteController@who_prepared_update');
		Route::put('who_dispatch_update', 'Api\PedidoClienteController@who_dispatch_update');
		Route::put('changeToPedido', 'Api\PedidoClienteController@changeToPedido');
		Route::put('row_product_update_quantity', 'Api\PedidoClienteController@row_product_update_quantity');
	});

	Route::group(['prefix' => 'sale_invoice'], function () {

		Route::post('store', 'Api\SaleInvoiceController@store');
		Route::post('by_customer', 'Api\SaleInvoiceController@byCustomer');
		Route::post('by_customer_with_debt', 'Api\SaleInvoiceController@byCustomerWithDebt');
		Route::post('index', 'Api\SaleInvoiceController@index');
		Route::put('is_searching', 'Api\SaleInvoiceController@isSearching');
		Route::post('save_presupuesto', 'Api\SaleInvoiceController@save_presupuesto');
		Route::post('excel', 'Api\SaleInvoiceController@excel');

		Route::post('generateInvoiceStep1', 'Api\Afip\SaleInvoiceController@generateInvoiceStep1');
		Route::post('generate_nota_credito', 'Api\Afip\SaleInvoiceController@generate_nota_credito');
	});

	Route::group(['prefix' => 'price_list'], function () {

		Route::post('getPriceListsOfAProduct', 'Api\PriceListsController@getPriceListsOfAProduct');
		Route::post('updatePriceProductOnPriceList', 'Api\PriceListsController@updatePriceProductOnPriceList');
		Route::post('enablePriceLists', 'Api\PriceListsController@enablePriceList');
		Route::post('store', 'Api\PriceListsController@store');
		Route::post('index', 'Api\PriceListsController@index');
		Route::post('update_benefit', 'Api\PriceListsController@update_benefit');
	});

	Route::group(['prefix' => 'address_type'], function () {

		Route::get('index', 'Api\AddressTypeController@index');
	});

	Route::group(['prefix' => 'localidades'], function () {

		Route::post('find_by_name', 'Api\LocalidadController@find_by_name');
	});

	Route::group(['prefix' => 'address'], function () {

		Route::post('store', 'Api\AddressController@store');
		Route::put('update', 'Api\AddressController@update');
	});

	Route::group(['prefix' => 'bank'], function () {
		Route::get('index', 'Api\BankController@index');
		Route::post('find_by_name', 'Api\BankController@findBankByName');
	});

	Route::group(['prefix' => 'receipt'], function () {

		Route::get('index', 'Api\ReceiptController@index');
		Route::post('store', 'Api\ReceiptController@store');
	});

	Route::group(['prefix' => 'measure_unity'], function () {
		Route::get('index', 'Api\MeasurementUnitController@index');
	});

	Route::group(['prefix' => 'article'], function () {
		Route::get('index', 'Api\ArticleController@index');
		Route::post('find_article_by_name', 'Api\ArticleController@find_article_by_name');
		Route::post('store', 'Api\ArticleController@store');
	});

	Route::group(['prefix' => 'accounting_account'], function () {
		Route::get('index', 'Api\AccountingAccountController@index');
		Route::post('son_provider', 'Api\AccountingAccountController@son_provider');
	});

	Route::group(['prefix' => 'taxes'], function () {
		Route::get('index', 'Api\TaxController@index');
		Route::post('store', 'Api\TaxController@store');
		Route::post('set_accounting_account', 'Api\TaxController@set_accounting_account');
		Route::post('set_type', 'Api\TaxController@set_type');
		Route::post('set_state', 'Api\TaxController@set_state');
		Route::post('active', 'Api\TaxController@active');
	});

	Route::group(['prefix' => 'tax_types'], function () {
		Route::get('index', 'Api\TaxTypeController@index');
	});

	Route::group(['prefix' => 'inscription'], function () {
		Route::get('index', 'Api\InscriptionController@index');
	});

	Route::group(['prefix' => 'purchase_document'], function () {
		Route::get('index', 'Api\PurchaseDocumentController@index');
	});

	Route::group(['prefix' => 'providers_regimen'], function () {
		Route::get('index', 'Api\RegimenController@index');
	});

	Route::group(['prefix' => 'provider'], function () {
		Route::post('store', 'Api\ProviderController@store');
		Route::post('find_by_name', 'Api\ProviderController@find_by_name');
		Route::post('receipt/index', 'Api\ReceiptPaymentToProvidersController@index');
		Route::post('receipt/store', 'Api\ReceiptPaymentToProvidersController@store');
		Route::get('receipt/list', 'Api\ReceiptPaymentToProvidersController@list');
	});

	Route::group(['prefix' => 'purchase_invoice'], function () {
		Route::get('index', 'Api\PurchaseInvoiceController@index');
		Route::post('get_notas_credito_from_supplier', 'Api\PurchaseInvoiceController@get_notas_credito_from_supplier');
		Route::post('store', 'Api\PurchaseInvoiceController@store');
	});

	Route::group(['prefix' => 'publication_list'], function () {
		Route::get('index', 'Api\MercadoLibre\PublicationListController@index');
	});

	Route::group(['prefix' => 'order_payment'], function () {
		Route::get('index', 'Api\OrderPaymentController@index');
		Route::post('store', 'Api\OrderPaymentController@store');
		Route::put('delete', 'Api\OrderPaymentController@delete');
	});

	Route::group(['prefix' => 'pay_methods'], function () {
		Route::get('index', 'Api\PaymentTypeController@index');
		Route::post('updatePayment', 'Api\PaymentTypeController@updatePayment');
		Route::post('enable', 'Api\PaymentTypeController@enable');
		Route::post('delete_payment_type', 'Api\PaymentTypeController@delete_payment_type');
		Route::post('store', 'Api\PaymentTypeController@store');
	});

	Route::group(['prefix' => 'commissions'], function () {
		Route::get('my', 'Api\UserController@my_commissions');
	});

	Route::group(['prefix' => 'remitos'], function () {
		Route::post('store', 'Api\RemitoController@store');
	});

	Route::group(['prefix' => 'cash'], function () {
		Route::post('store', 'Api\CashController@store');
		Route::get('index', 'Api\CashController@index');
		Route::post('delete', 'Api\CashController@delete');
	});
	Route::group(['prefix' => 'gains'], function () {
		Route::post('index', 'Api\GainsController@index');
		Route::post('sales_column_chart', 'Api\GainsController@sales_column_chart');
	});
	Route::group(['prefix' => 'checkingAccount'], function () {
		Route::get('index', 'Api\CheckingAccountController@index');
		Route::post('store', 'Api\CheckingAccountController@store');
	});
});
Route::group(['prefix' => 'auth'], function () {
	//Route::post('login', 'Auth\AuthController@login');
	Route::post('signup', 'Auth\AuthController@signup');
	Route::get('signup/activate/{token}', 'Auth\AuthController@signupActivate');

	Route::group(['middleware' => ['auth:api']], function () {
		/*  Route::get('logout', 'Auth\AuthController@logout');
        Route::get('user', 'Auth\AuthController@user'); */
	});

	Route::get('users/{id}', function ($id) {
	});
});
