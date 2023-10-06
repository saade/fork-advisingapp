<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateAdmMaterializedView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-adm-materialized-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = DB::connection('pgsql');

        // Students

        $database->statement('DROP MATERIALIZED VIEW IF EXISTS students_local CASCADE;');

        $database->statement('CREATE MATERIALIZED VIEW students_local AS SELECT * FROM students;');

        $database->statement('CREATE UNIQUE INDEX idx_students_sisid ON students_local (sisid);');

        $database->statement('VACUUM ANALYZE students_local;');

        // Programs

        $database->statement('DROP MATERIALIZED VIEW IF EXISTS programs_local CASCADE;');

        $database->statement('CREATE MATERIALIZED VIEW programs_local AS SELECT * FROM programs;');

        $database->statement('CREATE INDEX idx_programs_sisid ON programs_local (sisid);');

        $database->statement('VACUUM ANALYZE programs_local;');

        // Enrollments

        $database->statement('DROP MATERIALIZED VIEW IF EXISTS enrollments_local CASCADE;');

        $database->statement('CREATE MATERIALIZED VIEW enrollments_local AS SELECT * FROM enrollments;');

        $database->statement('CREATE INDEX idx_enrollments_sisid ON enrollments_local (sisid);');

        $database->statement('VACUUM ANALYZE enrollments_local;');

        // Performance

        $database->statement('DROP MATERIALIZED VIEW IF EXISTS performance_local CASCADE;');

        $database->statement('CREATE MATERIALIZED VIEW performance_local AS SELECT * FROM performance;');

        $database->statement('CREATE INDEX idx_performance_sisid ON performance_local (sisid);');

        $database->statement('VACUUM ANALYZE performance_local;');
    }
}
