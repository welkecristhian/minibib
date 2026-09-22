<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class CrudTest extends DuskTestCase
{
    //use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    //php artisan dusk:chrome-driver --detect-chromedriver
    public function testLoginRecord()
    {
         $this->browse(function (Browser $browser) {
            //login do usuário
            $browser->visit('/')
                ->clickLink('Entrar')
                ->waitForText('Usuário')
                ->type('#loginUsuario', '11111')
                ->press('Login')
                ->AssertSee('Sair');
     });
    }


    public function testCreateRecord() {
         $this->browse(function (Browser $browser) {
        
            $browser->visit('/records/create')
                    ->select('tipo','Livro')
                    ->typeSlowly('autores','Jorge Amado')
                    ->typeSlowly('titulo','Capitães da Areia')
                    ->typeSlowly('desc_fisica','Formato: 14.00x21.00 cm; Páginas: 296')
                    ->type('editora','Companhia das Letras')
                    ->type('assunto','literatura brasileira; romance; crítica social')
                    ->type('local_publicacao','São Paulo')
                    ->type('edicao','1ª edição')
                    ->type('ano','2009')
                    ->select('idioma','pt_BR')
                    ->type('isbn','978-85-359-1403-4')
                    ->typeSlowly('issn','---')
                    ->press('@save_record')
                    ->assertPathIs('/records')
                    ->pause(3000);
        });
    }

    public function testUpdateRecord(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/records')
                    ->pause(2000)
                    ->click('@edit_record')
                    ->select('tipo','Livro')
                    ->assertSee('Edição de Cadastro')
                    ->clear('autores')
                    ->typeSlowly('autores','Jorge Amado')
                    ->clear('titulo')
                    ->typeSlowly('titulo','Gabriela, Cravo e Canela')
                    ->clear('desc_fisica')
                    ->typeSlowly('desc_fisica','Formato: 21x16x2 cm; Páginas: 336')
                    ->clear('editora')
                    ->type('editora','Companhia das Letras')
                    ->clear('assunto')
                    ->type('assunto','Literatura brasileira; romance; cultura baiana')
                    ->clear('local_publicacao')
                    ->type('local_publicacao','São Paulo')
                    ->clear('edicao')
                    ->type('edicao','1ª edição')
                    ->clear('ano')
                    ->typeSlowly('ano','2008')
                    ->select('idioma','pt_BR')
                    ->clear('isbn')
                    ->typeSlowly('isbn','978-85-359-1227-6')
                    ->clear('issn')
                    ->typeSlowly('issn','---')
                    ->pause(1000)
                    ->press('@save_record')
                    ->pause(3000);
        });
    }


    public function testExemplarCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->click('@cadastrar_exemplar')
                    ->pause(300)
                    ->typeSlowly('tombo','909090')
                    ->typeSlowly('localizacao','estante 3.v4')
                    ->pause(1000)
                    ->press("@save_instance")
                    ->pause(3000);
        });
    }

    public function testExemplarUpdate(){
        $this->browse(function (Browser $browser) {
            $browser->click('@instance')
                    ->click('@edit_instance')
                    ->typeSlowly('tombo','12345678')
                    ->typeSlowly('localizacao','estante 3.v1')
                    ->press("@save_instance")
                    ->pause(2000)
                    ->click('@instance')
                    ->click("@emprestar_material")
                    ->pause(2000)
                    ->typeSlowly("n_usp","16816232")
                    ->click("@confirmar_emprestimo")
                    ->visit('/emprestimos')
                    ->waitForText('Emprestados')
                    ->pause(100)
                    ->click("@devolver_exemplar")
                    ->pause(1000)
                    ->click("@confirmar_devolucao")
                    ->pause(1000)
                    ->visit('/records')
                    ->pause(3000)
                    ->click("@delete_exemplar")
                    ->acceptDialog()
                    ->pause(3000);
        });
    }

    public function testDeleteRecord()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/records')
                    ->click('@delete_record')
                    ->acceptDialog()
                    ->pause(3000)
                    ->assertPathIs('/records');
        });
    }
}