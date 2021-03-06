<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentLibraries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{ 
        Schema::dropIfExists('payment_libraries');  

        Schema::create('payment_libraries', function($t)
        {
            $t->increments('id');
            $t->timestamps();            

            $t->string('name');
            $t->boolean('visible')->default(true);
        }); 

		DB::table('payment_libraries')->insert(['name' => 'Omnipay']);
		DB::table('payment_libraries')->insert(['name' => 'PHP-Payments']);

		Schema::table('gateways', function($table)
		{
			$table->unsignedInteger('payment_library_id')->default(1);
		});

		DB::table('gateways')->update(['payment_library_id' => 1]);

		Schema::table('gateways', function($table)
		{
            $table->foreign('payment_library_id')->references('id')->on('payment_libraries')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('gateways', 'payment_library_id'))
		{
			Schema::table('gateways', function($table)
			{
				$table->dropForeign('gateways_payment_library_id_foreign');
				$table->dropColumn('payment_library_id');
			});
		}

        Schema::dropIfExists('payment_libraries');  
	}
	
}