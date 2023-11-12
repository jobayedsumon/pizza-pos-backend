<div class="content">
    <!-- Page Header -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
        <h2 class="h1 mb-0 d-flex align-items-center gap-2">
            <img width="20" class="avatar-img" src="{{asset('public-assets/assets/admin/img/icons/all_orders.png')}}" alt="">
            <span class="page-header-title">
                    {{translate('POS_Orders')}}
                </span>
        </h2>
        <span class="badge badge-soft-dark rounded-50 fz-14">{{ /*$orders->total()*/ count($orders) }}</span>
    </div>
    <!-- End Page Header -->

    <!-- Card -->
    <div class="card delivery-menu-modal">
        <div class="card">
            <div class="card-body">
                <form class="delivery-menu-form-data">
                    <div class="row gy-3 gx-2 align-items-end">
                        <div class="col-12 pb-0">
                            <h4 class="mb-0">{{translate('select_date_range')}}</h4>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-dark">{{translate('start_date')}}</label>
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group mb-0">
                                <label class="text-dark">{{translate('end_date')}}</label>
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <button type="submit" class="btn btn-primary btn-block showDataButton">{{translate('show_data')}}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-10 col-md-8 col-lg-6">
                        <form class="delivery-menu-form-data">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                       class="form-control"
                                       placeholder="{{translate('Search by ID, customer or payment status')}}" aria-label="Search"
                                       value="{{$search}}" autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary searchDataButton">
                                        {{translate('Search')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-2 col-md-4 col-lg-6 d-flex justify-content-end">
                        <div>
                            <a type="submit" class="btn btn-outline-primary" href="{{route('branch.orders.export-excel-pos')}}">
                                <img width="14" src="{{asset('public-assets/assets/admin/img/icons/excel.png')}}" alt="">
                                {{translate('Export Excel')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                <tr>
                    <th>{{translate('SL')}}</th>
                    <th>{{translate('order_ID')}}</th>
                    <th>{{translate('order_Date')}}</th>
                    <th>{{translate('customer_Info')}}</th>
                    <th>{{translate('branch')}}</th>
                    <th>{{translate('total_Amount')}}</th>
                    <th>{{translate('order_Status')}}</th>
                    <th>{{translate('order_Type')}}</th>
                    <th class="text-center">{{translate('actions')}}</th>
                </tr>
                </thead>

                <tbody id="set-rows">
                @foreach($orders as $key => $order)
                    <tr class="status-{{$order['order_status']}} class-all">
                        <td class="">
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <a class="text-dark order-details-view-btn" data-id="{{$order['id']}}" href="javascript:void(0)">{{$order['id']}}</a>
                        </td>
                        <td>
                            <div>{{date('d M Y',strtotime($order['created_at']))}}</div>
                            <div>{{date('h:m A',strtotime($order['created_at']))}}</div>
                        </td>
                        <td>
                            @if($order->customer)
                                <label class="badge badge-success">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</label>
                                <br>
                                <label class="badge badge-soft-info">{{$order->customer['phone']}}</label>
                            @elseif($order['user_id'] == null)
                                <label class="badge badge-soft-success">{{translate('walk_in_customer')}}</label>
                            @else
                                <span class="text-capitalize badge-dark">{{translate('walk_in_customer')}}</span>
                            @endif
                        </td>
                        <td>
                            {{translate($order['branch']['name'])}}
                        </td>
                        <td>
                            <div>{{ \App\CentralLogics\Helpers::set_symbol($order['order_amount']) }}</div>

                            @if($order->payment_status=='paid')
                                <span class="badge badge-soft-success">{{translate('paid')}}
                                        </span>
                            @else
                                <span class="badge badge-soft-danger">{{translate('unpaid')}}
                                        </span>
                            @endif
                        </td>
                        <td class="text-capitalize">
                            @if($order['order_status']=='pending')
                                <span class="badge-soft-info px-2 rounded">{{translate('pending')}}
                                        </span>
                            @elseif($order['order_status']=='confirmed')
                                <span class="badge-soft-success px-2 rounded">{{translate('confirmed')}}
                                        </span>
                            @elseif($order['order_status']=='processing')
                                <span class="badge-soft-warning px-2 rounded">{{translate('processing')}}
                                        </span>
                            @elseif($order['order_status']=='picked_up')
                                <span class="badge-soft-warning px-2 rounded">{{translate('out_for_delivery')}}
                                        </span>
                            @elseif($order['order_status']=='delivered')
                                <span class="badge-soft-success px-2 rounded">{{translate('delivered')}}
                                        </span>
                            @else
                                <span class="badge-soft-danger px-2 rounded">{{str_replace('_',' ',$order['order_status'])}}
                                        </span>
                            @endif
                        </td>
                        <td class="text-capitalize">
                            @if($order['order_type']=='take_away')
                                <span class="badge-soft-success px-2 rounded">{{translate('take_away')}}
                                        </span>
                            @else
                                <span class="badge-soft-success px-2 rounded">{{translate('delivery')}}
                                        </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a class="btn btn-sm btn-outline-info square-btn order-details-view-btn" data-id="{{$order['id']}}"
                                   href="javascript:void(0);">
                                    <i class="tio-visible"></i></a>
                                <a class="btn btn-sm btn-outline-success square-btn" target="_blank" type="button"
                                   onclick="print_invoice('{{$order->id}}')"><i
                                        class="tio-download"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="print-invoice" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-primary">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('print')}} {{translate('invoice')}}</h5>
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

                </div>
            </div>
        </div>
    </div>
</div>
