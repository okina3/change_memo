<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpotSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      DB::table('spots')->insert([
         //ユーザー１のダミーデータ
         [
            'user_id' => '1',
            'name' => 'ダミースポット1',
            'created_at' => now(),
            'updated_at' => now(),
         ],
         //ユーザー２のダミーデータ
         [
            'user_id' => '2',
            'name' => 'ダミースポット２',
            'created_at' => now(),
            'updated_at' => now(),
         ],
         //ユーザー３のダミーデータ
         [
            'user_id' => '3',
            'name' => 'ダミースポット３',
            'created_at' => now(),
            'updated_at' => now(),
         ],
         //ユーザー４のダミーデータ
         [
            'user_id' => '4',
            'name' => 'ダミースポット４',
            'created_at' => now(),
            'updated_at' => now(),
         ],
         //ユーザー５のダミーデータ
         [
            'user_id' => '5',
            'name' => 'ダミースポット５',
            'created_at' => now(),
            'updated_at' => now(),
         ],
      ]);
   }
}
