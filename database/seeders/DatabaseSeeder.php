<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(BudgetSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);


        $this->call(ReasonSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(StatusSeeder::class);

        // $this->call(RoleTableSeeder::class);

        $this->call(NationalitySeeder::class);
        $this->call(DisabilitySeeder::class);
        
        $this->call(GenderSeeder::class);

        // $this->call(CitySeeder::class);
        // $this->call(AreaSeeder::class);
        // $this->call(DepartmentSeeder::class);
        // $this->call(TeamSeeder::class);

        // $this->call(JobTitleSeeder::class);
        // $this->call(BudgetSeeder::class);
        // $this->call(AdminUserSeeder::class);
        // $this->call(UserSeeder::class);

        $this->call(CaseeSeeder::class);
        $this->call(ReferralSourceSeeder::class);
        // $this->call(VulnerabilitySeeder::class);
        $this->call(EmergencyTypeSeeder::class);
        
        //$this->call(PssCaseSeeder::class);
        //$this->call(ReferralSeeder::class);




        $this->call(MonthSeeder::class);
        //$this->call(RecordSeeder::class);
        //$this->call(RecordBeneficiarySeeder::class);

        // $this->call(CaseTypeSeeder::class);
        // $this->call(VulnerabilitySeeder::class);
        //$this->call(FollowUpSeeder::class);
        //$this->call(ServiceSeeder::class);
    }
}
