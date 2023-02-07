<?php

namespace XuanChen\PetroYsd\Controllers\Admin;

use Auth;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use XuanChen\PetroYsd\Kernel\Models\PetroYsdCoupon;

class CouponController extends AdminController
{

    protected $title = '中石油优惠券管理(和悦)';

    /**
     * Notes:
     *
     * @Author: <C.Jason>
     * @Date  : 2019/9/18 14:50
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PetroYsdCoupon());

        $grid->disableActions();
        $grid->disableCreateButton();

        $grid->model()->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->column(1 / 2, function ($filter) {
                $filter->equal('mobile', '手机号');
                $filter->equal('thirdOrderId', '三方订单号');
                $filter->equal('productId', '产品ID');
            });
            $filter->column(1 / 2, function ($filter) {
                $filter->equal('state', '状态')->select(PetroYsdCoupon::STATUS);
                $filter->between('issuingDate', '发券时间')->datetime();
            });
        });

        $grid->column('id', '#ID#');
        $grid->column('mobile', '手机号');
        $grid->column('productName', '产品名称');
        $grid->column('productId', '产品ID')->hide();
        $grid->column('thirdOrderId', '三方订单号');
        $grid->column('couponId', '卡券ID');
        $grid->column('couponCode', '电子券编号');
        $grid->column('cashAmount', '实际支付金额')->hide();
        $grid->column('faceValue', '面值(分)');
        $grid->column('couponBeginDate', '卡券生效时间');
        $grid->column('couponEndDate', '卡券失效时间');
        $grid->column('issuingDate', '发券时间');
        $grid->column('productType', '产品类型')
            ->using(PetroYsdCoupon::PRODUCT_TYPES)
            ->label()
            ->hide();
        $grid->column('state', '状态')->using(PetroYsdCoupon::STATUS)->label();
        $grid->disableExport(false);

        $grid->export(function ($export) {
            $export->column('mobile', function ($value, $original) {
                return $value."\t";
            });
            $export->column('state', function ($value, $original) {
                return strip_tags($value);
            });
            $export->filename($this->title.date("YmdHis"));
        });

        return $grid;
    }

}
