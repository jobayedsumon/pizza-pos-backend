@php
    $branch = \App\Model\Branch::first();
     if (session()->get('delivery_address')) {
         $delivery_address = session()->get('delivery_address');
         $delivery_charge = $delivery_address['delivery_charge'];
     } else {
         $delivery_charge = \App\CentralLogics\Helpers::get_delivery_charge(0);
     }
@endphp

<div id="cart">
    @include('branch-views.pos._cart_render')
</div>

<style>
    .cartButton {
        width: 150px;
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<div class="pos-data-table px-3">

    <form action="{{route('branch.pos.order')}}" id='order_place' method="post">
        @csrf

        @include('common.pos-order-delivery')

        <div class="pt-4 mb-4">
            <div class="text-dark d-flex mb-2">{{translate('Paid_By')}} :</div>
            <ul class="list-unstyled option-buttons">
                <li>
                    <input type="radio" id="cash" value="cash" name="type" hidden="" checked="">
                    <label for="cash" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Cash')}}</label>
                </li>
                <li>
                    <input type="radio" value="card" id="card" name="type" hidden="">
                    <label for="card" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Card')}}</label>
                </li>
                <li>
                    <input type="radio" value="eftpos" id="eftpos" name="type" hidden="">
                    <label for="eftpos" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('EFTPOS')}}</label>
                </li>
                <li>
                    <input type="radio" value="voucher" id="voucher" name="type" hidden="">
                    <label for="voucher" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Voucher')}}</label>
                </li>
                <li>
                    <input type="radio" value="discount" id="discount" name="type" hidden="">
                    <label for="discount" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Discount')}}</label>
                </li>
                <li>
                    <input type="radio" value="account" id="account" name="type" hidden="">
                    <label for="account" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Account')}}</label>
                </li>
                <li>
                    <input type="radio" value="ml_paid_online" id="ml_paid_online" name="type" hidden="">
                    <label for="ml_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('ML - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="ml_paid_instore" id="ml_paid_instore" name="type" hidden="">
                    <label for="ml_paid_instore" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('ML - Paid Instore')}}</label>
                </li>
                <li>
                    <input type="radio" value="ml_collect_cash" id="ml_collect_cash" name="type" hidden="">
                    <label for="ml_collect_cash" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('ML - Collect Cash')}}</label>
                </li>
                <li>
                    <input type="radio" value="ue_paid_online" id="ue_paid_online" name="type" hidden="">
                    <label for="ue_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('UE - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="paid_online" id="paid_online" name="type" hidden="">
                    <label for="paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="easi_cash" id="easi_cash" name="type" hidden="">
                    <label for="easi_cash" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Easi - Cash')}}</label>
                </li>
                <li>
                    <input type="radio" value="door_dash_paid_online" id="door_dash_paid_online" name="type" hidden="">
                    <label for="door_dash_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Door Dash - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="order_in_paid_online" id="order_in_paid_online" name="type" hidden="">
                    <label for="order_in_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Order-In - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="easi_paid_online" id="easi_paid_online" name="type" hidden="">
                    <label for="easi_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Easi - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="easi_account" id="easi_account" name="type" hidden="">
                    <label for="easi_account" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Easi - Account')}}</label>
                </li>
                <li>
                    <input type="radio" value="dl_paid_online" id="dl_paid_online" name="type" hidden="">
                    <label for="dl_paid_online" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('DL - Paid Online')}}</label>
                </li>
                <li>
                    <input type="radio" value="pceftpos" id="pceftpos" name="type" hidden="">
                    <label for="pceftpos" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('PCEFTPOS')}}</label>
                </li>
                <li>
                    <input type="radio" value="gift_vouchers" id="gift_vouchers" name="type" hidden="">
                    <label for="gift_vouchers" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('Gift Vouchers')}}</label>
                </li>
                <li class="d-none" id="posCod">
                    <input type="radio" value="cod" id="cod" name="type" hidden="">
                    <label for="cod" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('COD')}}</label>
                </li>
                <li id="pay_after_eating_li" style="display: {{ session('table_id') ?  '' : 'none' }}">
                    <input type="radio" value="pay_after_eating" id="pay_after_eating" name="type" hidden="">
                    <label for="pay_after_eating" class="btn btn-bordered px-4 mb-0 cartButton">{{translate('pay_after_eating')}}</label>
                </li>
            </ul>
        </div>

        <div class="row mt-4 gy-2">
            <div class="col-md-4">
                <a href="#" class="btn btn-danger btn-block cartButton" onclick="emptyCart()"><i
                        class="fa fa-times-circle "></i> {{translate('Cancel_Order')}} </a>
            </div>
            <div class="col-md-4">
                {{--                        <button type="button" class="btn  btn-primary btn-block" data-toggle="modal" data-target="#paymentModal">--}}
                <button type="submit" class="btn btn-primary btn-block cartButton">
                    <i class="fa fa-shopping-bag"></i>
                    {{translate('Place_Order')}}
                </button>
                {{--                </form>--}}
            </div>
            <div class="col-md-4">
               <button type="submit" class="btn btn-success btn-block cartButton" name="save">
                    <i class="fa fa-shopping-bag"></i>
                    {{translate('Save Order')}}
                </button>
            </div>
        </div>
    </form>

</div>


