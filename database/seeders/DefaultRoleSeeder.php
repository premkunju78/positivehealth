<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Models\Role;
use App\Models\Permission;

class DefaultRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');


      $this->create($this->roles(), $this->map());

      DB::table('role_user')->insert([
      ['user_id' => 1, 'role_id' => 1],
      ['user_id' => 4, 'role_id' => 4],
    ]);

      DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }


    private function roles()
    {
        $rows = [
            'admin'=>[
                'users' =>'c,v,e,d',
                'roles' => 'c,v,e,d',
                'alliance-partners' => 'c,v,e,d',
                'clients' => 'c,v,e,d',
                'video'=>'c,v,e,d',
                'group'=>'c,v,e,d',
                'recipes'=>'c,v,e,d',
                'diet-plan'=>'c,v,e,d',
                'reminders'=>'c,v,e,d',
                'dashboard' => 'c,v,e,d',
                'questionnaire' => 'c,v,e,d',
                'availability' =>'v',
                'appointments' => 'c,v,e,d',
                'schedules' => 'v',
                'voice-call' => 'c',
                'program' => 'c,v,e,d',
                'case-sheet' => 'c,v',
                'investigation-config' => 'c,v,e,d',
                'investigation' => 'c,v,e,d',
                'prescription' => 'c,v,e,d',
                'accounts' => 'v',
                'affirmation' => 'c,v,e,d',
                'lead' => 'v',
                'discount-coupons'=> 'c,v,e,d'
                
            ],
            'alliance-partner'=>[
                'users' =>'v',
                'group'=>'c,v,e,d',
                'clients' => 'c,v,e,d',
                'reminders'=>'c,v,e,d',
                'dashboard' => 'c,v,e,d',
                'questionnaire' => 'v,e',
                'pkgs' => 'v',
                'leads' => 'c,v,e,d',
                'consultations' => 'v',
                'client-consultants' => 'v'
            ],
            'client'=>[
                'recipes'=>'c,v,e,d',
                'diet-plan'=>'c,v,e,d',
                'reminders'=>'c,v,e,d',
                'dashboard' => 'c,v,e,d',
                'questionnaire' => 'v,e',
                'video-meeting' => 'v',
                'video'=>'v',
                'booking' => 'c,v,e,d',
                'diet-plan'=>'v',
                'pkgs' => 'v',
                'recipes'=>'v',
                'investigation-config' => 'v',
                'investigation' => 'v',
                'prescription' => 'v',
                'consultations' => 'v',
            ],
            'doctor'=>[
                'recipes'=>'c,v,e,d',
                'diet-plan'=>'c,v,e,d',
                'dashboard' => 'c,v,e,d',
                'questionnaire' => 'c,v,e,d',
                'appointments' => 'c,v,e,d',
                'schedules' => 'v',
                'video-meeting' => 'c,v,e',
                'program' => 'v',
                'case-sheet' => 'c,v',
                'clients' => 'v',
                'investigation' => 'c,v,e,d',
                'prescription' => 'c,v,e,d',
                'leads' => 'c,v,e,d',
            ],
        ];

        return $rows;
    }

    private function map()
    {
        $rows = [
            'c' => 'create',
            'v' => 'view',
            'e' => 'edit',
            'd' => 'delete'
        ];

        return $rows;
    }

       /**
     * @param $roles
     * @param $map
     */
    private function create($roles, $map)
    {
        $mapPermission = collect($map);

        foreach ($roles as $key => $modules) {
            // Create a new role
            $role = Role::updateOrCreate(
                [
                    'name' => $key
                ],
                [
                    'name' => $key,
                    'label' => ucwords(str_replace("_", " ", $key)),
                    'description' => ucwords(str_replace("_", " ", $key)),
                    'color' => "#1CA085",
                    'status' => 1

                ]
            );

            $this->command->info('Creating Role '. strtoupper($key));
            $i=0;
            $permission = [];
            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);
                foreach ($permissions as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);
                    $moduleName = ucwords(str_replace("-", " ", $module));
                    $moduleFullName = explode('.',$module);
                    $moduleName = ucwords($moduleFullName[0]);
                    $permission[] = Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $moduleFullName[0],
                        'label' => ucfirst($permissionValue) . ' ' . $moduleName,
                        'description' => ucfirst($permissionValue) . ' ' . $moduleName,
                    ])->id;
                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $moduleName);
                }
            }

            $role->permissions()->sync($permission);
        }
    }
}