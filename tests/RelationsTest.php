<?php

namespace Oobook\Database\Eloquent\Tests;

use Oobook\Database\Eloquent\Tests\TestModels\Company;
use Oobook\Database\Eloquent\Tests\TestModels\User;

class RelationsTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_should_be_all_relations_of_defined_relations_of_company()
    {
        $company = new Company();

        $this->assertTrue(json_encode($company->definedRelations()) == json_encode(['users']) );
    }

    /** @test */
    public function it_should_be_has_many_of_defined_relations_of_company()
    {
        $company = new Company();

        $this->assertTrue(json_encode($company->definedRelations('HasMany')) == json_encode(['users']) );
    }

    /** @test */
    public function it_should_be_morph_many_of_defined_relations_of_user()
    {
        $model = new User();

        $this->assertTrue(json_encode($model->definedRelations('morphMany')) == json_encode(['files']) );
    }

}
