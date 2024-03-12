<?php

namespace Database\Seeders;

use App\Models\AlliancePartner;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::updateOrCreate(
      [
        'id' => 1,
      ],
      [
        'name' => 'Prajana Admin',
        'first_name' => 'Prajana',
        'last_name' => 'Admin',
        'email' => 'admin@prajana.com',
        'password' => Hash::make('admin123'),
        'email_verified_at' => date('Y-m-d H:i:s'),
        'phone' => '9995553654',
        'role_id' => 1
      ]
    );

    User::updateOrCreate(
      [
        'id' => 2,
      ],
      [
        'name' => 'ALLIANCE PARTNER',
        'first_name' => 'ALLIANCE',
        'last_name' => 'PARTNER',
        'email' => 'alliancepartner@prajana.com',
        'password' => Hash::make('test123'),
        'email_verified_at' => date('Y-m-d H:i:s'),
        'phone' => 9999999991,
        'role_id' => 2,
      ]
    );

    AlliancePartner::updateOrCreate(
      [
        'id' => 1,
      ],
      [
        'user_id' => 2,
        'url' => 'alliancepartner.com',
        'company_name' => 'PARTNER COMPANY',
        'welcome_message' => 'WELCOME MESSAGE',
        'website_name' =>'ALLIANCE PARTNER WEBSITE',
        'group' => 'Doctor Group',
        'type' => 'Doctors',
        'address' => 'ALLIANCE PARTNER ADDRESS',
        'pincode' => 45435435,
        'company_contact' => 9999999994
      ]
    );

    UserDetail::updateOrCreate(
      [
        'id' => 1
      ],
      [
        'user_id' => 2,
      ]
    );

    User::updateOrCreate(
      [
        'id' => 3,
      ],
      [
        'name' => 'ALLIANCE PARTNER CLIENT',
        'first_name' => 'ALLIANCE PARTNER',
        'last_name' => 'CLIENT',
        'email' => 'alliancepartnerclient@prajana.com',
        'password' => Hash::make('test123'),
        'email_verified_at' => date('Y-m-d H:i:s'),
        'phone' => 9999999992,
        'role_id' => 3,
      ]
    );

    UserDetail::updateOrCreate(
      [
        'id' => 2
      ],
      [
        'user_id' => 3,
      ]
    );

    DB::table('alian_client')->updateOrInsert(
      [
        'id' => 1
      ],
      [
      'aliance_id' => 2,
      'client_id' => 3,
      'referred' => 1
      ]
    );

    User::updateOrCreate(
      [
        'id' => 4,
      ],
      [
        'name' => 'JOHN',
        'first_name' => 'JOHN',
        'last_name' => 'DOE',
        'email' => 'john@gmail.com',
        'password' => Hash::make('test123'),
        'email_verified_at' => date('Y-m-d H:i:s'),
        'phone' => 9999999993,
        'role_id' => 4,
      ]
    );
    UserDetail::updateOrCreate(
      [
        'id' => 3
      ],
      [
        'user_id' => 4,
        'specialization' => 'Medical',
        'authorization'=> 'Internal',
        'specialized_in' => 'Dental',
        'cost_percent' => 5
      ]
    );

  }
}
