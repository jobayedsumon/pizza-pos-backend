<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Title -->
    <title>@yield('title')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public-assets/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public-assets/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public-assets/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public-assets/assets/admin')}}/css/style.css?v=1.0">
    @stack('css_or_js')

    <style>

        .nav-tabs {
            border: 0 !important;
        }

        .nav-tabs .nav-link {
            padding: 10px 30px !important;
        }

        .nav-tabs .navbar-vertical-aside-has-menu .header-menu {
            border: 1px solid lightpink;
            position: absolute;
            top: 60px !important;
            background: white;
        }

        .nav-tabs .navbar-vertical-aside-has-menu .header-menu .nav-link {
            padding: 10px 30px !important;
            min-width: 200px;
        }

        .modal-content {
            margin-top: 100px;
            overflow-y: auto;
        }

        .delivery-menu-modal {
            max-height: 800px;
        }

        .modal-body {
            padding: 1rem !important;
        }

        @media screen and (min-width: 1200px) {
            #order-details-modal .modal-dialog {
                min-width: 1150px;
            }
        }

        @media screen and (max-width: 1200px) {
            #order-details-modal .modal-dialog {
                min-width: 1100px;
            }
        }

        @media screen and (max-width: 1000px) {
            #order-details-modal .modal-dialog {
                min-width: 900px;
            }
        }

        @media screen and (max-width: 800px) {
            #order-details-modal .modal-dialog {
                min-width: 750px;
            }
        }

        @media screen and (max-width: 600px) {
            #order-details-modal .modal-dialog {
                min-width: 580px;
            }
        }

        .modal-close {
            border-radius: 25px;
            position: absolute;
            padding: 10px !important;
        }

        .modal-backdrop {
            opacity: 1.8 !important;
        }

        .modal {
            overflow-y:auto;
        }

        .dropdown-menu {
            padding: 0 !important;
        }

        .card-body {
            padding: 0.8rem !important;
        }

    </style>

    <script
        src="{{asset('public-assets/assets/admin')}}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js"></script>
    <link rel="stylesheet" href="{{asset('public-assets/assets/admin')}}/css/toastr.css">
</head>

<body class="footer-offset">

{{--loader--}}
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
{{--loader--}}

<!-- Builder -->
@include('layouts.branch.partials._front-settings')
<!-- End Builder -->

<!-- JS Preview mode only -->
@include('layouts.branch.partials._header')
{{--@include('layouts.branch.partials._sidebar')--}}
<!-- END ONLY DEV -->

<main id="content" role="main" class="main pointer-event">
    <!-- Content -->
@yield('content')
<!-- End Content -->

    <!-- Footer -->
@include('layouts.branch.partials._footer')
<!-- End Footer -->

    <div class="modal fade" id="popup-modal">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content border-primary">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="popup-modal-table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<!-- ========== END MAIN CONTENT ========== -->

<!-- ========== END SECONDARY CONTENTS ========== -->
<script src="{{asset('public-assets/assets/admin')}}/js/custom.js"></script>
<!-- JS Implementing Plugins -->

@stack('script')

<!-- JS Front -->
<script src="{{asset('public-assets/assets/admin')}}/js/vendor.min.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/sweet_alert.js"></script>
<script src="{{asset('public-assets/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

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
    $(document).on('ready', function () {

        // BUILDER TOGGLE INVOKER
        // =======================================================
        $('.js-navbar-vertical-aside-toggle-invoker').click(function () {
            $('.js-navbar-vertical-aside-toggle-invoker i').tooltip('hide');
        });
        // INITIALIZATION OF UNFOLD
        // =======================================================
        $('.js-hs-unfold-invoker').each(function () {
            var unfold = new HSUnfold($(this)).init();
        });


        // INITIALIZATION OF NAVBAR VERTICAL NAVIGATION
        // =======================================================
        var sidebar = $('.js-navbar-vertical-aside').hsSideNav();

    });
</script>

@stack('script_2')
<audio id="myAudio">
    <source src="{{asset('public-assets/assets/admin/sound/notification.mp3')}}" type="audio/mpeg">
</audio>

<script>
    var audio = document.getElementById("myAudio");

    function playAudio() {
        audio.play();
    }

    function pauseAudio() {
        audio.pause();
    }
