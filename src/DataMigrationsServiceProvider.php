<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>, Eran Machiels <dev@eranmachiels.nl>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente, Eran Machiels
 * @version     1.0
 */

namespace Coderan\DataMigrations;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Coderan\DataMigrations\Console\Commands\InstallCommand as MigrateInstallCommand;
use Coderan\DataMigrations\Console\Commands\MakeMigrateDataCommand;
use Coderan\DataMigrations\Console\Commands\MigrateDataCommand;
use Coderan\DataMigrations\Console\Commands\RollbackDataCommand;
use Coderan\DataMigrations\Repositories\DatabaseDataMigrationRepository;

/**
 * DataMigrationsServiceProvider class.
 *
 * @author José Lorente <jose.lorente.martin@gmail.com>
 * @author Eran Machiels <dev@eranmachiels.nl>
 */
class DataMigrationsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected array $provides = [
        'migrator.data',
        'migration.data.repository'
    ];

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected array $commands = [
        'command.migrate-data',
        'command.migrate-data.install',
        'command.migrate-data.rollback',
        'command.migrate-data.make'
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerConfig();
            $this->registerFolder();
        }
    }

    /**
     * Registers the config file for the package.
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../assets/config/data-migrations.php' => $this->app->configPath('data-migrations.php'),
        ], 'data-migrations');
    }

    /**
     * Registers the default folder of where the data migrations will be created.
     */
    protected function registerFolder(): void
    {
        $this->publishes([
            __DIR__ . '/../assets/database/migrations_data' => $this->app->databasePath('migrations_data'),
        ], 'data-migrations');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->bindRepository();
        $this->bindMigrator();
        $this->bindArtisanCommands();

        $this->commands($this->commands);
    }

    /**
     * Binds the repository used by the data migrations.
     */
    protected function bindRepository(): void
    {
        $this->app->singleton('migration.data.repository', function ($app) {
            $table = $app['config']->get('data-migrations.table');

            return new DatabaseDataMigrationRepository($app['db'], $table);
        });
    }

    /**
     * Binds the migrator used by the data migrations.
     */
    protected function bindMigrator(): void
    {
        $this->app->singleton('migrator.data', function ($app) {
            $repository = $app['migration.data.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });
    }

    /**
     * Binds the commands to execute the data migrations.
     */
    protected function bindArtisanCommands(): void
    {
        $this->app->singleton('command.migrate-data', function ($app) {
            return new MigrateDataCommand($app['migrator.data'], $this->app->make(Dispatcher::class));
        });
        $this->app->singleton('command.migrate-data.install', function ($app) {
            return new MigrateInstallCommand($app['migration.data.repository']);
        });
        $this->app->singleton('command.migrate-data.rollback', function ($app) {
            return new RollbackDataCommand($app['migrator.data']);
        });
        $this->app->singleton('command.migrate-data.make', function ($app) {
            // Once we have the migration creator registered, we will create the command
            // and inject the creator. The creator is responsible for the actual file
            // creation of the migrations, and may be extended by these developers.
            $creator = $app['migration.creator'];

            $composer = $app['composer'];

            return new MakeMigrateDataCommand($creator, $composer);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return array_merge($this->provides, $this->commands);
    }

}
