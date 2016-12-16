<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class adminBaseDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456789'),
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Accounts',
            'url' => 'accounts',
            'description' => '帳號管理',
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Profile',
            'url' => 'profile',
            'description' => '個人資料',
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Settings',
            'url' => 'settings',
            'description' => '設定參數',
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Web Logs',
            'url' => 'weblogs',
            'description' => '後台記錄',
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Menu Groups',
            'url' => 'menugroups',
            'description' => '目錄群組',
        ]);
        DB::table('menuitems')->insert([
            'name' => 'Menu Items',
            'url' => 'menuitems',
            'description' => '目錄項目',
        ]);
        DB::table('menugroups')->insert([
            'name' => 'Default Menu Group',
            'description' => '預設目錄群組',
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 1,
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 2,
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 3,
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 4,
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 5,
        ]);
        DB::table('menulinks')->insert([
            'group_id' => 1,
            'user_id' => 1,
            'menu_id' => 6,
        ]);
        DB::table('settings')->insert([
            'name' => 'MasterAccountID',
            'data' => 1,
            'description' => '預設管理帳號ID',
        ]);
        DB::table('settings')->insert([
            'name' => 'MasterGroupID',
            'data' => 1,
            'description' => '預設群組ID',
        ]);
    }
}
