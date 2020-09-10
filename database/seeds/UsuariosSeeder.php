<?php

use App\Animais;
use App\Clientes;
use App\Consultas;
use App\User;
use App\Usuarios;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Usuarios::class, 10)->create()->each(
            function ($usuario) {
                $usuario->clientes()->saveMany(factory(Clientes::class, rand(3, 10))->create()->each(
                    function ($cliente) {
                        $cliente->animais()->saveMany(factory(Animais::class, rand(1, 3))->create()->each(
                            function ($animal) {
                                $animal->consultas()->saveMany(factory(Consultas::class, rand(1, 5))->make());
                            }
                        ));
                    }
                ));
            }
        );
    }
}
