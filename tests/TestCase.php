<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Sowl\JsonApi\Testing\DoctrineRefreshDatabase;
use Sowl\JsonApi\Testing\InteractWithDoctrineDatabase;

abstract class TestCase extends BaseTestCase
{
    use WithFaker;
    use DoctrineRefreshDatabase;
    use InteractWithDoctrineDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDoctrineDatabase();
        $this->interactsWithDoctrineDatabase();
    }
}
