<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genero;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GeneroControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genero = factory(Genero::class)->create();
        $response = $this->get(route('generos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([ $genero->toArray()]);
    }

    public function testShow()
    {
        $genero = factory(Genero::class)->create();
        $response = $this->get(route('generos.show', [ 'genero' => $genero->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genero->toArray());
    }

    public function testInvalidationData() {
        $response = $this->json('POST', route('generos.store'), []);

        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('generos.store'), [
            'nome' => str_repeat('a', 256),
            'is_active' => 'a'
        ]);

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);

        $genero = factory(Genero::class)->create();

        $response = $this->json('PUT',
            route('generos.update',
                [ 'genero' => $genero->id]),
            []);

        $this->assertInvalidationRequired($response);

        $response = $this->json(
            'PUT',
            route('generos.update',
                [ 'genero' => $genero->id]),
            [
                'nome' => str_repeat('a', 256),
                'is_active' => 'a'

            ]);

        $this->assertInvalidationMax($response);
        $this->assertInvalidationBoolean($response);


    }

    public function testStore() {

        $response = $this->json('POST', route('generos.store'), [
            'nome' => 'test'
        ]);

        $id = $response->json('id');

        $genero = Genero::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genero->toArray());
        $this->assertTrue($response->json('is_active'));


        $response = $this->json('POST', route('generos.store'), [
            'nome' => 'test',
            'is_active' => false

        ]);

        $response->assertJsonFragment([
            'nome' => 'test',
            'is_active' => false
        ]);

    }

    public function testUpdate() {

        $genero = factory(Genero::class)->create([
            'nome' => 'augusto',
            'is_active' => false
        ]);

        $response = $this->json(
            'PUT',
            route('generos.update', ['genero' => $genero->id]),
            [
                'nome' => 'test',
                'is_active' => true
            ]);



        $id = $response->json('id');

        $genero = Genero::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genero->toArray())
            ->assertJsonFragment([
                'nome'=> 'test',
                'is_active' => true
            ]);



    }

    public function testDelete() {

        $genero = factory(Genero::class)->create();

        $response = $this->json(
            'DELETE',
            route('generos.destroy', ['genero' => $genero->id]));

        $response->assertStatus(204);

        $this->assertNull(Genero::find($genero->id));
        $this->assertNotNull(Genero::withTrashed()->find($genero->id));
    }

    protected function assertInvalidationRequired(TestResponse $response) {

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nome'])
            ->assertJsonMissingValidationErrors(['is_active'])
            ->assertJsonFragment([
                \Lang::trans('validation.required', ['attribute' => 'nome'])

            ]);


    }

    protected function assertInvalidationMax(TestResponse $response) {

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nome' ])
            ->assertJsonFragment([
                \Lang::trans('validation.max.string', ['attribute' => 'nome', 'max' => 255])

            ]);
    }

    protected function assertInvalidationBoolean(TestResponse $response) {

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['is_active' ])
            ->assertJsonFragment([
                \Lang::trans('validation.boolean', ['attribute' => 'is active'])

            ]);


    }



}
