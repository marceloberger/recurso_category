<?php

namespace Tests\Feature\Models;

use App\Models\Genero;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneroTest extends TestCase
{
    use DatabaseMigrations;


    public function testList()
    {
        $genero = Genero::create([
            'nome' => 'test1'
        ]);

        $generos = Genero::all();

        $this->assertCount(1, $generos);

        $generoKey = array_keys($generos->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'nome',
                'is_active',
                'deleted_at',
                'created_at',
                'updated_at'
            ],
            $generoKey

        );
    }

    public function testCreate() {

        $genero = Genero::create([
            'nome' => 'test1'
        ]);

        $genero->refresh();

        $this->assertEquals(36, strlen($genero->id));
        $this->assertEquals('test1', $genero->nome);
        $this->assertTrue($genero->is_active);

        $genero = Genero::create([
            'nome' => 'test1',
            'is_active' => false
        ]);

        $this->assertFalse($genero->is_active);

        $genero = Genero::create([
            'nome' => 'test1',
            'is_active' => true
        ]);

        $this->assertTrue($genero->is_active);

    }

    public function testUpdate()
    {
        $genero = factory(Genero::class, 1)->create([
            'is_active' => false
        ])->first();

        $data = [
            'nome' => 'test_name_updated',
            'is_active' => true
        ];

        $genero->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value,$genero->{$key});

        }

    }

    public function testDelete() {
        $genero = factory(Genero::class)->create([
            'is_active' => false
        ]);

        $genero->delete();
        $this->assertNull(Genero::find($genero->id));

        $genero->restore();
        $this->assertNotNull(Genero::find($genero->id));


    }
}
