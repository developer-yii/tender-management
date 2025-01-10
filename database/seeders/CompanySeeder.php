<?php

namespace Database\Seeders;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Company::where('name', 'JonDos GmbH')->first() == null){
            DB::table('companies')->insert([
                'name' => 'JonDos GmbH',
                'type' => 'Full-Service Digitalagentur',
                'address' => 'Eschenauer Straße 10, 90411 Nürnberg, Deutschland',
                'managing_director' => 'Markus Mathes',
                'bank_name' => 'bunq Niederlassung Deutschland',
                'iban_number' => 'DE74 3701 9000 1010 3077 06',
                'bic_number' => 'BUNQDE82XXX',
                'vat_id' => 'DE814839010',
                'trade_register' => 'Nürnberg, HRB 38425',
                'email' => 'info@jondos.de',
                'phone' => '+49 911 8962 2083',
                'website_url' => 'https://jondos.de/ ',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
