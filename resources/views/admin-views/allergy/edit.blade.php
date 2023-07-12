@extends('layouts.admin.app')

@section('title', translate('Update Allergy'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('public/assets/admin/img/icons/attribute.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Allergy_Update')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->


        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{route('admin.allergy.update',[$allergy['id']])}}" method="post">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())

                        @if($data && array_key_exists('code', $data[0]))
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{$lang['default'] == true ? 'active':''}}" href="#" id="{{$lang['code']}}-link">{{ Helpers::get_language_name($lang['code']).'('.strtoupper($lang['code']).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="row">
                                <div class="col-sm-6">
                                    @foreach($data as $lang)
                                        @if(count($allergy['translations']))
                                            @php($translate = []);
                                            @foreach($allergy['translations'] as $t)
                                                @if($t->locale == $lang['code'] && $t->key=="name")
                                                    @php($translate[$lang['code']]['name'] = $t->value)
                                                @endif
                                            @endforeach
                                        @endif
                                            <div class="form-group {{$lang['default'] == false ? 'd-none':''}} lang_form" id="{{$lang['code']}}-form">
                                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} ({{strtoupper($lang['code'])}})</label>
                                                <input type="text" name="name[]"
                                                    class="form-control"
                                                    placeholder="{{ translate('New Allergy') }}"
                                                    value="{{$lang['code'] == 'en' ? $allergy['name']:($translate[$lang['code']]['name']??'')}}"
                                                    {{$lang['status'] == true ? 'required':''}} maxlength="255"
                                                    @if($lang['status'] == true) oninvalid="document.getElementById('{{$lang['code']}}-link').click()" @endif>
                                            </div>
                                        <input type="hidden" name="lang[]" value="{{$lang['code']}}">
                                    @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group lang_form" id="{{$default_lang}}-form">
                                                    <label class="input-label" for="exampleFormControlInput1">{{translate('name')}} ({{strtoupper($default_lang)}})</label>
                                                    <input type="text" name="name[]" value="{{$allergy['name']}}" class="form-control" placeholder="{{translate('New Allergy')}}" required maxlength="255">
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$default_lang}}">
                                                @endif
                                                <input name="position" value="0" style="display: none">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="d-flex justify-content-start gap-3">
                                                    <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{translate('update')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>

@endpush
