# LaravelPayU

## Introducción
LaravelPayU provee una interfaz sencilla para utilizar el sdk de PayU en proyectos que tienen como base el framework [*Laravel*](https://laravel.com).
Este proyecto hace uso del [sdk de Payu](http://developers.payulatam.com/es/sdk/), pero no es un proyecto oficial de PayU.

## Instalación y configuración

Instalar el paquete mediante composer:

```bash
composer require alexo/laravel-payu
```

Luego incluir el ServiceProvider en el arreglo de providers en *config/app.php*

```bash
Alexo\LaravelPayU\LaravelPayUServiceProvider::class,
```

Publicar la configuración para incluir la informacion de la cuenta de PayU:

```bash
php artisan vendor:publish 
```


Incluir la informacion de la cuenta y ajustes en el archivo *.env* ó directamente en
el archivo de configuración *config/payu.php*

```bash
APP_ENV=local

PAYU_ON_TESTING=true

PAYU_MERCHANT_ID=your-merchant-id

PAYU_API_LOGIN=your-api-login

PAYU_API_KEY=your-api-key

PAYU_ACCOUNT_ID=your-account-id

PAYU_COUNTRY=your-country-ref: AR/BR/CO/CL/MX/PA/PE/US

PSE_REDIRECT_URL=your-pse-redirect-url
```

## Uso del API

Esta versión contiene solo una interfaz para pagos únicos y consultas.
Si necesita usar tokenización y pagos recurrentes debe usar el sdk de PayU directamente.

### Ping y Bancos

Para consultar la disponibilidad de la plataforma se puede usar el método doPing en el controlador
designado:

```php
<?php

namespace App\Http\Controllers;

use Alexo\LaravelPayU\LaravelPayU;

class PaymentsController extends Controller
{
    LaravelPayU::doPing(function($response) {
        $code = $response->code;
        // ... revisar el codigo de respuesta
    }, function($error) {
     // ... Manejo de errores PayUException
    });

```

Para consulta de bancos se utiliza el método getPSEBanks que también recibe una función de respuesta
y una de error:

```php
<?php

namespace App\Http\Controllers;

use Alexo\LaravelPayU\LaravelPayU;

class PaymentsController extends Controller
{
    LaravelPayU::getPSEBanks(function($banks) {
        //... Usar datos de bancos
        foreach($banks as $bank) {
            $bankCode = $bank->pseCode;
        }
    }, function($error) {
        // ... Manejo de errores PayUException, InvalidArgument
    });
```

### Pagos Únicos

Permite el pago de ordenes generadas a través del uso de un [*trait*](http://php.net/manual/en/language.oop5.traits.php) de la siguiente manera:

En el modelo de las ordenes, en este caso Order.php debe incluir:

```php
<?php

namespace App;

use Alexo\LaravelPayU\Payable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Payable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference', 'payu_order_id',  'transaction_id', 'state', 'value', 'user_id'
    ];
}

```

**Nota:** Los campos *reference*, *payu_order_id*, *transaction_id*, son necesarios para realizar
consultas posteriormente.

Una vez configurado el modelo, en el controlador designado para pagos podemos usar el método *payWith* para hacer la consulta y captura de pago (equivalente a doAuthorizationAndCapture en el sdk):

```php
<?php

$order = Order::find($id);

$data = [
    \PayUParameters::DESCRIPTION => 'Payment cc test',
    \PayUParameters::IP_ADDRESS => '127.0.0.1',
    \PayUParameters::CURRENCY => 'COP',
    \PayUParameters::CREDIT_CARD_NUMBER => '378282246310005',
    \PayUParameters::CREDIT_CARD_EXPIRATION_DATE => '2017/02',
    \PayUParameters::CREDIT_CARD_SECURITY_CODE => '1234',
    \PayUParameters::INSTALLMENTS_NUMBER => 1 ...
];

$order->payWith($data, function($response, $order) {
    if ($response->code == 'SUCCESS') {
        $order->update([
            'payu_order_id' => $response->transactionResponse->orderId,
            'transaction_id' => $response->transactionResponse->transactionId
        ]);
        // ... El resto de acciones sobre la orden
    } else {
    //... El código de respuesta no fue exitoso
    }
}, function($error) {
    // ... Manejo de errores PayUException, InvalidArgument
});

```

El método *payWith* recibe tres parámetros:

- Los parámetros de pago, usando "\" delante de la clase PayUParameters, para
poder utilizar la constante, dado que el sdk no usa namespaces y autoloading.
- Una función (closure) que recibe la respuesta de la consulta.
- Una función (closure) que recibe las Excepciones generadas por validación ó
errores en el pago.

También puede usar los métodos *authorizeWith* y *captureWith* para autorización de
pago y captura de la orden, pero recuerde que sólo están disponibles para **Brasíl**.

Ver documentación del [sdk para pagos](http://developers.payulatam.com/es/sdk/payments.html).

### Consultas

Para las consultas se agrega el trait Searchable en el modelo de la orden asi:

```php
<?php

namespace App;

use Alexo\LaravelPayU\Payable;
use Alexo\LaravelPayU\Searchable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Payable, Searchable;
}
```

Luego en el controlador designado para consultas podemos hacer consultas usando el id asignado por Payu, la referencia dada por nosotros, o el id de la transacción:

```php
<?php

$order = Order::find($id);

$order->searchById(function($response, $order) {
    // ... Usar la información de respuesta
}, function($error) {
    // ... Manejo de errores PayUException, InvalidArgument
});

$order->searchByReference(function($response, $order) {
    // ... Usar la información de respuesta
}, function($error) {
    // ... Manejo de errores PayUException, InvalidArgument
});

$order->searchByTransaction(function($response, $order) {
    // ... Usar la información de respuesta
}, function($error) {
    // ... Manejo de errores PayUException, InvalidArgument
});

```

Los métodos *searchById*, *searchByReference* y *searchByTransaction* reciben dos parámetros:

- Una función (closure) que recibe la respuesta de la consulta.
- Una función (closure) que recibe las Excepciones generadas por validación ó errores en el pago.

Ver documentación del [sdk de consultas](http://developers.payulatam.com/es/sdk/queries.html).


## Pruebas
Instalar las dependencias del paquete.
Crear un archivo *.env* en la raiz del paquete con la configuración respectiva de pruebas para Colombia, ya que es el único país con los tres métodos de pago disponibles. Ver información en [sitio de PayU](http://developers.payulatam.com/es/sdk/sandbox.html) y luego si ejecutar las pruebas:

```bash
phpunit
```

## Errores y contribuciones

Para un error escribir directamente el problema en github issues o enviarlo
al correo alejandrogutierrezacosta@gmail.com. Si desea contribuir con el proyecto por favor enviar los ajustes siguiendo la guía de contribuciones:

- Usar las recomendaciones de estilos [psr-1](http://www.php-fig.org/psr/psr-1/) y [psr-2](http://www.php-fig.org/psr/psr-2/)

- Preferiblemente escribir código que favorezca el uso de Laravel

- Escribir las pruebas y revisar el código antes de hacer un pull request

