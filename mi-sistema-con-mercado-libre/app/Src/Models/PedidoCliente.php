<?php

namespace App\Src\Models;

use App\Events\PedidoClienteCreated;
use App\User;
use App\Src\Models\Remito;
use App\Src\Models\Status;
use App\Src\Models\Comment;
use App\Src\Models\History;
use App\Src\Models\Customer;
use App\Src\Models\Commission;
use App\Src\Models\PaymentType;
use App\Src\Models\SaleInvoice;
use App\Src\Traits\StatusTrait;
use App\Src\Models\WebHookMessages;
use App\Src\Models\PedidoClienteItem;
use App\Src\Contracts\StatusInterface;
use Illuminate\Database\Eloquent\Model;
use App\Src\Models\PedidosClientesShipping;
use App\Src\Models\PedidosClientesPaymentType;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class PedidoCliente extends Model implements StatusInterface, Auditable
{
	use StatusTrait, \OwenIt\Auditing\Auditable;

	protected $guarded = [];

	protected $table = 'pedidos_clientes';

	protected $observables = ['finished'];

	protected $casts = [
		'geocoder' => 'array',
		'meli_data' => 'array'
	];

	public function items()
	{
		return $this->hasMany(PedidoClienteItem::class, 'pedido_cliente_id', 'id');
	}

	public function customer()
	{
		return $this->hasOne(Customer::class, 'id', 'customer_id');
	}

	public function status()
	{
		return $this->hasOne(Status::class, 'id', 'status_id');
	}

	public function statuses()
	{
		return $this->hasMany(PedidoClienteStatus::class, 'pedido_cliente_id', 'id');
	}

	public function addresses()
	{
		return $this->morphMany(Address::class, 'addressable');
	}

	public function comments()
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function messages()
	{
		return $this->hasMany(WebHookMessages::class, 'resource_id', 'meli_id')->orderBy('date');
	}

	public function commission()
	{
		return $this->hasOne(Commission::class, 'commission_id', 'id');
	}

	public function payment_type()
	{
		return $this->hasOne(PaymentType::class, 'id', 'pay_method');
	}

	public function payment_type_aditional()
	{
		return $this->hasOne(PedidosClientesPaymentType::class, 'pedido_cliente_id', 'id');
	}

	public function shipping()
	{
		return $this->hasOne(PedidosClientesShipping::class, 'pedido_cliente_id', 'id');
	}

	public function history()
	{
		return $this->morphMany(History::class, 'historiable');
	}

	public function invoice()
	{
		return $this->belongsTo(SaleInvoice::class, 'id', 'pedido_cliente_id');
	}

	public function invoices(): HasMany
	{
		return $this->hasMany(SaleInvoice::class, 'pedido_cliente_id', 'id');
	}

	public function voucher(): HasOne
	{
		return $this->hasOne(Voucher::class, 'id', 'voucher_id');
	}

	public function pedido(): HasMany
	{
		return $this->hasMany(PedidoCliente::class, 'parent_id');
	}

	public function cotizacion(): BelongsTo
	{
		return $this->belongsTo(PedidoCliente::class, 'id', 'parent_id');
	}

	public function remitos(): HasMany
	{
		return $this->hasMany(Remito::class, 'pedido_cliente_id', 'id');
	}

	public function base_imponible()
	{
		return $this->items->sum(function ($i) {
			return $i->quantity * $i->unit_price;
		});
	}

	public function come_from_meli()
	{
		if (is_null($this->meli_id)) {
			return false;
		}
		return true;
	}
}
