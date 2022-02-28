<?php

namespace Tests\Feature\TermsOfUse;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TermsOfUse extends TestCase
{
    use DatabaseTransactions;
    /**
     * @test
     */
    public function getTermsOfUse()
    {
        $response = $this->get('/app/terms-of-use');

        $response->assertJsonStructure([
            "success",
            "data" => [
                "terms_of_use"
            ]
        ]);
        $response->assertSuccessful();
    }
}
