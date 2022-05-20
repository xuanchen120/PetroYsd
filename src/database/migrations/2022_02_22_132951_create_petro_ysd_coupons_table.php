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
        Schema::create('petro_coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petro_log_id');
            $table->string('mobile');
            $table->string('name')->comment('电子券名称');
            $table->string('amount')->comment('面额');
            $table->string('couponNo')->comment('电子券编号');
            $table->tinyInteger('status')->default(2);
            $table->string('requestCode')->comment('电子券请求码');
            $table->string('limitAmount')->comment('便利店金额限制');
            $table->string('oillimitAmount')->comment('油品金额限制');
            $table->string('ttlimitAmount')->comment('总金额限制');
            $table->string('effectiveTime')->comment('生效日期');
            $table->string('expirdDate')->comment('失效日期');
            $table->string('isReuse')->comment('是否叠加');
            $table->string('stationLimitName')->comment('加油站限制');
            $table->string('goodsTypeStr')->comment('商品类型');
            $table->string('catName')->comment('商品大类');
            $table->string('glCatName')->comment('商品');
            $table->string('oilgCatName')->comment('油品大类');
            $table->string('oilglCatName')->comment('油品小类');
            $table->string('stationName')->comment('核销站点名称');
            $table->string('stationCode')->comment('核销站点编');
            $table->decimal('realAmount')->comment('实际支付金额');
            $table->json('goodsInfo')->comment('使用的商品数据');
            $table->dateTime('useTime')->comment('核销时间');
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
        Schema::dropIfExists('petro_coupons');
    }
}
