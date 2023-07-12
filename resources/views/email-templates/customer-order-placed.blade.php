<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
</head>

<body style="font-family: sans-serif; max-width: 960px; background-color: #f7f7f7; border: 1px solid #ccc; padding: 20px; margin: 0 auto">

    <h1>Order Receipt</h1>

    <header>
        <p>Dear Customer,</p>
        <p>Thank you for ordering with us. Here's your order details: </p>
    </header>

    <br>

    <main>
        <div style="text-align: center">
            <h2 style="margin: 10px">{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>
            <h5 style="font-size: 20px;font-weight: lighter;line-height: 1; margin: 10px">
                {{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}
            </h5>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1; margin: 10px">
                Phone : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
            </h5>
        </div>

        <hr>

        <table style="background-color: #f2f2f2; padding: 0 10px; width: 100%">
            <tbody>
            <tr>
                <td style="width: 50%">
                    <div>
                        <h4 style="margin-top: 0">{{translate('Order ID : ')}}{{$order['id']}}</h4>
                        <h4 style="font-weight: lighter">
                            <span>{{date('d/M/Y h:m a',strtotime($order['created_at']))}}</span>
                        </h4>
                    </div>
                </td>
                <td style="width: 50%">
                    <div>
                        @if(isset($order->customer))
                            <h4>
                                {{translate('Customer Name : ')}}<span>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</span>
                            </h4>
                            <h4>
                                {{translate('Phone : ')}}<span>{{$order->customer['phone']}}</span>
                            </h4>
                            @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                            @if(!$address)
                                @php($address = json_decode(json_encode(str_replace('/','',$order->delivery_address)),true))
                            @endif
                            <h4 style="font-weight: lighter">
                                {{translate('Address : ')}}<span>{{isset($address)?$address['address']:''}}</span>
                            </h4>
                        @endif
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

        <hr>

        <table style="width: 100%;">
            <thead style="background-color: red">
            <tr>
                <th style="text-align: left; width: 20%; background-color: #f2f2f2; padding: 20px">#</th>
                <th style="text-align: left; width: 40%; background-color: #f2f2f2; padding: 20px">{{translate('DESC')}}</th>
                <th style="text-align: center; width: 20%; background-color: #f2f2f2; padding: 20px">{{translate('QTY')}}</th>
                <th style="text-align: right; width: 20%; background-color: #f2f2f2; padding: 20px">{{translate('Price')}}</th>
            </tr>
            </thead>

            <tbody>
            @php($sub_total=0)
            @php($total_tax=0)
            @php($total_dis_on_pro=0)
            @php($add_ons_cost=0)
            @foreach($order->details as $detail)
                @if($detail->product)
                    @php($add_on_qtys=json_decode($detail['add_on_qtys'],true))
                    <tr>
                        <td style="text-align: left">
                            <span style="padding-left: 10px">
                                 {{$loop->index+1}}
                            </span>

                        </td>

                        <td style="text-align: left">
                            <h4 style="margin-bottom: 0;">
                                {{$detail->product['name']}}
                            </h4>

                            <br>

                            @if(count(json_decode($detail['variation'],true))>0)
                                <strong>{{translate('Variation : ')}}</strong>
                                @foreach(json_decode($detail['variation'],true)[0] as $key1 =>$variation)
                                    <div style="color: black!important;">
                                        <span style="text-transform: capitalize">{{$key1}} :  </span>
                                        <span>{{ $key1 == 'price' ?  Helpers::set_symbol($variation) : $variation }}</span>
                                    </div>
                                @endforeach

                                <br>

                            @endif

                            @if(json_decode($detail['add_on_ids'],true)>0)
                            @foreach(json_decode($detail['add_on_ids'],true) as $key2 =>$id)
                                @php($addon=\App\Model\AddOn::find($id))
                                @if($key2==0)<strong>{{translate('Addons : ')}}</strong>@endif

                                @if($add_on_qtys==null)
                                    @php($add_on_qty=1)
                                @else
                                    @php($add_on_qty=$add_on_qtys[$key2])
                                @endif

                                <div>
                                    <span>{{$addon['name']}} :  </span>
                                    <span>
                                                {{$add_on_qty}} x {{ \App\CentralLogics\Helpers::set_symbol($addon['price']) }}
                                            </span>
                                </div>
                                @php($add_ons_cost+=$addon['price']*$add_on_qty)
                            @endforeach
                                <br>
                            @endif

                            @php($allergy_ids = json_decode($detail['allergy_ids'],true))
                            @if ($allergy_ids)
                                <span>
                                        <strong>{{translate('allergys')}}</strong>
                                                @foreach($allergy_ids as $key2 =>$id)
                                        @php($allergy=\App\Model\Allergy::find($id))
                                        <div>
                                            <span>{{$allergy['name']}}</span>
                                        </div>
                                    @endforeach
                                            </span>

                                <br>
                            @endif

                            {{translate('Discount : ')}}{{ \App\CentralLogics\Helpers::set_symbol($detail['discount_on_product']) }}


                        </td>

                        <td style="text-align: center">
                            {{$detail['quantity']}}
                        </td>

                        <td style="text-align: right">
                            <span style="padding-right: 10px">
                                @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                                {{ \App\CentralLogics\Helpers::set_symbol($amount) }}
                            </span>

                        </td>
                    </tr>
                    @php($sub_total+=$amount)
                    @php($total_tax+=$detail['tax_amount']*$detail['quantity'])

                @endif
            @endforeach
            </tbody>
        </table>

        <br>

        <div style="text-align: right; background-color: #f2f2f2; padding: 10px">
            <dl style="color: black!important;">
                <div style="display: inline-flex">
                    <dt>{{translate('Items Price:')}}</dt>
                    <dd>{{ \App\CentralLogics\Helpers::set_symbol($sub_total) }}</dd>
                </div>
                <br>
                <div style="display: inline-flex">
                    <dt>{{translate('Tax / VAT:')}}</dt>
                    <dd>{{ \App\CentralLogics\Helpers::set_symbol($total_tax) }}</dd>
                </div>
                <br>
                <div style="display: inline-flex">
                    <dt>{{translate('Addon Cost:')}}</dt>
                    <dd>
                        {{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}
                    </dd>
                </div>

                <br>

                     <hr>

                    <div style="display: inline-flex">

                        <dt>{{translate('Subtotal:')}}</dt>
                        <dd>
                            {{ \App\CentralLogics\Helpers::set_symbol($sub_total+$total_tax+$add_ons_cost) }}</dd>

                     </div>
                <br>
                <div style="display: inline-flex">
                    <dt>{{translate('Extra Discount')}}:</dt>
                    <dd>
                        - {{ \App\CentralLogics\Helpers::set_symbol($order['extra_discount']) }}</dd>
                </div>
                <br>
                <div style="display: inline-flex">
                    <dt>{{translate('Coupon Discount:')}}</dt>
                    <dd>
                        - {{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>
                </div>
                <br>
                <div style="display: inline-flex">
                    <dt>{{translate('Delivery Fee:')}}</dt>
                    <dd>
                        @if($order['order_type']=='take_away')
                            @php($del_c=0)
                        @else
                            @php($del_c=$order['delivery_charge'])
                        @endif
                        {{ \App\CentralLogics\Helpers::set_symbol($del_c) }}
                    </dd>
                </div>
                <br>

                <hr>

                <div style="display: inline-flex">

                    <dt style="font-size: 20px">{{translate('Total:')}}</dt>
                    <dd style="font-size: 20px">
                        {{ \App\CentralLogics\Helpers::set_symbol($sub_total+$del_c+$total_tax+$add_ons_cost-$order['coupon_discount_amount']-$order['extra_discount']) }}
                    </dd>
                </div>

                </dl>
        </div>

    </main>

    <div>
        <p>You will soon receive your order.</p>

        <p>
            {{translate('"""Thank You."""')}}
        </p>
    </div>

    <footer style="text-align: center">
        <small>{{\App\Model\BusinessSetting::where(['key'=>'footer_text'])->first()->value}}</small>
    </footer>

</body>
</html>
