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
    public function it_should_match_defined_relations_of_models()
    {
        $model = new User();

        $this->assertEquals($model->definedRelations('morphMany'), ['files']);

        $company = new Company();

        $this->assertEquals($company->definedRelations(), ['users']);
        $this->assertEquals($company->definedRelations('HasMany'), ['users'] );
    }

    /** @test */
    public function it_should_match_defined_relation_types_of_models()
    {
        $model = new User();

        $this->assertEquals($model->definedRelationsTypes(), ['company' => 'BelongsTo', 'files' => 'MorphMany']);
        $this->assertEquals($model->definedRelationsTypes('BelongsTo'), ['company' => 'BelongsTo']);

        $model = new Company();

        $this->assertEquals($model->definedRelationsTypes(), ['users' => 'HasMany']);
        $this->assertEquals($model->definedRelationsTypes('BelongsTo'), []);
    }
}
