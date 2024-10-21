<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'stores', function (Blueprint $table){
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('theme_dir')->nullable();
            $table->string('store_theme')->nullable();
            $table->string('domains')->nullable();
            $table->string('enable_storelink')->nullable();
            $table->string('enable_subdomain')->nullable();
            $table->string('subdomain')->nullable();
            $table->string('enable_domain')->default('off');
            $table->text('content')->nullable();
            $table->string('item_variable')->nullable();
            $table->longText('about')->nullable();
            $table->string('tagline')->nullable();
            $table->string('slug')->nullable();
            $table->string('lang', 5)->default('en');
            $table->longText('storejs')->nullable();
            $table->string('currency')->default('$');
            $table->string('currency_code')->default('USD');
            $table->string('currency_symbol_position')->default('pre')->nullable();
            $table->string('currency_symbol_space')->default('without')->nullable();
            $table->string('whatsapp')->nullable()->default('#');
            $table->string('facebook')->nullable()->default('#');
            $table->string('instagram')->nullable()->default('#');
            $table->string('twitter')->nullable()->default('#');
            $table->string('youtube')->nullable()->default('#');
            $table->string('google_analytic')->nullable();
            $table->string('facebook_pixel')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('footer_note')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('decimal_number')->default(2);

            $table->string('enable_shipping')->default('on');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->nullable();
            $table->string('logo')->nullable();
            $table->string('invoice_logo')->nullable();
            $table->string('is_twilio_enabled',225)->nullable();
            $table->text('twilio_sid')->nullable();
            $table->text('twilio_token')->nullable();
            $table->text('twilio_from')->nullable();
            $table->text('notification_number')->nullable();
            $table->string('is_stripe_enabled')->default('off');
            $table->text('stripe_key')->nullable();
            $table->text('stripe_secret')->nullable();
            $table->string('is_paypal_enabled')->default('off');
            $table->text('paypal_mode')->nullable();
            $table->text('paypal_client_id')->nullable();
            $table->text('paypal_secret_key')->nullable();
            $table->text('mail_driver')->nullable();
            $table->text('mail_host')->nullable();
            $table->text('mail_port')->nullable();
            $table->text('mail_username')->nullable();
            $table->text('mail_password')->nullable();
            $table->text('mail_encryption')->nullable();
            $table->text('mail_from_address')->nullable();
            $table->text('mail_from_name')->nullable();
            $table->integer('is_store_enabled')->default(1);
            $table->string('is_checkout_login_required')->default('on');
            $table->integer('is_active')->default(1);
            $table->integer('created_by')->default(0);
            $table->string('enable_whatsapp')->default('off');
            $table->string('whatsapp_number')->nullable();
            $table->string('enable_telegram')->default('off');
            $table->string('telegrambot')->nullable();
            $table->string('telegramchatid')->nullable();
            $table->string('custom_field_title_1')->nullable();
            $table->string('custom_field_title_2')->nullable();
            $table->string('custom_field_title_3')->nullable();
            $table->string('custom_field_title_4')->nullable();
            $table->string('enable_cod')->default('off');
            $table->string('enable_bank')->default('off');
            $table->string('bank_number')->nullable();
            $table->string('enable_pwa_store')->default('off');
            $table->string('is_whatsapp_enabled')->default('off');
            $table->text('whatsapp_contact_number')->nullable();
            $table->timestamps();
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
