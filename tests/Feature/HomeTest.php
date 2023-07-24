<?php

namespace Tests\Feature;


use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomePageIsWorkingCorrectly(): void
    {
        $response = $this->get('/');

        // $response->assertStatus(200);
        $response->assertSeeText('Wellcome');
        // $response->assertSeeText('')
    }

    public function testContactPageIsWorkingCorrect()
    {
        $response = $this->get('/contact');
        $response->assertSeeText('This is contact');
    }
}
