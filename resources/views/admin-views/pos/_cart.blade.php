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
    @include('admin-views.pos._cart_render')
</div>

<div class="pos-data-table px-3">
    <form action="{{route('admin.pos.order')}}" id='order_place' method="post">
        @csrf

        @include('common.pos-order-delivery')

        <div class="pt-4 mb-4">
            <div class="text-dark d-flex mb-2">{{translate('Paid_By')}} :</div>
            <ul class="list-unstyled option-buttons">
                <li>
                    <input type="radio" id="cash" value="cash" name="type" hidden="" checked="">
                    <label for="cash" class="btn btn-bordered px-4 mb-0">{{translate('Cash')}}</label>
                </li>
                <li>
                    <input type="radio" value="card" id="card" name="type" hidden="">
                    <label for="card" class="btn btn-bordered px-4 mb-0">{{translate('Card')}}</label>
                </li>
                <li class="d-none" id="posCod">
                    <input type="radio" value="cod" id="cod" name="type" hidden="">
                    <label for="cod" class="btn btn-bordered px-4 mb-0">{{translate('COD')}}</label>
                </li>
                <li id="pay_after_eating_li" style="display: {{ session('table_id') ?  '' : 'none' }}">
                    <input type="radio" value="pay_after_eating" id="pay_after_eating" name="type" hidden="">
                    <label for="pay_after_eating" class="btn btn-bordered px-4 mb-0">{{translate('pay_after_eating')}}</label>
                </li>
            </ul>
        </div>

        {{--            <div class="text-dark">--}}
        {{--                <div class="row">--}}
        {{--                    <div  class="col-6">{{translate('Paid_Amount')}} :</div>--}}
        {{--                    <div class="col-6 text-right">--}}
        {{--                        <!-- <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-discount">--}}
        {{--                            <i class="tio-edit"></i>--}}
        {{--                        </button> -->--}}
        {{--                        {{translate('$600')}}--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--                <div class="row">--}}
        {{--                    <div  class="col-6">{{translate('Due Amount')}} :</div>--}}
        {{--                    <div class="col-6 text-right">--}}
        {{--                        {{translate('- $50')}}--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        <div class="row mt-4 gy-2">
            <div class="col-md-4">
                <a href="#" class="btn btn-outline-danger btn--danger btn-block" onclick="emptyCart()"><i
                        class="fa fa-times-circle "></i> {{translate('Cancel_Order')}} </a>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa fa-shopping-bag"></i>
                    {{translate('Place_Order')}}
                </button>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-block" name="save">
                    <i class="fa fa-shopping-bag"></i>
                    {{translate('Save Order')}}
                </button>
            </div>
        </div>
    </form>
</div>

