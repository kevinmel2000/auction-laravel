<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserDitelinUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname', 40)->nullable()->after('email');
            $table->string('lastname', 60)->nullable()->after('firstname');
            $table->enum('gender', ['male', 'female'])->nullable()->after('lastname');
            $table->date('age')->nullable()->after('gender');
            $table->boolean('admin')->nullable()->after('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
            $table->dropColumn('gender');
            $table->dropColumn('age');
            $table->dropColumn('admin');
        });
    }
}
