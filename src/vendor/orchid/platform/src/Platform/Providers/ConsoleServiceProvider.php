<?php

declare(strict_types=1);

namespace Orchid\Platform\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Commands\AdminCommand;
use Orchid\Platform\Commands\ChartCommand;
use Orchid\Platform\Commands\FilterCommand;
use Orchid\Platform\Commands\InstallCommand;
use Orchid\Platform\Commands\ListenerCommand;
use Orchid\Platform\Commands\PresenterCommand;
use Orchid\Platform\Commands\PublishCommand;
use Orchid\Platform\Commands\RowsCommand;
use Orchid\Platform\Commands\ScreenCommand;
use Orchid\Platform\Commands\SelectionCommand;
use Orchid\Platform\Commands\TableCommand;
use Orchid\Platform\Commands\TabMenuCommand;
use Orchid\Platform\Dashboard;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The available command shortname.
     *
     * @var array
     */
    protected $commands = [

    ];

    public function boot(): void
    {
    }

    /**
     * Register migrate.
     *
     * @return $this
     */
    protected function registerMigrationsPublisher(): self
    {


        return $this;
    }

    /**
     * Register translations.
     *
     * @return $this
     */
    public function registerTranslationsPublisher(): self
    {


        return $this;
    }

    /**
     * Register views & Publish views.
     *
     * @return $this
     */
    public function registerViewsPublisher(): self
    {

        return $this;
    }

    /**
     * Register config.
     *
     * @return $this
     */
    protected function registerConfigPublisher(): self
    {


        return $this;
    }

    /**
     * Register orchid.
     *
     * @return $this
     */
    protected function registerOrchidPublisher(): self
    {


        return $this;
    }

    /**
     * Register the asset publishing configuration.
     *
     * @return $this
     */
    protected function registerAssetsPublisher(): self
    {


        return $this;
    }
}
