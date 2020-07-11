<?php

use Illuminate\Database\Seeder;

class PersonaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Administradores*/
        DB::table('Persona')->insert(['UnidadAcademica' => 1,'Rol' => 1,'CI' => '001122334455','ApellidoPaterno' => 'Del','ApellidoMaterno' => 'Sistema','Nombre' => 'Administrador','Persona' => 'Administrador del Sistema','email' => 'admin@change.me','password' => bcrypt('secret'),'Activo' => 1]);
        DB::table('Persona')->insert(['UnidadAcademica' => 1,'Rol' => 1,'CI' => '6137801','ApellidoPaterno' => 'Chavez','ApellidoMaterno' => 'Gonzales','Nombre' => 'Ronald','Persona' => 'Ronald Chavez Gonzales','email' => 'rchavezg@adm.emi.edu.bo','password' => bcrypt('secret'),'Activo' => 1]);
    }
}
