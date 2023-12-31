@foreach($orders as $order)

    @include('branch-views.pos.order.invoice')

{{--<div style="width:370px;margin-left:18px" class="" id="printableAreaContent">--}}
{{--    <div class="text-center pt-4 mb-3 w-100">--}}
{{--        <h2 style="line-height: 1">{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>--}}
{{--        <h5 style="font-size: 20px;font-weight: lighter;line-height: 1">--}}
{{--            {{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}--}}
{{--        </h5>--}}
{{--        <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">--}}
{{--            {{translate('Phone')}}--}}
{{--            : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}--}}
{{--        </h5>--}}
{{--    </div>--}}

{{--    <span>--------------------------------------------</span>--}}
{{--    <div class="row mt-3">--}}
{{--        <div class="col-6">--}}
{{--            <h5>{{translate('Order ID')}} : {{$order['id']}}</h5>--}}
{{--        </div>--}}
{{--        <div class="col-6">--}}
{{--            <h5 style="font-weight: lighter">--}}
{{--                {{date('d/M/Y h:i a',strtotime($order['created_at']))}}--}}
{{--            </h5>--}}
{{--        </div>--}}
{{--        @if($order->customer)--}}
{{--            <div class="col-12">--}}
{{--                <h5>{{translate('Customer Name')}} : {{$order->customer['f_name'].' '.$order->customer['l_name']}}</h5>--}}
{{--                <h5>{{translate('Phone')}} : {{$order->customer['phone']}}</h5>--}}
{{--                <h5>--}}
{{--                    {{translate('Address')}}--}}
{{--                    : {{isset($order->delivery_address)?json_decode($order->delivery_address, true)['address']:''}}--}}
{{--                </h5>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <h5 class="text-uppercase"></h5>--}}
{{--    <span>--------------------------------------------</span>--}}
{{--    <table class="table table-bordered mt-3" style="width: 98%">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th style="width: 10%">{{translate('QTY')}}</th>--}}
{{--            <th class="">{{translate('DESC')}}</th>--}}
{{--            <th style="text-align:right; padding-right:4px">{{translate('Price')}}</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}

{{--        <tbody>--}}
{{--        @php($sub_total=0)--}}
{{--        @php($total_tax=0)--}}
{{--        @php($total_dis_on_pro=0)--}}
{{--        @php($add_ons_cost=0)--}}
{{--        @foreach($order->details as $detail)--}}
{{--            @if($detail->product)--}}
{{--                @php($add_on_qtys=json_decode($detail['add_on_qtys'],true))--}}
{{--                <tr>--}}
{{--                    <td class="">--}}
{{--                        {{$detail['quantity']}}--}}
{{--                    </td>--}}
{{--                    <td class="">--}}
{{--                        <span style="word-break: break-all;"> {{ Str::limit($detail->product['name'], 200) }}</span><br>--}}
{{--                        @if(count(json_decode($detail['variation'],true))>0)--}}
{{--                            <strong><u>{{translate('Variation')}} : </u></strong>--}}
{{--                            @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)--}}
{{--                                @if($key1 == 'price' && $detail['quantity'] != 0.5)--}}
{{--                                <div class="font-size-sm text-body" style="color: black!important;">--}}
{{--                                    <span>{{$key1}} :  </span>--}}
{{--                                    <span--}}
{{--                                        class="font-weight-bold">{{$variation}} {{$key1=='price'?\App\CentralLogics\Helpers::currency_symbol():''}}</span>--}}
{{--                                </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                        @foreach(json_decode($detail['add_on_ids'],true) as $key2 =>$id)--}}
{{--                            @php($addon=\App\Model\AddOn::find($id))--}}
{{--                            @if($key2==0)<strong><u>{{translate('Addons')}} : </u></strong>@endif--}}

{{--                            @if($add_on_qtys==null)--}}
{{--                                @php($add_on_qty=1)--}}
{{--                            @else--}}
{{--                                @php($add_on_qty=$add_on_qtys[$key2])--}}
{{--                            @endif--}}

{{--                            <div class="font-size-sm text-body">--}}
{{--                                <span>{{$addon['name']}} :  </span>--}}
{{--                                <span class="font-weight-bold">--}}
{{--                                                {{$add_on_qty}} x {{ \App\CentralLogics\Helpers::set_symbol($addon['price'])}}--}}
{{--                                            </span>--}}
{{--                            </div>--}}
{{--                            @php($add_ons_cost+=$addon['price']*$add_on_qty)--}}
{{--                        @endforeach--}}

{{--                        <br>--}}

{{--                        @php($allergy_ids = json_decode($detail['allergy_ids'],true))--}}
{{--                        @if ($allergy_ids)--}}
{{--                            <span>--}}
{{--                                                        <u><strong>{{translate('allergys')}}</strong></u>--}}
{{--                                                        @foreach($allergy_ids as $key2 =>$id)--}}
{{--                                    @php($allergy=\App\Model\Allergy::find($id))--}}
{{--                                    <div class="font-size-sm text-body">--}}
{{--                                                                    <span>{{$allergy['name']}}</span>--}}
{{--                                                                </div>--}}
{{--                                @endforeach--}}
{{--                                                    </span>--}}
{{--                        @endif--}}

{{--                        {{translate('Discount')}} : {{ \App\CentralLogics\Helpers::set_symbol($detail['discount_on_product']*$detail['quantity']) }}--}}
{{--                    </td>--}}
{{--                    <td style="width: 28%;padding-right:4px; text-align:right">--}}
{{--                        @php($amount=(($detail['quantity'] == 0.5 ? HALF_HALF_PRICE : $detail['price']) - $detail['discount_on_product']) * $detail['quantity'] + $addonSubTotal)--}}
{{--                        {{ \App\CentralLogics\Helpers::set_symbol($amount) }}--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                @php($sub_total+=$amount)--}}
{{--                @php($total_tax+=$detail['tax_amount']*$detail['quantity'])--}}
{{--            @endif--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--    <span>--------------------------------------------</span>--}}
{{--    <div class="row justify-content-end">--}}
{{--        <div class="col-md-8 col-lg-8">--}}
{{--            <dl class="row text-right" style="color: black!important;">--}}
{{--                <dt class="col-8">{{translate('Items Price')}}:</dt>--}}
{{--                <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($sub_total) }}</dd>--}}
{{--                <dt class="col-8">{{translate('Tax')}} / {{translate('VAT')}}:</dt>--}}
{{--                <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($total_tax) }}</dd>--}}
{{--                <dt class="col-8">{{translate('Addon Cost')}}:</dt>--}}
{{--                <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}--}}
{{--                    <hr>--}}
{{--                </dd>--}}

