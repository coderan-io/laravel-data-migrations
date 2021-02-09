<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace Coderan\DataMigrations\Console\Commands;

use Illuminate\Database\Console\Migrations\InstallCommand as BaseInstallCommand;

/**
 * InstallCommand class.
 *
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class InstallCommand extends BaseInstallCommand
{
    /**
     * The console command name.
     */
    protected $name = 'migrate-data:install';

    /**
     * The console command description.
     */
    protected $description = 'Create the data migration repository';

}
