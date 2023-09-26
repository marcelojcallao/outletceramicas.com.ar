<?php

namespace App\Src\Models;

use App\Src\Models\Iva;
use App\Src\Models\Brand;

use App\Src\Models\History;
use App\Src\Models\Picture;
use App\Src\Models\Category;

use App\Src\Models\PriceList;

use App\Src\Models\Publication;
use App\Src\Models\WebHookQuestion;
use App\Src\Models\PriceListProduct;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia, Auditable
{
    protected $guarded = [];

    /* public static function boot(){
        parent::boot();
        static::saving(function($product){
            dd($product);
        });
    } */
    use HasMediaTrait, LogsActivity {
    }

    use \OwenIt\Auditing\Auditable;

    protected static $logName = 'Product';

    protected $observables = ['finished'];

    protected $searchableColumns = ['search_by'];

    protected $casts = [
        'attr_item_condition' => 'array',
        'buying_mode' => 'array',
        'main_category' => 'array',
        'path_from_root' => 'array',
        'children_category' => 'array',
        'iva' => 'array',
        'money' => 'array',
        'pictures' => 'array',
        'allow_variations' => 'array',
        'listing_type' => 'array',
        'variation_attribute' => 'array',
        'attributes' => 'array',
        'meli_info' => 'array',
        'categories_path' => 'array',
        'selected_categories_from_root' => 'array',
        'apply_discount_pay_method' => 'array',
    ];

     /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

	public function stock_movements()
	{
		return $this->hasMany(ProductHistory::class, 'product_id', 'id');
	}

    public function prices()
    {
        return $this->hasMany(PriceListProduct::class, 'product_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function iva()
    {
        return $this->hasOne(Iva::class);
    }

    public function money()
    {
        return $this->hasOne(Money::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }

    public function pricelists()
    {
        return $this->belongsToMany(PriceList::class, 'pricelist_products', 'product_id', 'pricelist_id')
            ->withPivot(['price', 'costo', 'benefit', 'enabledPriceListToProduct']);
    }

    public function web_hook_questions()
    {
        return $this->hasMany(WebHookQuestion::class, 'item_id', 'meli_id');
    }

    public function history()
    {
        return $this->morphMany(History::class, 'historiable');
    }

    public function priority(): HasOne
    {
        return $this->hasOne(Priority::class, 'id', 'priority_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function publication()
    {
        return $this->hasOne(Publication::class, 'meli_id', 'meli_id');
    }

    /**
     * MÉTODOS
     */
    public function save_associated_images($files)
    {

        $this->addMedia('image')->toMediaCollection('products');

        return $this;
    }

    public function variation_count()
    {
        return $this->variations()->count();
    }

    public function is_published_meli()
    {

        if ($this->published_meli) {
            return true;
        }

        return false;
    }

    public function is_published_here()
    {

        if ($this->published_here) {
            return true;
        }

        return false;
    }


    /**
     * @ $response Response from meli
     */
    public function add_meli_id($response)
    {
        $publication = collect($response['body'])->toArray();

        $this->meli_id = $publication['id'];
    }

    public function is_now_published_meli()
    {
        $this->published_meli = 1;
    }

    public function reset_published_meli()
    {
        $this->published_meli = 0;
    }

    public function is_now_published_here()
    {
        $this->published_here = 1;
    }

    public function reset_published_here()
    {
        $this->published_here = 0;
    }

    public function total_stock()
    {
        return $this->variations->map(function($v){
            return $v->stock->total_stock;
        })->sum();
    }

    public function hasIva()
    {
        if (! (is_null($this->iva))) {
            return true;
        }

        return false;
    }

    /**
     * Scopes
     *
    */

    public function scopePublished_here($query)
    {
        return $query->where('published_here', '=', 1);
    }

    public function get_pics()
    {
        if($this->getMedia('products')->isEmpty()){
            return $this->pictures->first()->secure_url;
        }else{
            return $this->getMedia('products')->first()->getFullUrl();
        }
    }

}
