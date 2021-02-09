<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace Coderan\DataMigrations\Console\Commands;

use Illuminate\Database\Console\Migrations\RollbackCommand;
use Coderan\DataMigrations\Console\Traits\DataMigrationCommandTrait;

/**
 * RollbackDataCommand class.
 *
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class RollbackDataCommand extends RollbackCommand
{
    use DataMigrationCommandTrait;

    /**
     * The name and signature of the console command.
     */
    protected $name = 'migrate-data:rollback';

    /**
     * The console command description.
     */
    protected $description = 'Rollback the last database data migration';

}
