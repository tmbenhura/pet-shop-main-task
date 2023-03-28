<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use AjCastro\ScribeTdd\Tests\ScribeTddSetup;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use ScribeTddSetup;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpScribeTdd();
    }
}
