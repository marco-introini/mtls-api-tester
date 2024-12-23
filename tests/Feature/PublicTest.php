<?php

beforeEach(function (): void {
    \Pest\Laravel\withoutVite();
});

it('has public page')
    ->get('/')
    ->assertOk();

it('has GitHub link')
    ->get('/')
    ->assertSee('Github');
