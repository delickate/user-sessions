<?php 

namespace Delickate\UserSessions\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallAuditTriggers extends Command
{
    protected $signature = 'user-sessions:install-triggers {--drop}';
    protected $description = 'Install database audit triggers';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];

            if ($this->shouldIgnore($tableName)) {
                continue;
            }

            if ($this->option('drop')) {
                $this->dropTriggers($tableName);
            } else {
                $this->createTriggers($tableName);
            }
        }

        $this->info('Audit triggers processed successfully.');
    }

    protected function shouldIgnore($table)
	{
	    return in_array($table, [
	        'migrations',
	        'password_resets',
	        'sessions',
	        'db_audit_logs',
	        'user_sessions',
	        'user_session_activities',
	    ]);
	}

	protected function getColumns($table)
	{
	    return DB::select("SHOW COLUMNS FROM {$table}");
	}

	protected function jsonFields($columns, $prefix)
	{
	    return collect($columns)->map(function ($col) use ($prefix) {
	        return "'{$col->Field}', {$prefix}.{$col->Field}";
	    })->implode(",\n");
	}

	protected function createTriggers($table)
	{
	    $columns = $this->getColumns($table);
	    $jsonOld = $this->jsonFields($columns, 'OLD');
	    $jsonNew = $this->jsonFields($columns, 'NEW');

	    DB::unprepared("
	        CREATE TRIGGER audit_{$table}_update
	        BEFORE UPDATE ON {$table}
	        FOR EACH ROW
	        INSERT INTO db_audit_logs
	        SET
	          table_name = '{$table}',
	          operation = 'update',
	          before = JSON_OBJECT({$jsonOld}),
	          after = JSON_OBJECT({$jsonNew}),
	          executed_at = NOW();
	    ");
	}



}
