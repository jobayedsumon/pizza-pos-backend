@extends('layouts.branch.app')
@section('title', translate('POS'))

<style>
    .posCategoryCard {
        width: 150px;
        height: 100px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px 0px #ccc;
        margin-bottom: 10px;
        margin-right: 10px;
        padding: 10px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1rem;
    }
</style>
    <!-- END ONLY DEV -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" style="display: none;">
                    <div style="position: fixed;z-index: 9999; left: 40%;top: 37% ;width: 100%">
                        <img width="200" src="{{asset('public-assets/assets/admin/img/loader.gif')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    <main id="content" role="main" class="main pointer-event pt-0">--}}
{{--        <!-- Content -->--}}
{{--        <!-- ========================= SECTION CONTENT ========================= -->--}}
{{--        <section class="section-content padding-y-sm bg-default mt-3">--}}
            <div class="container-fluid py-4">
                <div class="row gy-3 gx-2">
                    <div class="col-lg-7">

                        @if(!request()->query->count())

                        <div class="row">
                            @foreach (['veg', 'chicken', 'meat', 'sea_food'] as $item)
                                @php
                                 if ($item == 'veg') {
                                     $bgColor = 'green';
                                 } elseif ($item == 'chicken') {
                                     $bgColor = 'orange';
                                 } elseif ($item == 'meat') {
                                     $bgColor = 'red';
                                 } elseif ($item == 'sea_food') {
                                     $bgColor = '#00c3e3';
                                 }
                                @endphp
                                <div class="card navbar-vertical-aside-has-menu posCategoryCard" style="background-color: {{ $bgColor }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('branch.pos.index',['product_type' => $item])  }}">
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-white">{{ translate($item) }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            @foreach ($categories as $item)
                                @php
                                    $itemName = strtolower($item->name);
                                    if (str_contains($itemName, 'salad')) {
                                        $bgColor = 'green';
                                    } elseif (str_contains($itemName, 'pizza')) {
                                        $bgColor = 'orange';
                                    } elseif (str_contains($itemName, 'pasta') || str_contains($itemName, 'dessert')) {
                                        $bgColor = '#00c3e3';
                                    } else {
                                        $bgColor = 'black';
                                    }
                                @endphp
                                <div class="card navbar-vertical-aside-has-menu posCategoryCard" style="background-color: {{ $bgColor }}">
                                    <a class="js-navbar-vertical-aside-menu-link nav-link" href="{{ route('branch.pos.index',['category_id' => $item->id])  }}">
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-white">{{Str::limit($item->name, 40)}}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        @endif

                        @if(request()->query->count())

                        <div class="card">
                            <!-- POS Title -->
{{--                            <div class="pos-title">--}}
{{--                                <h4 class="mb-0">{{translate('Product_Section')}}</h4>--}}
{{--                            </div>--}}
                            <!-- End POS Title -->

                            <div class="d-flex flex-wrap flex-md-nowrap justify-content-between gap-3 gap-xl-4 px-4 py-4">
                                <div class="w-100">
                                    <form id="search-form">
                                        <!-- Search -->
                                        <div class="input-group input-group-merge input-group-flush border rounded">
                                            <div class="input-group-prepend pl-2">
                                                <div class="input-group-text">
                                                    <!-- <i class="tio-search"></i> -->
                                                    <img width="13" src="{{asset('public-assets/assets/admin/img/icons/search.png')}}" alt="">
                                                </div>
                                            </div>
                                            <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search" class="form-control border-0" placeholder="{{translate('Search_here')}}" aria-label="Search here">
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="items">
                                <div class="pos-item-wrap justify-content-center">
                                    @foreach($products as $product)
                                        @include('branch-views.pos._single_product',['product'=>$product])
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-3 d-flex justify-content-end">
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>

                        @endif

                    </div>
                    <div class="col-lg-5">
                        <div class="card billing-section-wrap">
                            <!-- POS Title -->
                            <div class="pos-title d-flex justify-content-between">
                                <h4 class="mb-0">{{translate('Billing_Section')}}</h4>
                                <h5 class="staff-name">Staff Name: <span class="blink" id="currentStaffName">
                                        @if(session('order_taken_by'))
                                            {{ $employees->find(session('order_taken_by'))->f_name }}
                                        @else
                                            {{translate('Select Your Name')}}
                                        @endif
                                    </span></h5>
                            </div>
                            <!-- End POS Title -->

                            <div class="px-2 pt-2 px-sm-4 pt-sm-2">
                                <label for="">Order Taken By</label>
                                <select id='order_taken_by' name="order_taken_by" data-placeholder="{{translate('Select Your Name')}}" class="form-control"
                                >
                                    <option selected disabled>{{translate('Select Your Name')}}</option>
                                    @forelse($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ $employee->id == session('order_taken_by') ? 'selected' : ''}}>{{ $employee->f_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="p-2 p-sm-4">
                                <small>For new number put 0 first and total 10 digits to auto save.</small>
                                <div class="d-flex flex-row gap-2 mb-3">
                                    <select
                                        onchange="changeCustomerId(this.value)" id='customer' name="customer_id" data-placeholder="{{translate('Walk_In_Customer')}}" class="js-data-example-ajax form-control"
                                    >

                                    </select>
                                    <button class="btn btn-success rounded text-nowrap" id="add_new_customer" type="button" data-toggle="modal" data-target="#add-customer" title="Add Customer">
                                        <i class="tio-add"></i>
                                        {{translate('Customer')}}
                                    </button>
                                </div>

                                <a type="button" id="previousOrders" class="btn btn-primary mb-3 btn-sm" data-toggle="modal" data-target="#orders-customer-modal">
                                    {{translate('Previous_Orders')}}
                                </a>

                                {{--<div class="form-group d-flex flex-wrap flex-sm-nowrap gap-2">
                                    <select onchange="store_key('table_id',this.value)" id='table' name="table_id"  class="table-data-selector form-control form-ellipsis">
                                        <option selected disabled>{{translate('Select Table')}}</option>
                                    @foreach($tables as $table)
                                            <option value="{{$table['id']}}" {{ $table['id'] == session('table_id') ? 'selected' : ''}}>{{translate('Table')}} - {{$table['number']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group d-flex flex-wrap flex-sm-nowrap gap-2">
                                    <input type="number" value="{{ session('people_number') }}" name="number_of_people" onkeyup="store_key('people_number',this.value)" id="number_of_people" class="form-control" id="password" min="1" max="99" placeholder="{{translate('Number Of People')}}">
--}}{{--                                    <button type="button" class="btn btn-secondary text-nowrap">{{translate('Clear_Cart')}}</button>--}}{{--
--}}{{--                                    <button type="button" class="btn btn-primary text-nowrap">{{translate('New_Order')}}</button>--}}{{--
                                </div>--}}

                                <div class='w-100'>
                                    @include('branch-views.pos._cart')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- container //  -->

        <!-- End Content -->
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>

        <div class="modal fade" id="add-customer" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('Add_New_Customer')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('branch.pos.customer-store')}}" method="post">
                            @csrf
                            <div class="row pl-2">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('Name')}}
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <input type="text" name="f_name" class="form-control mt-4" value="" placeholder="Name" required="">
                                    </div>
                                </div>
{{--                                <div class="col-12 col-lg-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="input-label">--}}
{{--                                            {{translate('Last_Name')}}--}}
{{--                                            <span class="input-label-secondary text-danger">*</span>--}}
{{--                                        </label>--}}
{{--                                        <input type="text" name="l_name" class="form-control" value="" placeholder="Last name" required="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row pl-2">--}}
{{--                                <div class="col-12 col-lg-6">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label class="input-label">--}}
{{--                                            {{translate('Email')}}--}}
{{--                                            <span class="input-label-secondary"></span>--}}
{{--                                        </label>--}}
{{--                                        <input type="email" name="email" class="form-control" value="" placeholder="Ex : ex@example.com">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{translate('Phone')}}
                                            ({{translate('+61')}})
                                            <span class="input-label-secondary text-danger">*</span>
                                        </label>
                                        <small>Put 0 first and total 10 digits.</small>
                                        <input type="number" name="phone" class="form-control" value="" placeholder="Phone" required="" >
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-secondary mr-1">{{translate('reset')}}</button>
                                <button type="submit" id="submit_new_customer" class="btn btn-primary">{{translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @php($order=\App\Model\Order::find(session('last_order')))
        @if($order)
        @php(session(['last_order'=> false]))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('Print Invoice')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row" style="font-family: emoji;">
                        <div class="col-md-12">
                            <center>
                                <input type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea')"
                                    value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                                <a href="{{url()->previous()}}" class="btn btn-danger non-printable">{{translate('Back')}}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                        <div class="row" id="printableArea" style="margin: auto;">
                            @include('branch-views.pos.order.invoice')
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endif
{{--    </main>--}}
@endsection

@push('script_2')
<!-- ========== END MAIN CONTENT ========== -->

<!-- ========== END SECONDARY CONTENTS ========== -->

<!-- JS Implementing Plugins -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<!-- JS Front -->
<script src="{{asset('public-assets/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/sweet_alert.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/toastr.js"></script>
{{--{!! Toastr::message() !!}--}}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif


<!-- JS Plugins Init. -->
<script>

    $('#order_taken_by').select2();

    $(document).on('change', '#order_taken_by', function () {
        var name = $(this).find('option:selected').text();
        $('#currentStaffName').text(name);
        store_key('order_taken_by', $(this).val());
    });

    $(document).on('ready', function () {
        @if($order)
            print_invoice('{{$order->id}}')
        @endif
    });

    function printDiv(divName) {
        let printContents = document.getElementById(divName).innerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }

    function set_category_filter(id) {
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('category_id', id);
        location.href = nurl;
    }


    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        var keyword= $('#datatableSearch').val();
        var nurl = new URL('{!!url()->full()!!}');
        nurl.searchParams.set('keyword', keyword);
        location.href = nurl;
    });

    function changeCustomerId(value) {
        store_key('customer_id', value);
        var node = $('#customer_address_id');

        $.get({
            url: '{{route('branch.pos.customer-address-list')}}' + '?customer_id=' + value,
            success: function (data) {
                node.html('');
                node.append('<option value="">New Address</option>');
                data.addresses.forEach(function (address) {
                    node.append('<option value="' + address.id + '">' + address.address + '</option>')
                });
                node.trigger('change');
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal', 'customer')}}' + '?customer_id=' + value,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#orders-customer-table').html(response)
            },
        });

    }

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('branch.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                toastr.success(key+' '+'{{translate('selected')}}!', {
                    CloseButton: true,
                    ProgressBar: true
                });
                if (data === 'table_id') {
                    $('#pay_after_eating_li').css('display', 'block')
                }
            },
        });
    }

    function addon_quantity_input_toggle(e)
    {
        var cb = $(e.target);
        if(cb.is(":checked"))
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'visible'});
        }
        else
        {
            cb.siblings('.addon-quantity-input').css({'visibility':'hidden'});
        }
    }
    function quickView(product_id) {
        $.ajax({
            url: '{{route('branch.pos.quick-view')}}',
            type: 'GET',
            data: {
                product_id: product_id
            },
            dataType: 'json', // added data type
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log("success...");
                console.log(data);

                // $("#quick-view").removeClass('fade');
                // $("#quick-view").addClass('show');

                $('#quick-view').modal('show');
                $('#quick-view-modal').empty().html(data.view);
            },
            complete: function () {
                $('#loading').hide();
            },
        });

    }

    function checkAddToCartValidity() {
        return true;
    }

    function cartQuantityInitialize() {
        $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    confirmButtonText:'{{translate("Ok")}}',
                    text: '{{translate('Sorry, the minimum value was reached')}}'
                });
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    confirmButtonText:'{{translate("Ok")}}',
                    text: '{{translate('Sorry, stock limit exceeded')}}.'
                });
                $(this).val($(this).data('oldValue'));
            }
        });
        $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });

        $('#half_half').click(function () {
            if ($(this).is(':checked')) {
                $('.btn-number').attr('disabled', true);
                $('.input-number').attr('disabled', true);
            } else {
                $('.btn-number').removeAttr('disabled');
                $('.input-number').removeAttr('disabled');
            }
        });
    }

    function getVariantPrice() {
        if ($('#add-to-cart-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('branch.pos.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                }
            });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('branch.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.data == 2) {
                        Swal.fire({
                            icon: 'info',
                            title: '{{translate("Cart")}}',
                            confirmButtonText:'{{translate("Ok")}}',
                            text: "{{translate('Please add other half of the previous item')}}"
                        });
                        return false;
                    }
                    else if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: '{{translate("Cart")}}',
                            confirmButtonText:'{{translate("Ok")}}',
                            text: "{{translate('Product already added in cart')}}"
                        });
                        return false;
                    } else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{translate("Cart")}}',
                            confirmButtonText:'{{translate("Ok")}}',
                            text: '{{translate('Sorry, product out of stock')}}.'
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{translate('Item has been added in your cart')}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: '{{translate("Cart")}}',
                confirmButtonText:'{{translate("Ok")}}',
                text: '{{translate('Please choose all the options')}}'
            });
        }
    }

    function removeFromCart(key) {
        $.post('{{ route('branch.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                updateCart();
                toastr.info('{{translate('Item has been removed from cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

        });
    }

    function emptyCart() {
        $.post('{{ route('branch.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
            updateCart();
            toastr.info('{{translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    }

    function updateCart() {
        var deliveryChargeHidden = $('.deliveryChargeInTable').hasClass('d-none');

        if(!deliveryChargeHidden){
            var deliveryCharge = $('#deliveryChargeInTableValue').text();
        }

        $.post('<?php echo e(route('branch.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
            if (!deliveryChargeHidden) {
                $('#deliveryChargeInTableValue').text(deliveryCharge);
                var currency = deliveryCharge.charAt(0);
                var total = parseFloat($('#posTotalValue').text().slice(1)) + parseFloat(deliveryCharge.slice(1))
                $('#posTotalValue').text(currency + total.toFixed(2));
                $('.deliveryChargeInTable').removeClass('d-none');
            }
        });
    }

   $(function(){
        $(document).on('click','input[type=number]',function(){ this.select(); });
    });


    function updateQuantity(e){
        var element = $( e.target );
        var minValue = parseInt(element.attr('min'));
        // maxValue = parseInt(element.attr('max'));
        var valueCurrent = parseInt(element.val());

        var key = element.data('key');
        if (valueCurrent >= minValue) {
            $.post('{{ route('branch.pos.updateQuantity') }}', {_token: '{{ csrf_token() }}', key: key, quantity:valueCurrent}, function (data) {
                updateCart();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: '{{translate("Cart")}}',
                confirmButtonText:'{{translate("Ok")}}',
                text: '{{translate('Sorry, the minimum value was reached')}}'
            });
            element.val(element.data('oldValue'));
        }
        // if (valueCurrent <= maxValue) {
        //     $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        // } else {
        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Cart',
        //         text: 'Sorry, stock limit exceeded.'
        //     });
        //     $(this).val($(this).data('oldValue'));
        // }


        // Allow: backspace, delete, tab, escape, enter and .
        if(e.type == 'keydown')
        {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    };

    // INITIALIZATION OF SELECT2
    // =======================================================
    // $('.js-select2-custom').each(function () {
    //     var select2 = $.HSCore.components.HSSelect2.init($(this));
    // });



    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('branch.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            },
        },
    });

    @if(session()->get('customer_id'))

    var customerSelect = $('.js-data-example-ajax');

    $.get({
        url: '{{route('branch.pos.customers')}}',
        data: {
            customer_id: {{session()->get('customer_id')}}
        },
        success: function (data) {
            var option = new Option(data[0].text, data[0].id, true, true);
            customerSelect.append(option).trigger('change');
            customerSelect.trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
        }
    });

    @endif


        // $("#order_place").submit(function(e) {

    //     e.preventDefault(); // avoid to execute the actual submit of the form.

    //     var form = $(this);
    //     form.append("user_id", $('#customer').val());

    //     form.submit();
    // });

    $('#order_place').submit(function(eventObj) {

        if(!$('#order_taken_by').val()) {
            eventObj.preventDefault();
            Swal.fire({
                icon: 'error',
                title: '{{translate("Error")}}',
                text: '{{translate('Please select order taken by')}}'
            });
            return false;
        }

        if($('#customer').val())
        {
            $(this).append('<input type="hidden" name="user_id" value="'+$('#customer').val()+'" /> ');
        }
        return true;
    });

</script>
<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public-assets/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
@endpush
{{-- </body>
</html> --}}


<!-- Modal -->
<div class="modal fade" id="orders-customer-modal" role="dialog" aria-labelledby="orders-customer-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="orders-customer-table">

                </div>
            </div>
        </div>
    </div>
</div>

