

<div class="pt-4">
    <div class="text-dark d-flex mb-2">{{translate('Receive_By')}} :</div>
    <ul class="list-unstyled option-buttons">
        <li>
            <input class="receiveBy" type="radio" id="pick-up" value="pick-up" name="receive_by" hidden="" @if(!isset($delivery_address)) checked="" @endif >
            <label for="pick-up" class="btn btn-bordered px-4 mb-0">{{translate('Pickup')}}</label>
        </li>
        <li>
            <input class="receiveBy" type="radio" value="delivery" id="delivery" name="receive_by" hidden="" @if(isset($delivery_address)) checked="" @endif>
            <label for="delivery" class="btn btn-bordered px-4 mb-0">{{translate('Delivery')}}</label>
        </li>
    </ul>
</div>

<div class="pt-4 {{ isset($delivery_address) ? '' : 'd-none' }}" id="posAddress">

    <div class="form-group">
        <label>Customer Address List</label>
        <select class="form-control" name="customer_address_id" id="customer_address_id" onchange="get_address(this.value)">
            <option value="">New Address</option>
            @foreach(\App\Model\CustomerAddress::where('user_id', session()->get('customer_id'))->get() as $address)
                <option value="{{ $address->id }}">{{ $address->address }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label class="control-label">{{translate('Address_Line')}}
        <span class="text-danger">*</span>
        </label>
        <input id="address" type="text" class="form-control" name="address" placeholder="23/A Block,Sector 4" value="{{ isset($delivery_address) ? $delivery_address->address : '' }}">
        <input type="hidden" name="latitude" id="latitude" value="{{ isset($delivery_address) ? $delivery_address->latitude : '' }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ isset($delivery_address) ? $delivery_address->longitude : '' }}">
        <input type="hidden" name="distance" id="distance">
        <input type="hidden" name="address_type" id="address_type" value="{{ isset($delivery_address) ? $delivery_address->address_type : '' }}">
    </div>
    <div class="form-group">
        <label class="control-label">{{translate('Street_Number')}}</label>
        <input id="road" type="text" class="form-control" name="road" placeholder="EX: 10th Street" value="{{ isset($delivery_address) ? $delivery_address->road : '' }}">
    </div>
    <div class="form-group">
        <label class="control-label">
            House / Floor Number
        </label>
        <div class="row">
            <div class="col-md-6">
                <input id="house" type="text" class="form-control" name="house" placeholder="EX: 02" value="{{ isset($delivery_address) ? $delivery_address->house : '' }}">
            </div>
            <div class="col-md-6">
                <input id="floor" type="text" class="form-control" name="floor" placeholder="EX: 2B" value="{{ isset($delivery_address) ? $delivery_address->floor : '' }}">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label">{{translate('Contact_Person_Name')}}
            <span class="text-danger">*</span>
        </label>
        <input id="contact_person_name" type="text" class="form-control" name="contact_person_name" placeholder="Enter contact person name" value="{{ isset($delivery_address) ? $delivery_address->contact_person_name : '' }}">
    </div>
    <div class="form-group">
        <label class="control-label">{{translate('Contact_Person_Number')}}
            <span class="text-danger">*</span>
        </label>
        <input id="contact_person_number" type="text" class="form-control" name="contact_person_number" placeholder="Enter contact person number" value="{{ isset($delivery_address) ? $delivery_address->contact_person_number : '' }}">
    </div>
</div>

@push('script_2')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=places,geometry&v=3.51"></script>
<script>

    var customerAddressSelect = $.HSCore.components.HSSelect2.init($('#customer_address_id'));

    var lat1 = {{ $branch->latitude }};
    var lng1 = {{ $branch->longitude }};

    function calculateDistance(lat2, lng2) {

        if (lat2 && lng2) {
            var distance = google.maps.geometry.spherical.computeDistanceBetween(
                new google.maps.LatLng(lat1, lng1),
                new google.maps.LatLng(lat2, lng2)
            );

            $('#distance').val(distance);

            return distance / 1000;
        }
    }

    var isDeliverySet = {{ isset($delivery_address) ? 1 : 0 }};
    var addressId = {{ isset($delivery_address) ? $delivery_address->id : 0 }};
    var latitude = {{ isset($delivery_address) ? $delivery_address->latitude : 0 }};
    var longitude = {{ isset($delivery_address) ? $delivery_address->longitude : 0 }};

    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();

            var lat2 = place.geometry['location'].lat();
            var lng2 = place.geometry['location'].lng();

            $('#latitude').val(lat2);
            $('#longitude').val(lng2);

            calculateDistance(lat2, lng2);

        });
    }

        $(document).on('click', '.receiveBy', function(){

            var posTotalValue = $('#posTotalValue');
            var total = parseFloat(posTotalValue.text().substring(1, posTotalValue.text().length));
            var symbol = posTotalValue.text().substring(0, 1);
            var deliveryCharge = parseFloat('{{ $delivery_charge }}');

            if($(this).val() == 'delivery'){
                $('#posAddress').removeClass('d-none');
                $('#posCod').removeClass('d-none');
                $('.deliveryChargeInTable').removeClass('d-none');
                $('#deliveryChargeInTableValue').text( '{{ \App\CentralLogics\Helpers::set_symbol($delivery_charge) }}' );

                posTotalValue.text( symbol + (total + deliveryCharge).toFixed(2) );
            }
            else{
                $('#posAddress').addClass('d-none');
                $('#posCod').addClass('d-none');
                $('.deliveryChargeInTable').addClass('d-none');

                posTotalValue.text( symbol + (total - deliveryCharge).toFixed(2) );
            }
        });


     function get_address(id) {
        if(id) {

            $.get({
                url: '{{ route('branch.pos.customer-address') }}' + '?address_id=' + id,
                success: function (data) {
                    var address = data.address;
                    $('#address').val(address.address);
                    $('#latitude').val(address.latitude);
                    $('#longitude').val(address.longitude);
                    $('#address_type').val(address.address_type);
                    $('#road').val(address.road);
                    $('#house').val(address.house);
                    $('#floor').val(address.floor);
                    $('#contact_person_name').val(address.contact_person_name);
                    $('#contact_person_number').val(address.contact_person_number);

                    calculateDistance(address.latitude, address.longitude);
                }
            })

        } else if(!isDeliverySet) {
            $('#address').val('');
            $('#latitude').val('');
            $('#longitude').val('');
            $('#distance').val('');
            $('#address_type').val('');
            $('#road').val('');
            $('#house').val('');
            $('#floor').val('');
            $('#contact_person_name').val('');
            $('#contact_person_number').val('');

        } else if(isDeliverySet) {
            if(addressId) {
                customerAddressSelect.val(addressId).trigger('change');
            }
            calculateDistance(latitude, longitude);
        }
    }

</script>
@endpush
