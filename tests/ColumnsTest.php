<?php

namespace Oobook\Database\Eloquent\Tests;

use Oobook\Database\Eloquent\Tests\TestModels\Company;
use Oobook\Database\Eloquent\Tests\TestModels\User;

class ColumnsTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_should_have_name_column()
    {
        $model = new Company();

        $columns = $model->getColumns();

        $this->assertContains('name', $columns);
    }

    /** @test */
    public function it_should_have_timestamp_columns()
    {
        $model = new Company();

        $columns = $model->getTimestampColumns();

        $this->assertContains('created_at', $columns);
        $this->assertContains('updated_at', $columns);
    }

    /** @test */
    public function it_should_have_company_id_column()
    {
        $model = new User();

        $this->assertTrue($model->hasColumn('company_id'));
    }

    /** @test */
    public function it_should_does_not_have_user_id_column()
    {
        $model = new User();

        $this->assertFalse( $model->hasColumn('user_id') );
    }

    /** @test */
    public function it_should_be_soft_deleteable()
    {
        $model = new User();

        $this->assertTrue( $model->isSoftDeletable() );
    }

    /** @test */
    public function it_should_be_not_soft_deleteable()
    {
        $model = new Company();

        $this->assertFalse( $model->isSoftDeletable() );
    }
}
