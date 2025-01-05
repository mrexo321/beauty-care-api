<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PortofolioController;

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Users End-Point
$router->post('/register' , 'UserController@store');
$router->post('/login' , 'UserController@authenticate');
$router->post('/register' , 'UserController@register');
$router->get('/users/staff' , 'UserController@staff');
$router->get('/users/detail/{id}' , 'UserController@user_detail');
$router->get('/users/{id}' , 'UserController@staff');
$router->get('/staff/portofolio/{id}' , 'UserController@getPortofolio');

// Order End-Point
$router->get('/orders', 'OrderController@index');
$router->get('/orders/{id}', 'OrderController@show');
$router->post('/orders', 'OrderController@store');
$router->put('/orders/{id}' , 'OrderController@update');
$router->delete('/orders/{id}' , 'OrderController@destroy');

$router->post('/staff/add', 'UserController@addNewStaff');
$router->get('/staff/detail/{id}', 'UserController@staffDetail');
$router->put('/staff/{id}', 'UserController@updateStaff');

// Review End-Point
$router->get('/reviews', 'ReviewController@index');
$router->get('/reviews/{id}', 'ReviewController@show');
$router->post('/reviews', 'ReviewController@store');
$router->put('/reviews/{id}' , 'ReviewController@update');
$router->delete('/reviews/{id}' , 'ReviewController@destroy');

// Category End-Point
$router->get('/categories', 'CategoryController@index');

// Service End-Point
$router->get('/services', 'ServiceController@index');
$router->get('/services/{id}', 'ServiceController@show');
$router->post('/services', 'ServiceController@store');
$router->put('/services/{id}' , 'ServiceController@update');
$router->delete('/services/{id}' , 'ServiceController@destroy');


$router->get('/portofolio', 'PortofolioController@index');
$router->get('/portofolio/{id}', 'PortofolioController@show');
$router->post('/portofolio', 'PortofolioController@store');
$router->put('/portofolio/{id}' , 'PortofolioController@update');
$router->delete('/portofolio/{id}' , 'PortofolioController@destroy');
