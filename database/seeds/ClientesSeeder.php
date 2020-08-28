<?php

use App\Clientes;
use Illuminate\Database\Seeder;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Clientes::class, rand(2, 5))->create()->each(
        //     function ($cliente) {
        //         $cliente->animais()->saveMany(factory(Animais::class, rand(1, 3))->make());
        //     }
        // );
    }
}
