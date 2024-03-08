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
                            <div class="col-md-8 offset-md-2">


                                <div class="alert alert-info" style="background-color: rgb(1, 158, 106); color:white; text-align:center">
                                    <h4 style="font-weight: bold"> Detail Pembayaran #{{$campaign->title}} </h4>
                                </div>

                                <div class="mt-3 mb-3 p-3 bg-light rounded">
                                    <h4 style="text-align: center; margin-bottom:20px">Transfer sesuai nominal dibawah ini</h4>
                                    <h4 style="text-align: center; font-weight: bold; margin-bottom:20px ">{!! get_amount($amount) !!}</h4>
                                    <
                                    <h5 style="text-align: center"> Ke rekening Bank Muamalat Indonesia</h5>
                                    <h5 id="copyText" style="text-align: center; font-weight:bold; cursor: pointer" onclick="copyToClipboard('copyText')">3320800800</h5>
                                    <h5 style="text-align: center; font-weight:bold">Atas Nama Yayasan Risma Peduli Nusantara</h5>                                            
                                </div>

                                <div class="mt-3 mb-3 p-3 bg-light rounded">
                                    <h4 style="text-align: center; margin-bottom:20px">Detail Transaksi</h4>
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>@lang('app.amount')</th>
                                            <td>{!! get_amount($amount) !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Atas Nama</th>
                                            <td>{{$name}}</td>
                                        </tr>
                                         
                                        <tr>
                                            <th>Metode Pembayaran</th>
                                            <td>Bank Transfer</td>
                                        </tr>                                           
                                    </table>      
                                </div>

                                <div id="bankTransferStatus"></div>

                                <form action="{{route('bank_transfer_submit')}}" id="bankTransferForm" class="payment-form" method="post" enctype="multipart/form-data" > @csrf                                            

                                    {{-- <div class="row mb-3 {{ $errors->has('account_number')? 'is-invalid':'' }}">
                                        <label for="account_number" class="col-sm-4 col-form-label">Nomor Rekening<span class="field-required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="account_number" value="{{ old('account_number') }}" name="account_number" placeholder="Nomor Rekening">
                                            {!! $errors->has('account_number')? '<p class="help-block">'.$errors->first('account_number').'</p>':'' !!}
                                        </div>
                                    </div>

                                    <div class="row mb-3 {{ $errors->has('branch_name')? 'is-invalid':'' }}">
                                        <label for="branch_name" class="col-sm-4 col-form-label">Nama Bank<span class="field-required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="branch_name" value="{{ old('branch_name') }}" name="branch_name" placeholder="Nama Bank">
                                            {!! $errors->has('branch_name')? '<p class="help-block">'.$errors->first('branch_name').'</p>':'' !!}
                                        </div>
                                    </div> --}}

                                    <div class="row mb-3">
                                        <label for="bukti_pembayaran" class="col-sm-4 col-form-label">Bukti Pembayaran<span class="field-required">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*">
                                            {!! $errors->has('bukti_pembayaran') ? '<p class="help-block">'.$errors->first('bukti_pembayaran').'</p>' : '' !!}
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="offset-sm-4 col-sm-8">
                                            <button type="submit" class="btn btn-primary" style="background-color:rgb(2, 95, 2); border: none">Konfirmasi Pembayaran</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@section('page-js')

    <script>
        $(function() {
            $('#bankTransferForm').submit(function(e){
                e.preventDefault();

                var formData = new FormData(this); // Membuat objek FormData dari formulir

                // Menambahkan _token secara manual ke formData
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('bank_transfer_submit') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,  // Jangan memproses data secara default
                    contentType: false,  // Jangan mengatur tipe konten secara default
                    success: function(data) {
                        if (data.success == 1){
                            $('.checkout-wrap').html(data.response);
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    },
                    error: function(jqXhr, json, errorThrown) {
                        var errors = jqXhr.responseJSON;
                        var errorsHtml = '';
                        $.each(errors, function(key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        toastr.error(errorsHtml, "Error " + jqXhr.status + ': ' + errorThrown);
                    }
                });
            });
        });
        
        function copyToClipboard(elementId) {
            var text = document.getElementById(elementId).innerText;
            var tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Teks berhasil disalin: " + text);
        }
    </script>

@endsection