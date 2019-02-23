# Zoroaster Menu Builder

![Zoroaster Menu Builder](https://raw.githubusercontent.com/KarimQaderi/Zoroaster-Menu-Builder/master/img/img.png)



## نصب 
 
 کدای زیر رو به ترتیب اجرا کنید :

```bash
composer require karim-qaderi/zoroaster-menu-builder

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



## سطح دسترسی 

برای اینکه سطح دسترسی رو بزارید فایل `app/Providers/ZoroasterServiceProvider.php` رو باز کنید کد زیر رو در `boot` قرار دهید. 

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


