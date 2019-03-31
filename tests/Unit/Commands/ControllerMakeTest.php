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

        $output = $this->callHatchetCommand($hatchet, 'make:controller', [
            'name' => 'MyController',
        ]);

        // Assert the file was created
        $relativePath = 'app/Http/Controllers/MyController.php';
        $this->assertMockPath($relativePath);
        $this->assertContains('MyController', $this->getMockFileContents($relativePath));
        $this->assertNotContains('DummyController', $this->getMockFileContents($relativePath));
        $this->assertContains('extends Controller', $this->getMockFileContents($relativePath));
        $this->assertContains('use Rareloop\\Lumberjack\\Http\\Controller;', $this->getMockFileContents($relativePath));
        $this->requireMockFile($relativePath);

        // Assert we can instantiate it and make inferences on it's properties
        $controller = new \App\Http\Controllers\MyController;
        $this->assertInstanceOf(\App\Http\Controllers\MyController::class, $controller);
    }
}
