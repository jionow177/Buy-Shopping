<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'referral_code')) {

            Schema::table('users', function (Blueprint $table) {
                $table->integer('referral_code')->default(0)->after('storage_limit');
                $table->integer('used_referral_code')->default(0)->after('referral_code');
                $table->integer('commission_amount')->default(0)->after('used_referral_code');
            });
        }
        if (Schema::hasColumn('users', 'referral_code')){
            $users = DB::table('users')->where('type','Owner')->get();
            foreach ($users as $user) {
                do {
                    $refferal_code = rand(100000 , 999999);
                    $checkCode = DB::table('users')->where('type','Owner')->where('referral_code', $refferal_code)->get();
                } while ($checkCode->count());
                
                DB::table('users')->where('id', $user->id)->update(['referral_code' => $refferal_code]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
