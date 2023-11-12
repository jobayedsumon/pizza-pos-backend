<div class="content">
    <!-- Page Header -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex align-items-center gap-1">
            <img width="20" class="avatar-img" src="{{asset('public-assets/assets/admin/img/icons/all_orders.png')}}" alt="">
            <span class="page-header-title">
                {{translate($status)}} {{translate('Orders')}}
                </span>
        </h2>
        <span class="badge badge-soft-dark rounded-50 fz-14">{{ /*$orders->total()*/ count($orders) }}</span>
    </div>
    <!-- End Page Header -->

    <!-- Filter Card -->
    <div class="card">
        <div class="card-body">
            <form class="orders-form-data" data-status="{{$status}}" action="{{route('branch.orders.orders-modal',$status)}}">
                @csrf
                <div class="row gy-3 gx-2 align-items-end">
                    <div class="col-12 pb-0">
                        <h4 class="mb-0">{{translate('Select Date Range')}}</h4>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="form-group mb-0">
                            <label class="text-dark">{{ translate('Start Date') }}</label>
                            <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="form-group mb-0">
                            <label class="text-dark">{{ translate('End Date') }}</label>
                            <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 d-flex gap-2">
                        <button type="reset" class="btn btn-secondary flex-grow-1">{{ translate('Clear') }}</button>
                        <button type="submit" class="btn btn-primary flex-grow-1 text-nowrap showDataButton">{{ translate('Show_Data') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Filter Card -->

    <!-- Header -->
    <div class="card-top px-card pt-4">
        <div class="row justify-content-between align-items-center gy-2">
            <div class="col-sm-10 col-md-8 col-lg-6">
                <form class="orders-form-data" data-status="{{$status}}" action="{{route('branch.orders.orders-modal',$status)}}">
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
                    <a class="btn btn-outline-primary" href="{{route('branch.orders.export-excel-orders',$status)}}">
                        <img width="14" src="{{asset('public-assets/assets/admin/img/icons/excel.png')}}" alt="">
                        {{translate('Export Excel')}}
                    </a>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Header -->

    <div class="py-4">
        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                <thead class="thead-light">
                <tr>
                    <th>{{translate('SL')}}</th>
                    <th>{{translate('order_ID')}}</th>
                    <th>{{translate('Delivery_Date')}}</th>
                    {{--                                <th>{{translate('Time_Slot')}}</th>--}}
                    <th>{{translate('Customer_Info')}}</th>
                    <th>{{translate('Branch')}}</th>
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
                            {{--{{ $orders->firstitem()+$key }}--}}
                        </td>
                        <td>
                            <a class="text-dark order-details-view-btn" data-id="{{$order['id']}}" href="javascript:void(0)">{{$order['id']}}</a>
                        </td>
                        <td>
                            <div>
                                {{date('d M Y',strtotime($order['created_at']))}}
                            </div>
                            <div>{{date('h:i A',strtotime($order['created_at']))}}</div>
                        </td>
                        {{--                                <td>12:30:00 - 15:30:00</td>--}}
                        <td>
                            @if($order->customer)
                                <a class="text-dark text-capitalize order-details-view-btn" data-id="{{$order['id']}}" href="javascript:void(0)">
                                    {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                    <br>
                                    <label class="badge badge-soft-info">{{$order->customer['phone']}}</label>
                                </a>
                            @else
                                <span class="text-capitalize text-muted">
                                        {{translate('walk_in_customer')}}
                                    </span>
                            @endif
                        </td>
                        <td><span class="badge-soft-info px-2 py-1 rounded">{{$order->branch->name}}</span></td>
                        <td>
                            <div>{{ \App\CentralLogics\Helpers::set_symbol($order['order_amount'])  }}</div>

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
                                <span class="badge-soft-info px-2 py-1 rounded">{{translate('pending')}}</span>
                            @elseif($order['order_status']=='confirmed')
                                <span class="badge-soft-info px-2 py-1 rounded">{{translate('confirmed')}}</span>
                            @elseif($order['order_status']=='processing')
                                <span class="badge-soft-warning px-2 py-1 rounded">{{translate('processing')}}</span>
                            @elseif($order['order_status']=='out_for_delivery')
                                <span class="badge-soft-warning px-2 py-1 rounded">{{translate('out_for_delivery')}}</span>
                            @elseif($order['order_status']=='delivered')
                                <span class="badge-soft-success px-2 py-1 rounded">{{translate('delivered')}}</span>
                            @else
                                <span class="badge-soft-danger px-2 py-1 rounded">{{str_replace('_',' ',$order['order_status'])}}</span>
                            @endif
                        </td>
                        <td class="text-capitalize">
                                <span class="badge-soft-success px-2 rounded">{{translate($order['order_type'])}}
                                    </span>

                        </td>
                        <td>
                            {{--<div class="dropdown">--}}
                            {{--<button class="btn btn-outline-secondary dropdown-toggle" type="button"--}}
                            {{--id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"--}}
                            {{--aria-expanded="false">--}}
                            {{--<i class="tio-settings"></i>--}}
                            {{--</button>--}}
                            {{--<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
                            {{--<a class="dropdown-item"--}}
                            {{--href="{{route('branch.orders.details',['id'=>$order['id']])}}"><i--}}
                            {{--class="tio-visible"></i> {{translate('view')}}</a>--}}
                            {{--<a class="dropdown-item" target="_blank"--}}
                            {{--href="{{route('branch.orders.generate-invoice',[$order['id']])}}"><i--}}
                            {{--class="tio-download"></i> {{translate('invoice')}}</a>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            <div class="d-flex justify-content-center gap-2 align-items-center">
                                <a class="btn btn-sm btn-outline-info square-btn order-details-view-btn" data-id="{{$order['id']}}"
                                   href="javascript:void(0);">
                                    <i class="tio-visible"></i></a>
                                <button class="btn btn-sm btn-outline-success square-btn" target="_blank" type="button"
                                        onclick="print_invoice('{{$order->id}}')"><i
                                        class="tio-download"></i></button>
                                @if($status == 'customer')
                                <a class="btn btn-sm btn-outline-info " href="{{ route('branch.pos.reorder', $order->id) }}">
                                    Reorder
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        {{--<div class="table-responsive mt-4 px-3">
            <div class="d-flex justify-content-lg-end">
                <!-- Pagination -->
                {!! $orders->links() !!}
            </div>
        </div>--}}
    </div>
</div>

<div class="modal fade" id="print-invoice" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
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