</script>
<script>
    setInterval(function () {
        $.get({
            url: '{{route('branch.get-restaurant-data')}}',
            dataType: 'json',
            success: function (response) {
                let data = response.data;
                if (data.new_order > 0) {
                    playAudio();
                    $('#popup-modal').appendTo("body").modal('show');
                    $.get({
                        url: '{{route('branch.orders.unchecked-orders-modal','confirmed')}}',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function (response) {
                            $('#popup-modal-table').html(response)
                        },
                    });
                }
            },
        });
    },10000);

    $(function () {

        /*$('a.js-navbar-vertical-aside-menu-link.nav-link.nav-link-toggle').mouseenter(function (e) {
            e.stopPropagation();
            $(this).siblings('.js-navbar-vertical-aside-submenu').css('display','block')
            $(this).addClass('show')
        })

        $('a.js-navbar-vertical-aside-menu-link.nav-link.nav-link-toggle').mouseleave(function (e) {
            e.stopPropagation();
            setTimeout(function () {
                $(this).siblings('.js-navbar-vertical-aside-submenu').css('display','none')
                $(this).removeClass('show')
            }, 1000)
        })*/

        $('body').on('click','.order-details-view-btn', function (e) {
            e.preventDefault()

            /*$(this).parents('.modal').first().modal('hide')*/

            const id = $(this).data("id");
            const url = '{{route('branch.orders.order-details-modal')}}'
            // fetch order details
            $.post({
                url: url,
                data: {id:id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#order-details-table').html(response)
                },
            });
            // open order-details modal
            $('#order-details-modal').modal('show').focus();
        })

        $('body').on('click','.delivery-popup-accept-all-order-btn', function (e) {
            e.preventDefault()
            const url = '{{route('branch.pos.accept-all-orders')}}'
            // check order
            $.post({
                url: url,
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#pos-orders-table').html(response)
                    // and print invoice
                    print_multiple_invoice(response.ids)
                },
            });
        })

        $('body').on('click','.delivery-popup-accept-order-btn', function (e) {
            e.preventDefault()
            const id = $(this).data("id");
            const url = '{{route('branch.pos.accept-order')}}'
            // check order
            $.post({
                url: url,
                data: {id:id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (response) {
                    $('#pos-orders-table').html(response)
                },
            });
            // and print invoice
            print_invoice(id)
        })

        $.get({
            url: '{{route('branch.orders.orders-modal','delivered')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#orders-delivered-table').html(response)
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal','out_for_delivery')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#orders-out-for-delivery-table').html(response)
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal','confirmed')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#orders-confirmed-table').html(response)
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal','all')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#orders-all-table').html(response)
            },
        });

        $.post({
            url: '{{route('branch.pos.delivery-popup-orders')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#pos-orders-table').html(response)
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal','processing')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#delivery-menu-table').html(response)
            },
        });

        $.get({
            url: '{{route('branch.orders.orders-modal','pay_pickup')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {
                $('#pay-pickup-table').html(response)
            },
        });

        $('body').on('submit','.delivery-menu-form-data', function (e) {
            e.preventDefault()

            let form = $('.delivery-menu-form-data')
            $.post({
                url: '{{route('branch.pos.delivery-popup-orders')}}',
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response)
                    // $('#delivery-menu-table').html(response)
                    $('#pos-orders-table').html(response)
                },
            });
        })

        $('body').on('submit','.orders-form-data', function (e) {
            e.preventDefault()

            const status = $(this).data('status')

            let form = $(this)
            let url = $(this).attr('action')
            console.log(status,url)
            $.post({
                url: url,
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: $(this).serialize(),
                success: function (response) {
                    console.log(status,response)
                    // $('#delivery-menu-table').html(response)
                    if(status === 'all'){
                        $('#orders-all-table').html(response)
                    } else if(status === 'confirmed'){
                        $('#orders-confirmed-table').html(response)
                    } else if(status === 'out_for_delivery'){
                        $('#orders-out-for-delivery-table').html(response)
                    } else if(status === 'processing'){
                        $('#delivery-menu-table').html(response)
                    } else if(status === 'delivered'){
                        $('#orders-delivered-table').html(response)
                    } else if(status === 'pay_pickup'){
                        $('#pay-pickup-table').html(response)
                    }
                },
            });
        })

    })

    function check_order() {
        location.href = '{{route('branch.order.list',['status'=>'all'])}}';
    }

    function route_alert(route, message) {
        Swal.fire({
            title: '{{ translate('Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('No') }}',
            confirmButtonText: '{{ translate('Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }

    function form_alert(id, message) {
        Swal.fire({
            title: '{{ translate('Are you sure?') }}',
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#FC6A57',
            cancelButtonText: '{{ translate('No') }}',
            confirmButtonText: '{{ translate('Yes') }}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#'+id).submit()
            }
        })
    }
</script>

<script>
    function call_demo(){
        toastr.info('{{ translate('Update option is disabled for demo!') }}', {
            CloseButton: true,
            ProgressBar: true
        });
    }
</script>

