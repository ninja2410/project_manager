<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->defineAs(App\Item::class,'Producto',function (Faker\Generator $faker) {
    return [
        'upc_ean_isbn' => $faker->ean8,
        'item_name' => $faker->word,
        'size' => $faker->shuffle('Extra Grande,Grande, Mediano,Pequeño'),
        'stock_action'=>$faker->randomElement($array = array ('=','+')),
        'description' => $faker->sentence,
        'avatar' => 'no-foto.png',
        'cost_price' => $faker->randomFloat(2,5,10000),
        'selling_price' => $faker->randomFloat(2,5,10000)*1.2,
        'quantity' => 1,
        'type_id' => 1,
        'status'=>1,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
        'id_categorie' => $faker->numberBetween(1,2),
        'low_price' => $faker->randomFloat(2,5,10000),
        'expiration_date' => time(),
        'minimal_existence' => $faker->numberBetween(0,50),
    ];
});

$factory->defineAs(App\Item::class,'Servicio',function (Faker\Generator $faker) {
    return [
        'upc_ean_isbn' => $faker->ean8,
        'item_name' => $faker->word,
        'size' => $faker->shuffle('Extra Grande,Grande, Mediano,Pequeño'),
        'stock_action'=>$faker->randomElement($array = array ('=','+')),
        'description' => $faker->sentence,
        'avatar' => 'no-foto.png',
        'cost_price' => $faker->randomFloat(2,5,10000),
        'selling_price' => $faker->randomFloat(2,5,10000)*1.2,
        'quantity' => 1,
        'type_id' => 2,
        'status'=>1,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
        'id_categorie' => $faker->numberBetween(3,4),
        'low_price' => $faker->randomFloat(2,5,10000),
        'expiration_date' => time(),
        'minimal_existence' => $faker->numberBetween(0,50),
    ];
});

$factory->defineAs(App\Item::class,'Mobiliario',function (Faker\Generator $faker) {
    return [
        'upc_ean_isbn' => $faker->ean8,
        'item_name' => $faker->word,
        'size' => $faker->shuffle('Extra Grande,Grande, Mediano,Pequeño'),
        'stock_action'=>$faker->randomElement($array = array ('=','+')),
        'description' => $faker->sentence,
        'avatar' => 'no-foto.png',
        'cost_price' => $faker->randomFloat(2,5,10000),
        'selling_price' => $faker->randomFloat(2,5,10000)*1.2,
        'quantity' => 1,
        'type_id' => 3,
        'status'=>1,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
        'id_categorie' => $faker->numberBetween(5,6),
        'low_price' => $faker->randomFloat(2,5,10000),
        'expiration_date' => time(),
        'minimal_existence' => $faker->numberBetween(0,50),
    ];
});
