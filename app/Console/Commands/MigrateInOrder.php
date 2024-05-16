<?php

namespace App\Console\Commands;

use Database\Factories\RolFactory;
use Illuminate\Console\Command;

class MigrateInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate_in_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra todas las tablas en el orden especificado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrations = [
            // documents_type
            '2024_04_29_145542_create_document_type_table.php',
            //Roles
            '2023_05_09_134430_000000_create_roles_table.php',
            //Users
            '2023_05_09_134423_000000_create_users_table.php',
            // People
            '2023_05_09_141532_000000_create_people_table.php',
            //Days
            '2023_06_06_141259_000000_create_days_table.php',
            //Components_type
            '2023_05_30_162926_000000_create_components_type_table.php',
            //Blocks
            '2023_06_05_131814_000000_create_blocks_table.php',
            //Failed jobs
            '2019_08_19_000000_create_failed_jobs_table.php',
            //Holidays
            '2023_10_04_145828_000000_create_holidays_table.php',
            //Password_reset_tokens
            '2014_10_12_100000_create_password_reset_tokens_table.php',
            //Personal_access_tokens
            '2019_12_14_000001_create_personal_access_tokens_table.php',
            //Years_quaters
            '2023_06_23_142958_000000_create_year_quarters_table.php',
            //Coordinations
            '2023_05_11_145508_000000_create_coordinations_table.php',
            //Offers
            '2023_06_05_203746_000000_create_offers_table.php',
            //Headquartes
            '2023_06_05_153258_000000_create_headquarters_table.php',
            //Environments
            '2023_06_05_153337_000000_create_environments_table.php',
            //Program_type
            '2023_05_26_010721_000000_create_program_type_table.php',
            //Programs
            '2023_05_26_010736_000000_create_programs_table.php',
            //quarters
            '2023_05_30_163015_000000_create_quarters_table.php',
            //Components
            '2023_05_30_163029_000000_create_components_table.php',
            //Skills
            '2023_06_15_145819_000000_create_skills_table.php',
            //Contracts
            '2023_05_11_150124_000000_create_contracts_table.php',
            //Working_hours
            '2023_05_11_150135_000000_create_working_hours_table.php',
            //Users_roles
            '2023_05_09_134440_000000_create_users_roles_table.php',
            //Conditions
            '2023_05_11_150115_000000_create_conditions_table.php',
            //Conditions_hours -
            '2023_08_01_154208_000000_create_condition_hours_table.php',
            //Teachers -
            '2023_05_11_150225_000000_create_teachers_table.php',
            //Components-teachers
            '2023_08_01_154031_000000_create_teachers_components_type_table.php',
            //Hours_teachers -
            '2023_08_25_142512_create_hours_teachers_table.php',
            //Conditions_teachers -
            '2023_05_11_162151_000000_create_conditions_teachers_table.php',
            //Study_sheets -
            '2023_06_07_192555_000000_create_study_sheets_table.php',
            //Events -
            '2023_06_23_192644_000000_create_events_table.php',
            // teaher Coordinations
            '2024_02_26_213244_create_teachers_coordinations_table.php',
            // environment_components
            '2024_02_26_123538_create_environment_components_table.php',
            // programs_components
            '2024_02_21_195601_create_components_programs_table.php',
            // environment_coordinations
            '2024_03_08_135239_create_environment_coordinations_table.php',
            // type_work_update
            '2024_04_02_201031_create_type_work_updates.php',
            // work_update
            '2024_04_02_202141_create_work_updates.php',


        ];

        foreach ($migrations as $migration) {
            $basePath = 'database/migrations/';
            $migrationName = trim($migration);
            $path = $basePath . $migrationName;
            if (file_exists($path)) {
                $this->call('migrate', ['--path' => $path]);
            } else {
                $this->warn("La migración {$migrationName} no existe.");
            }
        }

        //Seeders
        //DocumentsType
        $this->call('db:seed', ['--class' => 'DocumentsTypeSeeder']);
        //Rol
        $this->call('db:seed', ['--class' => 'RolSeeder']);
        //USer
        $this->call('db:seed', ['--class' => 'UserSeeder']);
        //UserRol
        $this->call('db:seed', ['--class' => 'UsersRolesSeeder']);
        //Persona
        $this->call('db:seed', ['--class' => 'PersonaSeeder']);
        //Day
        $this->call('db:seed', ['--class' => 'DaySeeder']);
        //Quarter
        $this->call('db:seed', ['--class' => 'QuarterSeeder']);
        //ComponetType
        $this->call('db:seed', ['--class' => 'ComponentTypeSeeder']);
        //Offer
        $this->call('db:seed', ['--class' => 'OfferSeeder']);
        //ProgramType
        $this->call('db:seed', ['--class' => 'ProgramTypeSeeder']);
        //Contract
        $this->call('db:seed', ['--class' => 'ContractSeeder']);
        //Condition
        $this->call('db:seed', ['--class' => 'ConditionSeeder']);
        //HourConditions
        $this->call('db:seed', ['--class' => 'HourConditionSeeder']);

        $this->info('Migraciones y seeders han sido ejecutados exitosamente!');

    }
}
