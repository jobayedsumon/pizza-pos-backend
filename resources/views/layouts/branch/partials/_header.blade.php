<div id="headerMain" class="d-none">
    <header id="header" class="navbar justify-content-between navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">

        <div class="d-flex justify-content-around">

            <!-- Navbar Vertical Toggle -->
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3 d-flex align-items-center">
                {{--<i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                   data-placement="right" title="Collapse"></i>--}}
                <i class="tio-category-outlined navbar-vertical-aside-toggle-full-align"
                   data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                   data-toggle="tooltip" data-placement="right" title="Expand">
                    <span style="font-family: 'Roboto', sans-serif;">{{translate('Category')}}</span>
                </i>
            </button>
            <!-- End Navbar Vertical Toggle -->

            <div class="navbar-nav align-items-center flex-row">
                <div class="nav-item mx-2">
                    <!-- POS -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper nav-link nav-link-toggle" href="javascript:void(0);" data-hs-unfold-options='{
                                     "target": "#posNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                            <i class="tio-shopping nav-icon"></i>
                            <span class="">{{translate('POS')}}</span>
                        </a>

                        <div id="posNavbarDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                            <div class="dropdown-item">
                                <a type="button" class="btn-ghost-primary nav-link" data-toggle="modal" data-target="#pos-orders-modal">
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('order')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Model\Order::where('branch_id', auth('branch')->id())->Pos()->count()}}
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End POS -->
                </div>
            </div>

            <div class="navbar-nav align-items-center flex-row">
                <div class="nav-item mx-2">
                    <!-- Orders -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper nav-link nav-link-toggle" href="javascript:void(0);" data-hs-unfold-options='{
                                     "target": "#ordersNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                            <i class="tio-shopping-cart nav-icon"></i>
                            <span class="">
                                {{translate('order')}}
                            </span>
                        </a>

                        <div id="ordersNavbarDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account">
                            <div class="dropdown-item">
                                <a type="button" class="btn-ghost-primary nav-link" data-toggle="modal" data-target="#orders-all-modal">
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('all')}}
                                        <span class="badge badge-soft-info badge-pill ml-1">
                                            {{\App\Model\Order::notPos()->notDineIn()->where(['branch_id'=>auth('branch')->id()])->count()}}
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a type="button" class="btn-ghost-primary nav-link" data-toggle="modal" data-target="#orders-confirmed-modal">
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('confirmed')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Model\Order::notPos()->notSchedule()->where('order_type', '!=' , 'dine_in')->where(['order_status'=>'confirmed','branch_id'=>auth('branch')->id()])->count()}}
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a type="button" class="btn-ghost-primary nav-link" data-toggle="modal" data-target="#orders-out-for-delivery-modal">
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('out_for_delivery')}}
                                            <span class="badge badge-soft-warning badge-pill ml-1">
                                            {{\App\Model\Order::notPos()->notSchedule()->where(['order_status'=>'out_for_delivery','branch_id'=>auth('branch')->id()])->count()}}
                                        </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dropdown-item">
                                <a type="button" class="btn-ghost-primary nav-link" data-toggle="modal" data-target="#orders-delivered-modal">
                                    <span class="text-truncate sidebar--badge-container">
                                        {{translate('delivered')}}
                                            <span class="badge badge-soft-success badge-pill ml-1">
                                            {{\App\Model\Order::notPos()->notSchedule()->where(['order_status'=>'delivered','branch_id'=>auth('branch')->id()])->count()}}
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Orders -->
                </div>
            </div>

            <!-- Delivery -->
            <div class="navbar-vertical-aside-has-menu">
                <a type="button" class="btn btn-ghost-primary nav-link nav-link-toggle my-2" data-toggle="modal" data-target="#delivery-menu-modal">
                    <i class="tio-shopping nav-icon"></i>
                    <span class="">{{translate('Delivery')}}</span>
                </a>
            </div>
            <!-- End Delivery -->

            <div class="navbar-nav align-items-center flex-row">

                <div class="nav-item ml-4">
                    <!-- Account -->
                    <div class="hs-unfold">
                        <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper media gap-2" href="javascript:void(0);"
                           data-hs-unfold-options='{
                                     "target": "#accountNavbarDropdown",
                                     "type": "css-animation"
                                   }'>
                            <div class="media-body d-flex align-items-end flex-column">
                                <span class="card-title h5">{{auth('branch')->user()->name}}</span>
                                <span class="card-text fz-12 font-weight-bold">{{translate('Branch Admin')}}</span>
                            </div>
                            <div class="avatar avatar-sm {{--avatar-circle--}}">
                                <img class="avatar-img"
                                     onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                     src="{{asset('storage/app/public/branch')}}/{{auth('branch')->user()->image}}"
                                     alt="Image Description">
                                <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                            </div>
                        </a>

                        <div id="accountNavbarDropdown"
                             class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                             style="width: 16rem;">
                            <div class="dropdown-item">
                                <div class="media align-items-center">
                                    {{--<div class="avatar avatar-sm --}}{{--avatar-circle--}}{{--">
                                        <img class="avatar-img"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                             src="{{asset('storage/app/public/branch')}}/{{auth('branch')->user()->image}}"
                                             alt="Image Description">
                                    </div>--}}
                                    <div class="media-body">
                                        <span class="card-title h5">{{auth('branch')->user()->name}}</span>
                                        <span class="card-text">{{auth('branch')->user()->email}}</span>
                                    </div>
                                </div>
                            </div>

{{--                            --}}{{--<div class="dropdown-divider"></div>--}}

{{--                            <a class="dropdown-item" href="{{route('branch.settings')}}">--}}
{{--                                <span class="text-truncate pr-2" title="Settings">{{translate('settings')}}</span>--}}
{{--                            </a>--}}

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="javascript:void(0);" onclick="Swal.fire({
                                title: '{{translate('Do you want to logout ?')}}',
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#FC6A57',
                                cancelButtonColor: '#363636',
                                confirmButtonText: `{{translate('Yes')}}`,
                                cancelButtonText: `{{translate('No')}}`,
                                }).then((result) => {
                                if (result.value) {
                                location.href='{{route('branch.auth.logout')}}';
                                } else{
                                Swal.fire({
                                title: '{{translate("Canceled")}}',
                                confirmButtonText: '{{translate("Okay")}}',
                                })
                                }
                                })">
                                <span class="text-truncate pr-2" title="Sign out">{{translate('sign_out')}}</span>
                            </a>
                        </div>
                    </div>
                    <!-- End Account -->
                </div>
            </div>

            <!-- End Navbar -->
        </div>


        <div class="navbar-nav justify-content-center">
            <a target="_blank" href="/" class="btn btn-primary">
                Admin Login
            </a>
        </div>

    </header>
</div>
<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>

<!-- Modal -->
<div class="modal fade" id="orders-delivered-modal" role="dialog" aria-labelledby="orders-delivered-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="orders-delivered-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orders-out-for-delivery-modal" role="dialog" aria-labelledby="orders-out-for-delivery-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="orders-out-for-delivery-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orders-confirmed-modal" role="dialog" aria-labelledby="orders-confirmed-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="orders-confirmed-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="orders-all-modal" role="dialog" aria-labelledby="orders-all-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="orders-all-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="pos-orders-modal" role="dialog" aria-labelledby="pos-orders-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="pos-orders-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="delivery-menu-modal" role="dialog" aria-labelledby="delivery-menu-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="delivery-menu-table">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="order-details-modal" role="dialog" aria-labelledby="order-details-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-primary">
            <div class="modal-body">
                <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="order-details-table">

                </div>
            </div>
        </div>
    </div>
</div>
