# Zoroaster Menu Builder

![Zoroaster Menu Builder](https://raw.githubusercontent.com/KarimQaderi/Zoroaster-Menu-Builder/master/img/img.png)



## نصب 

فایل composer.json باز کنید و کد زیر رو قرار دهید :

```json
    "require": {
        "karim-qaderi/zoroaster-menu-builder": "*"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/KarimQaderi/Zoroaster-Menu-Builder.git"
        }
    ],
```

```bash
composer update
```

بعد کدای زیر رو به ترتیب اجرا کنید :

```bash
php artisan vendor:publish --tag=menu-builder-migration
php artisan migrate

php artisan vendor:publish --tag=menu-builder-assets
```


## Helper Function

```php
{!! menu_builder('main') !!}

//or

{!! menu_builder('main', 'parent-class', 'child-class', 'dl', 'dd') !!}
```


```php
{!! menu_json('main') !!}
```



## سطع دسترسی کلی 

برای اینکه سطع دسترسی رو بزارید فایل `app/Providers/ZoroasterServiceProvider.php` رو باز کنید کد زیر رو در `boot` قرار دهید. 

```php
/**
 * Bootstrap any application services.
 *
 * @return void
 */
protected function boot()
{
    Gate::define('ZoroasterMenuBuilder', function ($user) {
        return in_array($user->email, [
            'karimqaderi1@gmail.com',
        ]);
    });
}
```


