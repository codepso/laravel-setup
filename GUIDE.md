# Codepso / Laravel / Catalog

## Use
```php
use Codepso\Laravel\Catalog\Catalog;

return Catalog::for(User::query())->init()->get();
````

```php
use Codepso\Laravel\Catalog\Catalog;

$catalog = Catalog::for(User::query());
$catalog->customFilters = ['user_id', 'user_full_name'];
$catalog->init();

foreach ($catalog->customFilters as $attribute) {
    if (isset($catalog->builder->params()->customFilters[$attribute])) {
        $value = $catalog->builder->params()->customFilters[$attribute];
        switch ($attribute) {
            case 'user_id':
                break;
            case 'user_full_name':
                $catalog->q->where($attribute, 'ILIKE', "%{$value}%");
                break;
        }
    }
}

return $catalog->get();
````

### Request Filters
```php
use Codepso\Laravel\Catalog\Catalog;

$catalog = app(Catalog::class);
$catalog->builder->init();
return $catalog->builder->params();
```

```php
use Codepso\Laravel\Catalog\Catalog;

$catalog = app(Catalog::class);
$catalog->builder-getFilters();
return $catalog->builder->params()->filters;
```


