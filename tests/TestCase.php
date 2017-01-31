<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class TestCase extends Laravel\Lumen\Testing\TestCase {
    use DatabaseMigrations;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication() {
        putenv('DB_DEFAULT_CONNECTION=testing');
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Mock a class and use the mock in the current request
     *
     * @param string $class - fully qualified name of the class to mock
     * @return \Mockery\MockInterface
     */
    protected function mock($class) {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);
        return $mock;
    }

}
