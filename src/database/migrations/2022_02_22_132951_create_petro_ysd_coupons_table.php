<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetroYsdCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('petro_ysd_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petro_log_id');
            $table->string('mobile');
            $table->string('productName')->comment('产品名称');
            $table->string('productId')->comment('产品ID');
            $table->string('thirdOrderId')->comment('三方订单号');
            $table->string('couponId')->comment('卡券ID');
            $table->string('couponCode')->comment('电子券编号');
            $table->string('cashAmount')->comment('实际支付金额');
            $table->string('faceValue')->comment('面值(分)');
            $table->timestamp('couponBeginDate')->nullable()->comment('卡券生效时间');
            $table->timestamp('couponEndDate')->nullable()->comment('卡券失效时间');
            $table->timestamp('issuingDate')->nullable()->comment('发券时间');
            $table->timestamp('useTime')->nullable()->comment('使用时间');
            $table->string('useShop', 150)->nullable()->comment('使用门店');
            $table->string('productType')->comment('产品类型');
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('petro_ysd_coupons');
    }
}
