<?php

use Illuminate\Database\Seeder;

class ProsesPengadaan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
    {
        \App\prosespengadaan::insert([
        	[
        		'id_pegawai' => 1,
        		'id_ppbj' => 1,
        		'created_at'      => \Carbon\Carbon::now('Asia/Jakarta')
        	],
        ]);
    }
}
