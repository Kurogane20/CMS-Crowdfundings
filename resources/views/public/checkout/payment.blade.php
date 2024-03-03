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
                            {{-- <p class="contributing-to-name"><strong> @lang('app.you_are_contributing_to') {{$campaign->user->name}}</strong></p> --}}
                            <h3>{{$campaign->title}}</h3>
                        </div>

                        <hr />

                        <?php
                        $currency = get_option('currency_sign');
                        ?>

                        <div class="row">
                           


                            @if(get_option('enable_bank_transfer') == 1)
                                <div class="col-md-4">
                                    <button class="btn btn-primary" style="background-color: green; border:none" id="bankTransferBtn"><i class="fa fa-bank"></i> Bayar dengan transfer bank</button>
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


                                        <div class="alert alert-info" style="background-color: rgb(1, 158, 106); color:white; text-align:center">
                                            <h4 style="font-weight: bold"> Detail Pembayaran #{{$campaign->title}} </h4>
                                        </div>

                                        <div class="mt-3 mb-3 p-3 bg-light rounded">
                                            <h4 style="text-align: center; margin-bottom:20px">Transfer sesuai nominal dibawah ini</h4>
                                            <h4 style="text-align: center; font-weight: bold; margin-bottom:20px ">Rp {{ $amount }}</h4>
                                            <h5 style="text-align: center"> Ke rekening Bank Muamalat</h5>
                                            <h5 style="text-align: center; font-weight:bold">3320800800</h5>
                                            <h5 style="text-align: center; font-weight:bold">Atas Nama Yayasan Risma Peduli Nusantara</h5>

                                            
                                        </div>

                                        <div id="bankTransferStatus"></div>

                                        <form action="{{route('bank_transfer_submit')}}" id="bankTransferForm" class="payment-form" method="post" enctype="multipart/form-data" > @csrf


                                            

                                            <div class="row mb-3 {{ $errors->has('account_number')? 'is-invalid':'' }}">
                                                <label for="account_number" class="col-sm-4 col-form-label">Nomor Rekening<span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="account_number" value="{{ old('account_number') }}" name="account_number" placeholder="Nomor Rekening">
                                                    {!! $errors->has('account_number')? '<p class="help-block">'.$errors->first('account_number').'</p>':'' !!}
                                                </div>
                                            </div>

                                            <div class="row mb-3 {{ $errors->has('branch_name')? 'is-invalid':'' }}">
                                                <label for="branch_name" class="col-sm-4 col-form-label">Nama Bank<span class="field-required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="branch_name" value="{{ old('branch_name') }}" name="branch_name" placeholder="Nama Bank">
                                                    {!! $errors->has('branch_name')? '<p class="help-block">'.$errors->first('branch_name').'</p>':'' !!}
                                                </div>
                                            </div>

                                            

                                            <div class="row mb-3">
                                                <div class="offset-sm-4 col-sm-8">
                                                    <button type="submit" class="btn btn-primary" style="background-color: green; border: none">Submit Pembayaran</button>
                                                </div>
                                            </div>

                                        </form>


                                    </div>
                                </div>

                            </div>
                        @endif
                        
                        

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