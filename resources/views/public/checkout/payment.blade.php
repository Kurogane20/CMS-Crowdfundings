@extends('layouts.public.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <section class="campaign-details-wrap">
        @include('public.campaigns.partials.header')
        <div class="container">

            <div class="row">
                <div class="col-md-8 offset-md-2">

                    <div class="checkout-wrap">

                        <div class="contributing-to">
                            <p class="contributing-to-name"><strong> @lang('app.you_are_contributing_to') {{$campaign->user->name}}</strong></p>
                            <h3>{{$campaign->title}}</h3>
                        </div>

                        <hr />

                        <?php
                        $currency = get_option('currency_sign');
                        ?>

                        <div class="row">
                           


                            @if(get_option('enable_bank_transfer') == 1)
                                <div class="col-md-4">
                                    <button class="btn btn-primary" id="bankTransferBtn"><i class="fa fa-bank"></i> @lang('app.pay_with_bank_bank_transfer')</button>
                                </div>
                            @endif

                            {{-- @if(get_option('enable_bank_transfer') == 1)
                                <div class="col-md-4">
                                    <button class="btn btn-primary" id="mandiriTransferBtn"><i class="fa fa-bank"></i> Pay With Mandiri</button>
                                </div>
                            @endif --}}
                        </div>

                        @if(get_option('enable_bank_transfer') == 1)
                            <div class="bankPaymetWrap" style="display: none;">

                                <div class="row">
                                    <div class="col-md-8 offset-md-2">


                                        <div class="alert alert-info">
                                            <h4> @lang('app.campaign_unique_info') #{{$campaign->id}} </h4>
                                        </div>

                                        <div class="mt-3 mb-3 p-3 bg-light rounded">
                                            <h4>@lang('app.bank_payment_instruction')</h4>

                                            <table class="table">
                                                {{-- <tr>
                                                    <th>@lang('app.bank_swift_code')</th>
                                                    <td>{{get_option('bank_swift_code') }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <th>@lang('app.account_number')</th>
                                                    <td>{{get_option('account_number') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.branch_name')</th>
                                                    <td>{{get_option('branch_name') }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <th>@lang('app.branch_address')</th>
                                                    <td>{{get_option('branch_address') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.account_name')</th>
                                                    <td>{{get_option('account_name') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.iban')</th>
                                                    <td>{{get_option('iban') }}</td>
                                                </tr> --}}
                                            </table>
                                        </div>

                                        <div id="bankTransferStatus"></div>

                                        <form action="{{route('bank_transfer_submit')}}" id="bankTransferForm" class="payment-form" method="post" enctype="multipart/form-data" > @csrf


                                            {{-- <div class="row mb-3 {{ $errors->has('bank_swift_code')? 'is-invalid':'' }}">
                                                <label for="bank_swift_code" class="col-sm-4 col-form-label">
                                                    @lang('app.bank_swift_code') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="bank_swift_code" value="{{ old('bank_swift_code') }}" name="bank_swift_code" placeholder="@lang('app.bank_swift_code')">
                                                    {!! $errors->has('bank_swift_code')? '<p class="help-block">'.$errors->first('bank_swift_code').'</p>':'' !!}
                                                </div>
                                            </div> --}}

                                            <div class="row mb-3 {{ $errors->has('account_number')? 'is-invalid':'' }}">
                                                <label for="account_number" class="col-sm-4 col-form-label">@lang('app.account_number') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="account_number" value="{{ old('account_number') }}" name="account_number" placeholder="@lang('app.account_number')">
                                                    {!! $errors->has('account_number')? '<p class="help-block">'.$errors->first('account_number').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('branch_name')? 'is-invalid':'' }}">
                                                <label for="branch_name" class="col-sm-4 col-form-label">@lang('app.branch_name') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="branch_name" value="{{ old('branch_name') }}" name="branch_name" placeholder="@lang('app.branch_name')">
                                                    {!! $errors->has('branch_name')? '<p class="help-block">'.$errors->first('branch_name').'</p>':'' !!}
                                                </div>
                                            </div>

                                            {{-- <div class="row mb-3 {{ $errors->has('branch_address')? 'is-invalid':'' }}">
                                                <label for="branch_address" class="col-sm-4 col-form-label">@lang('app.branch_address') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="branch_address" value="{{ old('branch_address') }}" name="branch_address" placeholder="@lang('app.branch_address')">
                                                    {!! $errors->has('branch_address')? '<p class="help-block">'.$errors->first('branch_address').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('account_name')? 'is-invalid':'' }}">
                                                <label for="account_name" class="col-sm-4 col-form-label">@lang('app.account_name') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="account_name" value="{{ old('account_name') }}" name="account_name" placeholder="@lang('app.account_name')">
                                                    {!! $errors->has('account_name')? '<p class="help-block">'.$errors->first('account_name').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('iban')? 'is-invalid':'' }}">
                                                <label for="iban" class="col-sm-4 col-form-label">@lang('app.iban')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="iban" value="{{ old('iban') }}" name="iban" placeholder="@lang('app.iban')">
                                                    {!! $errors->has('iban')? '<p class="help-block">'.$errors->first('iban').'</p>':'' !!}
                                                </div>
                                            </div> --}}

                                            <div class="row mb-3">
                                                <div class="offset-sm-4 col-sm-8">
                                                    <button type="submit" class="btn btn-primary">@lang('app.pay')</button>
                                                </div>
                                            </div>

                                        </form>


                                    </div>
                                </div>

                            </div>
                        @endif
                        {{-- @if(get_option('enable_bank_transfer') == 1)
                            <div class="mandiriPaymetWrap" style="display: none;">

                                <div class="row">
                                    <div class="col-md-8 offset-md-2">


                                        <div class="alert alert-info">
                                            <h4> @lang('app.campaign_unique_info') #{{$campaign->id}} </h4>
                                        </div>

                                        <div class="mt-3 mb-3 p-3 bg-light rounded">
                                            <h4>@lang('app.bank_payment_instruction')</h4>

                                            <table class="table">
                                                <tr>
                                                    <th>@lang('app.bank_swift_code')</th>
                                                    <td>{{get_option('bank_swift_code') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.account_number')</th>
                                                    <td>{{get_option('account_number') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.branch_name')</th>
                                                    <td>{{get_option('branch_name') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.branch_address')</th>
                                                    <td>{{get_option('branch_address') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.account_name')</th>
                                                    <td>{{get_option('account_name') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>@lang('app.iban')</th>
                                                    <td>{{get_option('iban') }}</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div id="bankTransferStatus"></div>

                                        <form action="{{route('bank_transfer_submit')}}" id="bankTransferForm" class="payment-form" method="post" enctype="multipart/form-data" > @csrf


                                            <div class="row mb-3 {{ $errors->has('bank_swift_code')? 'is-invalid':'' }}">
                                                <label for="bank_swift_code" class="col-sm-4 col-form-label">
                                                    @lang('app.bank_swift_code') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="bank_swift_code" value="{{ old('bank_swift_code') }}" name="bank_swift_code" placeholder="@lang('app.bank_swift_code')">
                                                    {!! $errors->has('bank_swift_code')? '<p class="help-block">'.$errors->first('bank_swift_code').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('account_number')? 'is-invalid':'' }}">
                                                <label for="account_number" class="col-sm-4 col-form-label">@lang('app.account_number') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="account_number" value="{{ old('account_number') }}" name="account_number" placeholder="@lang('app.account_number')">
                                                    {!! $errors->has('account_number')? '<p class="help-block">'.$errors->first('account_number').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('branch_name')? 'is-invalid':'' }}">
                                                <label for="branch_name" class="col-sm-4 col-form-label">@lang('app.branch_name') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="branch_name" value="{{ old('branch_name') }}" name="branch_name" placeholder="@lang('app.branch_name')">
                                                    {!! $errors->has('branch_name')? '<p class="help-block">'.$errors->first('branch_name').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('branch_address')? 'is-invalid':'' }}">
                                                <label for="branch_address" class="col-sm-4 col-form-label">@lang('app.branch_address') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="branch_address" value="{{ old('branch_address') }}" name="branch_address" placeholder="@lang('app.branch_address')">
                                                    {!! $errors->has('branch_address')? '<p class="help-block">'.$errors->first('branch_address').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('account_name')? 'is-invalid':'' }}">
                                                <label for="account_name" class="col-sm-4 col-form-label">@lang('app.account_name') <span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="account_name" value="{{ old('account_name') }}" name="account_name" placeholder="@lang('app.account_name')">
                                                    {!! $errors->has('account_name')? '<p class="help-block">'.$errors->first('account_name').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('iban')? 'is-invalid':'' }}">
                                                <label for="iban" class="col-sm-4 col-form-label">@lang('app.iban')</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="iban" value="{{ old('iban') }}" name="iban" placeholder="@lang('app.iban')">
                                                    {!! $errors->has('iban')? '<p class="help-block">'.$errors->first('iban').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="offset-sm-4 col-sm-8">
                                                    <button type="submit" class="btn btn-primary">@lang('app.pay')</button>
                                                </div>
                                            </div>

                                        </form>


                                    </div>
                                </div>

                            </div>
                        @endif --}}
                        

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@section('page-js')

    <script>
        $(function() {
           
            @if(get_option('enable_bank_transfer') == 1)

            $('#bankTransferBtn').click(function(){
                $('.bankPaymetWrap').slideToggle();
                $('.mandiriPaymetWrap').slideUp();
            });

            $('#mandiriTransferBtn').click(function(){
                $('.mandiriPaymetWrap').slideToggle();
                $('.bankPaymetWrap').slideUp();
            });

            $('#bankTransferForm').submit(function(e){
                e.preventDefault();

                var form_input = $(this).serialize()+'&_token={{csrf_token()}}';

                $.ajax({
                    url : '{{route('bank_transfer_submit')}}',
                    type: "POST",
                    data: form_input,
                    success : function (data) {
                        if (data.success == 1){
                            $('.checkout-wrap').html(data.response);
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    },
                    error   : function ( jqXhr, json, errorThrown ) {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml= '';
                        $.each( errors, function( key, value ) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error( errorsHtml , "Error " + jqXhr.status +': '+ errorThrown);
                    }
                });

            });
            @endif

        });
    </script>

@endsection