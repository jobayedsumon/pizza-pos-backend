<div class="table-responsive pos-cart-table border">
    <table class="table table-align-middle mb-0">
        <thead class="text-dark bg-light">
        <tr>
            <th class="text-capitalize border-0 min-w-120">{{translate('item')}}</th>
            <th class="text-capitalize border-0">{{translate('qty')}}</th>
            <th class="text-capitalize border-0">{{translate('price')}}</th>
            <th class="text-capitalize border-0 text-center">{{translate('delete')}}</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $subtotal = 0;
        $addon_price = 0;
        $discount = 0;
        $discount_type = 'amount';
        $discount_on_product = 0;
        $total_tax = 0;

        $branch = \App\Model\Branch::first();
        if (session()->get('delivery_address')) {
            $delivery_address = session()->get('delivery_address');
            $delivery_charge = $delivery_address['delivery_charge'];
        } else {
            $delivery_charge = \App\CentralLogics\Helpers::get_delivery_charge(0);
        }

        ?>
        @if(session()->has('cart') && count( session()->get('cart')) > 0)
                <?php
                $cart = session()->get('cart');
                if(isset($cart['discount']))
                {
                    $discount = $cart['discount'];
                    $discount_type = $cart['discount_type'];
                }
                ?>
            @foreach($cart as $key => $cartItem)
                @if(is_array($cartItem))
                        <?php
                        $product_subtotal = $cartItem['quantity'] * ($cartItem['quantity'] == 0.5 ? HALF_HALF_PRICE : $cartItem['price']);
                        $discount_on_product += ($cartItem['discount']*$cartItem['quantity']);
                        $subtotal += $product_subtotal;
                        $addon_price += $cartItem['addon_price'];

                        //tax calculation
                        $product = \App\Model\Product::find($cartItem['id']);
                        $total_tax += \App\CentralLogics\Helpers::tax_calculate($product, $cartItem['price'])*$cartItem['quantity'];

                        ?>

                    <tr>
                        <td>
                            <div class="media align-items-center gap-10">
                                <img class="avatar avatar-sm" src="{{asset('storage/app/public/product')}}/{{$cartItem['image']}}"
                                     onerror="this.src='{{asset('public-assets/assets/admin/img/160x160/img2.jpg')}}'" alt="{{$cartItem['name']}} image">
                                <div class="media-body">
                                    <h5 class="text-hover-primary mb-0">{{Str::limit($cartItem['name'], 50)}}</h5>
                                    <small class="font-weight-bold">{{Str::limit($cartItem['variant'], 20)}}</small>
                                    <small class="d-block">
                                        @php($add_on_qtys=$cartItem['add_on_qtys'])
                                        @php($addonSubtotal=0)
                                        @foreach($cartItem['add_ons'] as $key2 =>$id)
                                            @php($addon=\App\Model\AddOn::find($id))
                                            @if($key2==0)<strong><u>Addons : </u></strong>@endif

                                            @if($add_on_qtys==null)
                                                @php($add_on_qty=1)
                                            @else
                                                @php($add_on_qty=$add_on_qtys[$key2])
                                            @endif

                                        @php($addonSubtotal += ($add_on_qty ?? 0) * $addon['price'])

                                            <div class="font-size-sm text-body">
                                                <span>{{$addon['name']}} :  </span>
                                                <span class="font-weight-bold">
                                                    {{ $add_on_qty}}
                                                </span>
                                            </div>
                                        @endforeach
                                    </small>
                                    <small class="d-block">
                                        @foreach($cartItem['allergies'] as $key2 =>$id)
                                            @php($allergy=\App\Model\Allergy::find($id))
                                            @if($key2==0)<strong><u>Allergies : </u></strong>@endif
                                            <div class="font-size-sm text-body">
                                                <span>{{$allergy['name']}}</span>
                                            </div>
                                        @endforeach
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($cartItem['quantity'] == 0.5)
                                <span class="badge badge-inline badge-soft-success">Half / Half</span>
                            @else
                                <input type="number" class="form-control qty" data-key="{{$key}}" value="{{intval($cartItem['quantity'])}}" min="1" onkeyup="updateQuantity(event)">
                            @endif
                        </td>
                        <td>
                            <div class="">
                                {{ \App\CentralLogics\Helpers::set_symbol($product_subtotal + $addonSubtotal) }}
                            </div> <!-- price-wrap .// -->
                        </td>
                        <td class="justify-content-center gap-2">
                            <a href="javascript:removeFromCart({{$key}})" class="btn btn-sm btn-outline-danger square-btn">
                                <i class="tio-delete"></i>
                            </a>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<?php
