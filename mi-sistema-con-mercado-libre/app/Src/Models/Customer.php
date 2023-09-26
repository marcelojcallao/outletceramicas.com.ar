<?php

namespace App\Src\Models;

use App\Src\Models\BankAccount;
use App\Src\Models\Inscription;
use App\Src\Models\SaleInvoice;
use App\Src\Models\CustomerType;
use App\Src\Models\PedidoCliente;
use App\Src\Models\AccountingAccount;
use App\Src\Models\PurchaserDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Customer extends Model
{
    protected $searchableColumns = ['number', 'name', 'email', 'meli_nick'];

    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected $casts = [
        'datos_generales' => 'array',
        'regimen_general' => 'array',
        'monotributo' => 'array',
        'error_constancia' => 'array',
        'afip_data' => 'array',
        'others' => 'array',
    ];
    
    /** 
     * Inscription -> responsoble inscripto - monotributo - etc
     */
    public function inscription()
    {
        return $this->hasOne(Inscription::class, 'id', 'inscription_id');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function type()
    {
        return $this->hasOne(CustomerType::class, 'id', 'customer_type_id');
    }
    /**
     * PurchaserDocument -> cuit - dni - cuil - etc
     */
    public function purchaserDocument()
    {
        return $this->hasOne(PurchaserDocument::class, 'id', 'purchaser_document_id');
    }

    public function pedidos()
    {
        return $this->hasMany(PedidoCliente::class);
    }

    public function sale_invoices()
    {
        return $this->hasMany(SaleInvoice::class);
    }

    public function accounting_account_child()
    {
        return $this->hasOne(AccountingAccount::class, 'id', 'accounting_account_child_id');
    }

    public function banksAccounts()
    {
        return $this->morphMany(BankAccount::class, 'bank_accountable');
    }

    public function remitos(): HasMany
    {
        return $this->hasMany(Remito::class, 'customer_id', 'id');
    }

    public function hasInscription()
    {
        if (is_null($this->inscription_id)) {
            return false;
        }
        return true;
    }

    /* public function delete()
    {
        $this->status_id = Status::ELIMINADO;
        
        $this->save();
        
        $this->refresh();
        
        return $this;
    } */
}
