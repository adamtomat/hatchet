<?php

namespace Rareloop\Hatchet\Test\Commands;

use PHPUnit\Framework\TestCase;
use Rareloop\Hatchet\Commands\ControllerMake;
use Rareloop\Hatchet\Hatchet;
use Rareloop\Hatchet\Test\Unit\Commands\CommandTestTrait;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ControllerMakeTest extends TestCase
{
    use CommandTestTrait;

    /** @test */
    public function can_create_a_command_with_a_name()
    {
        $app = $this->appWithMockBasePath();
        $hatchet = $app->make(Hatchet::class);
        $hatchet->console()->add($app->make(ControllerMake::class));

        // The app class does not exist because it's part of the theme. We don't really care
        // that this class exists, we just care that the file is generated extending the right class.
        // So we are just aliasing the base controller
        class_alias('Rareloop\\Lumberjack\\Http\\Controller', 'App\\Http\\Controllers\\Controller');

        $output = $this->callHatchetCommand($hatchet, 'make:controller', [
            'name' => 'MyController',
        ]);

        // Assert the file was created
        $relativePath = 'app/Http/Controllers/MyController.php';
        $this->assertMockPath($relativePath);
        $this->assertNotContains('DummyController', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $controller = new \App\Http\Controllers\MyController;
        $this->assertInstanceOf(\App\Http\Controllers\MyController::class, $controller);
        $this->assertInstanceOf(\App\Http\Controllers\Controller::class, $controller);
    }
}