$total = $subtotal+$addon_price;
$discount_amount = ($discount_type=='percent' && $discount>0)?(($total * $discount)/100):$discount;
$discount_amount += $discount_on_product;
$total -= $discount_amount;

$extra_discount = session()->get('cart')['extra_discount'] ?? 0;
$extra_discount_type = session()->get('cart')['extra_discount_type'] ?? 'amount';
if($extra_discount_type == 'percent' && $extra_discount > 0){
    $extra_discount = ($subtotal * $extra_discount) / 100;
}
if($extra_discount) {
    $total -= $extra_discount;
}
?>
<div class="pos-data-table px-3 mt-3">
    <dl class="row">
{{--        <dt  class="col-6">{{translate('addon')}} : </dt>--}}
{{--        <dd class="col-6 text-right">{{ \App\CentralLogics\Helpers::set_symbol($addon_price) }}</dd>--}}

        <dt  class="col-6">{{translate('sub_total')}} : </dt>
        <dd class="col-6 text-right">{{\App\CentralLogics\Helpers::set_symbol($subtotal+$addon_price) }}</dd>

        <dt  class="col-6">{{translate('product')}} {{translate('discount')}} :</dt>
        <dd class="col-6 text-right">- {{ \App\CentralLogics\Helpers::set_symbol(round($discount_amount,2)) }}</dd>

        <dt  class="col-6">{{translate('extra')}} {{translate('discount')}} :</dt>
        <dd class="col-6 text-right">
            <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-discount">
                <i class="tio-edit"></i>
            </button>
            - {{ \App\CentralLogics\Helpers::set_symbol($extra_discount) }}
        </dd>

{{--        <dt  class="col-6">{{translate('VAT/TAX:')}} : </dt>--}}
{{--        <dd class="col-6 text-right">{{ \App\CentralLogics\Helpers::set_symbol(round($total_tax,2)) }}</dd>--}}

        <dt  class="col-6 {{ session()->get('delivery_address') ? '' : 'd-none' }} deliveryChargeInTable pb-2">{{translate('delivery_charge')}} : </dt>
        <dd class="col-6 {{ session()->get('delivery_address') ? '' : 'd-none' }} text-right deliveryChargeInTable pb-2" id="deliveryChargeInTableValue">
            @if(session()->get('delivery_address'))
                {{ \App\CentralLogics\Helpers::set_symbol($delivery_charge) }}
            @endif
        </dd>

        <dt  class="col-6 border-top font-weight-bold pt-2">{{translate('total')}} : </dt>

        <dd class="col-6 text-right border-top font-weight-bold pt-2" id="posTotalValue">{{ \App\CentralLogics\Helpers::set_symbol(round(
    isset($delivery_address) ? $total+$total_tax + $delivery_charge : $total+$total_tax
    , 2)) }}</dd>
    </dl>
</div>

<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_discount')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('branch.pos.discount')}}" method="post" class="row mb-0">
                    @csrf
                    <div class="form-group col-sm-6">
                        <label class="text-dark">{{translate('discount')}}</label>
                        <input type="number" class="form-control" name="discount" value="{{ session()->get('cart')['extra_discount'] ?? 0 }}">
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="text-dark">{{translate('type')}}</label>
                        <select name="type" class="form-control">
                            <option
                                value="amount" {{$extra_discount_type=='amount'?'selected':''}}>{{translate('amount')}}
                                ({{\App\CentralLogics\Helpers::currency_symbol()}})
                            </option>
                            <option
                                value="percent" {{$extra_discount_type=='percent'?'selected':''}}>{{translate('percent')}}
                                (%)
                            </option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end col-sm-12">
                        <button class="btn btn-sm btn-primary" type="submit">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="coupon-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('Coupon_Discount')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="mb-0" action="{{route('branch.pos.discount')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="text-dark">{{translate('Coupon_Code')}}</label>
                        <input type="number" class="form-control" name="discount" placeholder="{{translate('SULTAN200')}}">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-primary" type="submit">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_tax')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('branch.pos.tax')}}" method="POST" class="row">
                    @csrf
                    <div class="form-group col-12">
                        <label for="">{{translate('tax')}} (%)</label>
                        <input type="number" class="form-control" name="tax" min="0">
                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary" type="submit">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