<script>

    function print_multiple_invoice(order_ids) {
        $.post({
            data: {order_ids:order_ids},
            url: '{{route('branch.pos.multi-invoice')}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                // console.log("success...multi...")
                $('#print-invoice').modal('show');
                $('#printableArea').empty().html(data.view);

                // print is same window and reload after print
                document.body.innerHTML = document.getElementById('printableArea').innerHTML;
                window.print();

                setTimeout(function () {
                    location.reload();
                },1000)

                // print in new window and no need to reload after print
                /*let winPrint = window.open('', '_blank', 'left=0,top=0,width=800,height=600,toolbar=0,scrollbars=0,status=0');
                winPrint.document.write(document.getElementById('printableArea').innerHTML);
                winPrint.document.close();
                winPrint.focus();
                winPrint.print();
                setTimeout(function () {
                    winPrint.close()
                },1000)
                $('.modal-backdrop').hide();*/
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function print_invoice(order_id) {
        $.get({
            url: '{{url('/')}}/branch/pos/invoice/'+order_id,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                // console.log("success...")
                $('#print-invoice').modal('show');
                $('#printableArea').empty().html(data.view);

                // print is same window and reload after print
                document.body.innerHTML = document.getElementById('printableArea').innerHTML;
                window.print();

                setTimeout(function () {
                    window.location.reload();
                },500)
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    }

    function printDiv(divName) {
        document.body.innerHTML = document.getElementById(divName).innerHTML;
        window.print();
        location.reload();
    }
</script>

<script>
    $('#from_date, #to_date').change(function () {
        let from = $('#from_date').val();
        let to = $('#to_date').val();
        if(from != ''){
            $('#to_date').attr('required','required');
        }
        if(to != ''){
            $('#from_date').attr('required','required');
        }
        if (from != '' && to != '') {
            if (from > to) {
                $('#from_date').val('');
                $('#to_date').val('');
                toastr.error('{{\App\CentralLogics\translate('Invalid date range')}}!');
            }
        }

    })
</script>

{{--order-view-modal-scripts---start--}}

    <script>
        $('body').on('click','.assign-delivery-man-modal-btn', function (e) {
            const order_id = $(this).data('order-id')
            const delivery_man_id = $(this).data('delivery-man-id')
            $.ajax({
                type: "POST",
                url: '{{route('branch.orders.add-delivery-man-modal')}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    order_id:order_id,
                    delivery_man_id:delivery_man_id
                },
                success: function (data) {
                    if(data.status == true) {
                        toastr.success('{{\App\CentralLogics\translate("Delivery man successfully assigned/changed")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 500)
                    }else{
                        toastr.error('{{\App\CentralLogics\translate("Deliveryman man can not assign/change in that status")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function (xhr, error, status) {
                    toastr.error('{{\App\CentralLogics\translate("Add valid data")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    console.log(xhr,error,status)
                }
            });
        })

        function last_location_view() {
            toastr.warning('{{\App\CentralLogics\translate("Only available when order is out for delivery!")}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

    <script>
        function predefined_time_input(min) {
            document.getElementById("extra_minute").value = min;
        }
    </script>
    {{--@if($order['order_type'] != 'pos' && $order['order_type'] != 'take_away' && ($order['order_status'] != DELIVERED && $order['order_status'] != RETURNED && $order['order_status'] != CANCELED && $order['order_status'] != FAILED && $order['order_status'] != COMPLETED))
        <script>
            const expire_time = "{{ $order['remaining_time'] }}";
            var countDownDate = new Date(expire_time).getTime();
            const time_zone = "{{ \App\CentralLogics\Helpers::get_business_settings('time_zone') ?? 'UTC' }}";

            var x = setInterval(function() {
                var now = new Date(new Date().toLocaleString("en-US", {timeZone: time_zone})).getTime();

                var distance = countDownDate - now;

                var days = Math.trunc(distance / (1000 * 60 * 60 * 24));
                var hours = Math.trunc((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.trunc((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.trunc((distance % (1000 * 60)) / 1000);


                document.getElementById("timer-icon").classList.remove("d-none");
                document.getElementById("edit-icon").classList.remove("d-none");
                $text = (distance < 0) ? "{{ translate('over') }}" : "{{ translate('left') }}";
                document.getElementById("counter").innerHTML = Math.abs(days) + "d " + Math.abs(hours) + "h " + Math.abs(minutes) + "m " + Math.abs(seconds) + "s " + $text;
                if (distance < 0) {
                    var element = document.getElementById('counter');
                    element.classList.add('text-danger');
                }
            }, 1000);
        </script>
    @endif--}}
{{--
url: '{{url('/')}}/branch/orders/ajax-change-delivery-time-date/{{$order['id']}}?' + t.name + '=' + t.value,
--}}
    <script>
        function changeDeliveryTimeDate(t) {
            let name = t.name
            let value = t.value
            $.ajax({
                type: "GET",

                data: {
                    name : name,
                    value : value
                },
                success: function (data) {
                    console.log(data)
                    if(data.status == true && name == 'delivery_date') {
                        toastr.success('{{\App\CentralLogics\translate("Delivery date changed successfully")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else if(data.status == true && name == 'delivery_time'){
                        toastr.success('{{\App\CentralLogics\translate("Delivery time changed successfully")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else {
                        toastr.error('{{\App\CentralLogics\translate("Order No is not valid")}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('{{\App\CentralLogics\translate("Add valid data")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }
    </script>

{{--order-view-modal-scripts---end--}}

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public-assets/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>

<script>
    var modals = ['pos-orders-modal', 'orders-delivered-modal',
        'orders-out-for-delivery-modal', 'orders-confirmed-modal',
        'orders-all-modal', 'delivery-menu-modal',
        'pay-pickup-modal', 'orders-customer-modal'];

    modals.forEach(function (modal) {
       $(`#${modal}`).on('shown.bs.modal', function (event) {
           $(this).find('.showDataButton').click();
           if ($(this).find('#datatableSearch_').val()) {
               $(this).find('.searchDataButton').click();
           }
       });
    });
</script>

</body>
</html>
