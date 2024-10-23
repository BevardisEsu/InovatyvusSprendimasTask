<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTruckSubunitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck_subunits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_truck_id')->constrained('trucks')->onDelete('cascade');
            $table->foreignId('subunit_truck_id')->constrained('trucks')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('truck_subunits');
    }
}