{{--                <dt class="col-8">{{translate('Subtotal')}}:</dt>--}}
{{--                <dd class="col-6">{{ \App\CentralLogics\Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>--}}
{{--                <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>--}}
{{--                <dt class="col-8">{{translate('Coupon Discount')}}:</dt>--}}
{{--                <dd class="col-4">--}}
{{--                    -{{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>--}}
{{--                <dt class="col-8">{{translate('Extra Discount')}}:</dt>--}}
{{--                <dd class="col-4">--}}
{{--                    -{{ \App\CentralLogics\Helpers::set_symbol($order['extra_discount']) }}</dd>--}}
{{--                <dt class="col-8">{{translate('Delivery Fee')}}:</dt>--}}
{{--                <dd class="col-4">--}}
{{--                    @if($order['order_type']=='take_away')--}}
{{--                        @php($del_c=0)--}}
{{--                    @else--}}
{{--                        @php($del_c=$order['delivery_charge'])--}}
{{--                    @endif--}}
{{--                    {{ \App\CentralLogics\Helpers::set_symbol($del_c) }}--}}
{{--                    <hr>--}}
{{--                </dd>--}}
{{--                <dt class="col-8" style="font-size: 20px">{{translate('Total')}}:</dt>--}}
{{--                <dd class="col-6" style="font-size: 20px">{{ \App\CentralLogics\Helpers::set_symbol($sub_total+$del_c+$total_tax+$add_ons_cost-$order['coupon_discount_amount']) }}</dd>--}}
{{--                --}}{{-- <dd class="col-4" style="font-size: 20px">{{ \App\CentralLogics\Helpers::set_symbol($order->order_amount) }}</dd> --}}
{{--            </dl>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="d-flex flex-row justify-content-between border-top">--}}
{{--        <span>{{translate('Paid_by')}}: {{\App\CentralLogics\translate($order->payment_method)}}</span>--}}
{{--    </div>--}}
{{--    <span>--------------------------------------------</span>--}}
{{--    <h5 class="text-center pt-3">--}}
{{--        """{{translate('THANK YOU')}}"""--}}
{{--    </h5>--}}
{{--    <span>--------------------------------------------</span>--}}
{{--</div>--}}
@endforeach
