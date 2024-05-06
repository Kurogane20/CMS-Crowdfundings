@extends('layouts.public.app')

@section('content')
    <section class="home-campaign section-bg-white" style="padding-top: 70px">
        <div class="container">

            {{-- <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title">Kenapa Memilih Kami</h2>
                </div>
            </div> --}}

            {{-- <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="why-choose-us-box">
                        <div class="icon">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div class="title">
                            <h4>@lang('app.secure')</h4>
                        </div>
                        <div class="desc">
                            <p>@lang('app.secure_desc')</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="why-choose-us-box">
                        <div class="icon">
                            <i class="fa fa-history"></i>
                        </div>
                        <div class="title">
                            <h4>@lang('app.flexible')</h4>
                        </div>
                        <div class="desc">
                            <p>@lang('app.flexible_desc')</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="why-choose-us-box">
                        <div class="icon">
                            <i class="fa fa-thumbs-up"></i>
                        </div>
                        <div class="title">
                            <h4>@lang('app.easy')</h4>
                        </div>
                        <div class="desc">
                            <p>@lang('app.easy_desc')</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="why-choose-us-box">
                        <div class="icon">
                            <i class="fa fa-gift"></i>
                        </div>
                        <div class="title">
                            <h4>@lang('app.supports_reward')</h4>
                        </div>
                        <div class="desc">
                            <p>@lang('app.supports_reward_desc')</p>
                        </div>
                    </div>
                </div>

            </div> --}}

        </div>
    </section>

    <section class="auth-form" >
        <div class="container" style="padding-top: 70px">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card card-default">
                        <div class="card-header text-center">Hubungi Kami</div>
                        <div class="card-body">

                            @include('layouts.partials.alert')


                            <form action="{{ route('csr') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="nama_pic">Nama PIC</label>
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic" required>
                            </div>
                            <div class="form-group">
                                <label for="no_pic">No PIC</label>
                                <input type="no_pic" class="form-control" id="no_pic" name="no_pic" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_perusahaan">Nama Perusahaan</label>
                                <input type="nama_perusahaan" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
                            </div>                             
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="donasi">Estimasi Donasi</label>
                                <input type="donasi" class="form-control" id="donasi" name="donasi" required>
                            </div>                               
                            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if(get_option('enable_recaptcha_contact_form') == 1)
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endif