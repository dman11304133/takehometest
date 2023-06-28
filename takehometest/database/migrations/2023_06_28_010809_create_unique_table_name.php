<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {

        // Create FundManagers table
        Schema::create('fund_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        // Create Funds table
        Schema::create('funds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('start_year');
            $table->unsignedInteger('manager_id');
            $table->timestamps();

            $table->foreign('manager_id')->references('id')->on('fund_managers');
        });



        // Create Aliases table
        Schema::create('aliases', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fund_id');
            $table->string('alias');
            $table->timestamps();

            $table->foreign('fund_id')->references('id')->on('funds')->onDelete('cascade');
        });

        // Create Companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        // Create FundCompanyInvestments table
        Schema::create('fund_company_investments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fund_id');
            $table->unsignedInteger('company_id');
            $table->timestamps();

            $table->foreign('fund_id')->references('id')->on('funds');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('fund_company_investments');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('aliases');
        Schema::dropIfExists('fund_managers');
        Schema::dropIfExists('funds');
    }
};
