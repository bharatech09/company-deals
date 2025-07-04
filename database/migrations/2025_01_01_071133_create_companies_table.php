<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('urn')->nullable();
            $table->string('type_of_entity')->nullable();
            $table->string('name')->nullable(); 
            $table->string('name_prefix')->nullable();
            $table->string('cin_llpin')->nullable();
            $table->string('roc')->nullable();
            $table->string('year_of_incorporation')->nullable();
            $table->string('industry')->nullable();
            $table->string('have_gst')->nullable();
            $table->string('gst')->nullable();
            $table->integer('current_market_price')->nullable();
            $table->integer('high_52_weeks')->nullable();
            $table->integer('low_52_weeks')->nullable();
            $table->integer('promoters_holding')->nullable();
            $table->integer('transferable_holding')->nullable();
            $table->integer('public_holding')->nullable();
            $table->integer('market_capitalization')->nullable();
            $table->string('market_capitalization_unit')->nullable();
            $table->integer('market_capitalization_amount')->nullable();
            $table->string('trading_conditions')->nullable();
            $table->string('acquisition_method')->nullable();
            $table->integer('face_value')->nullable();
            $table->string('bse_main_board_group')->nullable();
            $table->integer('no_of_directors')->nullable();
            $table->integer('no_of_promoters')->nullable();
            $table->integer('demat_shareholding')->nullable();
            $table->integer('physical_shareholding')->nullable();

            $table->integer('authorised_capital')->nullable();
            $table->string('authorised_capital_unit')->nullable();
            $table->bigInteger('authorised_capital_amount')->nullable();

            $table->integer('paidup_capital')->nullable();
            $table->string('paidup_capital_unit')->nullable();
            $table->bigInteger('paidup_capital_amount')->nullable();

            $table->integer('activity_code')->nullable();

            $table->string('type_of_NBFC')->nullable();
            $table->string('size_of_NBFC')->nullable();
            $table->string('authorized_capital')->nullable();
            $table->string('income_tax')->nullable();
           

            $table->integer('turnover_year1')->nullable();
            $table->integer('turnover1')->nullable();
            $table->string('turnover_unit1')->nullable();
            $table->bigInteger('turnover_amount1')->nullable();

            $table->integer('turnover_year2')->nullable();
            $table->integer('turnover2')->nullable();
            $table->string('turnover_unit2')->nullable();
            $table->bigInteger('turnover_amount2')->nullable();

            $table->integer('turnover_year3')->nullable();
            $table->integer('turnover3')->nullable();
            $table->string('turnover_unit3')->nullable();
            $table->bigInteger('turnover_amount3')->nullable();

            $table->integer('turnover_year4')->nullable();
            $table->integer('turnover4')->nullable();
            $table->string('turnover_unit4')->nullable();
            $table->bigInteger('turnover_amount4')->nullable();

            $table->integer('turnover_year5')->nullable();
            $table->integer('turnover5')->nullable();
            $table->string('turnover_unit5')->nullable();
            $table->bigInteger('turnover_amount5')->nullable();

            $table->integer('profit_year1')->nullable();
            $table->integer('profit1')->nullable();
            $table->string('profit_unit1')->nullable();
            $table->bigInteger('profit_amount1')->nullable();

            $table->integer('profit_year2')->nullable();
            $table->integer('profit2')->nullable();
            $table->string('profit_unit2')->nullable();
            $table->bigInteger('profit_amount2')->nullable();

            $table->integer('profit_year3')->nullable();
            $table->integer('profit3')->nullable();
            $table->string('profit_unit3')->nullable();
            $table->bigInteger('profit_amount3')->nullable();

            $table->integer('profit_year4')->nullable();
            $table->integer('profit4')->nullable();
            $table->string('profit_unit4')->nullable();
            $table->bigInteger('profit_amount4')->nullable();

            $table->integer('profit_year5')->nullable();
            $table->integer('profit5')->nullable();
            $table->string('profit_unit5')->nullable();
            $table->bigInteger('profit_amount5')->nullable();

            $table->integer('authorized_capital')->nullable();
            $table->string('authorized_capital_unit')->nullable();
            $table->bigInteger('authorized_capital_amount')->nullable();

            $table->integer('paid_up_capital')->nullable();
            $table->string('paid_up_capital_unit')->nullable();
            $table->bigInteger('paid_up_capital_amount')->nullable();

            $table->integer('net_worth')->nullable();
            $table->string('net_worth_unit')->nullable();
            $table->bigInteger('net_worth_amount')->nullable();

            $table->integer('reserve')->nullable();
            $table->string('reserve_unit')->nullable();
            $table->bigInteger('reserve_amount')->nullable();

            $table->integer('secured_creditors')->nullable();
            $table->string('secured_creditors_unit')->nullable();
            $table->bigInteger('secured_creditors_amount')->nullable();

            $table->integer('unsecured_creditors')->nullable();
            $table->string('unsecured_creditors_unit')->nullable();
            $table->bigInteger('unsecured_creditors_amount')->nullable();

            $table->integer('land_building')->nullable();
            $table->string('land_building_unit')->nullable();
            $table->bigInteger('land_building_amount')->nullable();

            $table->integer('plant_machinery')->nullable();
            $table->string('plant_machinery_unit')->nullable();
            $table->bigInteger('plant_machinery_amount')->nullable();

            $table->integer('investment')->nullable();
            $table->string('investment_unit')->nullable();
            $table->bigInteger('investment_amount')->nullable();

            $table->integer('debtors')->nullable();
            $table->string('debtors_unit')->nullable();
            $table->bigInteger('debtors_amount')->nullable();

            $table->integer('cash_bank')->nullable();
            $table->string('cash_bank_unit')->nullable();
            $table->bigInteger('cash_bank_amount')->nullable();

            $table->string('roc_status')->nullable();
            $table->integer('roc_year')->nullable();
            $table->string('income_tax_status')->nullable();
            $table->integer('income_tax_year')->nullable();
            $table->string('gst_status')->nullable();
            $table->integer('gst_year')->nullable();
            $table->string('rbi_status')->nullable();
            $table->integer('rbi_year')->nullable();
            $table->string('fema_status')->nullable();
            $table->integer('fema_year')->nullable();
            $table->string('sebi_status')->nullable();
            $table->integer('sebi_year')->nullable();
            $table->string('stock_exchange_status')->nullable();
            $table->integer('stock_exchange_year')->nullable();

            $table->string('certificate_incorporation')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('moa_aoa')->nullable();
            $table->string('llp_agreement')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('audited_fs1')->nullable();
            $table->string('audited_fs2')->nullable();
            $table->string('audited_fs3')->nullable();
            $table->string('audited_fs4')->nullable();
            $table->string('audited_fs5')->nullable();
            $table->string('rbi_certificate')->nullable();
            $table->string('other_document1')->nullable();
            $table->string('other_document2')->nullable();
            $table->string('other_document3')->nullable();
            $table->string('other_document4')->nullable();

            
            $table->integer('ask_price')->nullable();
            $table->string('ask_price_unit')->nullable();
            $table->integer('ask_price_amount')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status',['inactive','active']);
            $table->integer('payment_id')->nullable();
            $table->boolean('home_featured')->default(false);
            $table->tinyInteger('deal_closed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
