<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('StatusSeeder');
		$this->call('PaymentFormsSeeder');
		$this->call('DocsSeriesSeeder');
		$this->call('UserTableSeeder');



		$this->call('CategoriesTableSeeder');

		// $item = factory('App\Item','Servicio',100)->create();
		// $item = factory('App\Item','Mobiliario',50)->create();

		// $this->call('ClassTableSeeder');
		$this->call('TransactionCatalogueTableSeeder');
		$this->call('BankAccountTypesSeeder');

		$this->call('AlmacenTableSeeder');
		$this->call('BankAccountsTableSeeder');
		$this->call('TutaposSettingTableSeeder');

		$this->call('ItemsTableSeeder');
		$this->call('ItemPricesSeeder');

		$this->call('MoneySeeder');
        $this->call('TypeExpenses');
		$this->call('NewPermissionsTableSeeder');
        $this->call('PermissionsRolesSeeder');
		$this->call('GeneralParametersSeeder');
		$this->call('TypeProyectSeeder');
		Model::reguard();

	}

}
