<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddCountryCodeAndRoleToCompanyAddresses extends Migration {
    public function up(){ Schema::table('company_addresses', function(Blueprint $table){ $table->string('country_code',2)->nullable()->after('country'); $table->string('address_role',20)->nullable()->after('company_id'); }); }
    public function down(){ Schema::table('company_addresses', function(Blueprint $table){ $table->dropColumn(['country_code','address_role']); }); }
}
