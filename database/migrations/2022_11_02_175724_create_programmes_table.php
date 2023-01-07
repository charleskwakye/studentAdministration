<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert some data
        DB::table('programmes')->insert(
            [
                [
                    'name' => 'IT Factory'
                ],
                [
                    'name' => 'Office Management'
                ],
                [
                    'name' => 'Business and Tourism'
                ],
                [
                    'name' => 'Media and Communication'
                ],
                [
                    'name' => 'People & Health'
                ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programmes');
    }
};
