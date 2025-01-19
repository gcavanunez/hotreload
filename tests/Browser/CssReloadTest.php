<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\BrowserTestCase;

use function Orchestra\Testbench\workbench_path;

class CssReloadTest extends BrowserTestCase
{
    #[Test]
    public function reloads_css(): void
    {
        $this->browse(function (Browser $browser) {
            $visit = $browser->visit('/')
                ->assertSee('Hotreload App');

            $visit->pause($this->waitingTimeMs());

            $this->editFile(workbench_path('resources', 'assets', 'css', 'app.css'), 'visible', 'hidden');

            $visit->waitUntilMissingText('Hotreload App');
        });
    }

    #[Test]
    public function loads_new_stylesheets(): void
    {
        $this->browse(function (Browser $browser) {
            $visit = $browser->visit('/')
                ->assertSee('Hotreload App');

            $visit->pause($this->waitingTimeMs());

            $this->addFile(workbench_path('resources', 'assets', 'css', 'other.css'), <<<'CSS'
            body {
                visibility: hidden !important;
            }
            CSS);

            $visit->waitUntilMissingText('Hotreload App')->assertDontSee('Hotreload App');
        });
    }
}
