<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserUpdateFields extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('users', function (Blueprint $table) {
      $table->string('firstName')->nullable()->after('remember_token');
      $table->string('lastName')->nullable()->after('firstName');
      $table->longText('logo')->nullable()->after('lastName');
      $table->string('university_id')->nullable()->after('logo');
      $table->string('website')->nullable()->after('university_id');
      $table->string('phone')->nullable()->after('university_id');
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
      $table->dropColumn('firstName')->nullable();
      $table->dropColumn('lastName')->nullable();
      $table->dropColumn('logo')->nullable();
      $table->dropColumn('university_id')->nullable();
      $table->dropColumn('website')->nullable();
      $table->dropColumn('phone')->nullable();
    });
  }
}
