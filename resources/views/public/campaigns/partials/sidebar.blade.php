<div class="single-donate-wrap">

    <h3 class="campaign-single-sub-title">{{$campaign->short_description}}</h3>

    @if($campaign->user)
        <div class="single-author-box">
            <img src="{{$campaign->user->get_gravatar()}}">
            <p><strong>{{$campaign->user->name}}</strong> <br />   {{$campaign->address}} </p>
        </div>
    @endif

    @if($campaign->get_category)
        <div class="campaign-tag-list">
            <a href="#"><i class="glyphicon glyphicon-tag"></i> {{$campaign->get_category->category_name}} </a>
        </div>
    @endif

    <div class="campaign-progress-info">
        <h4>{!! get_amount($campaign->total_raised()) !!} <small>dari target {!! get_amount($campaign->goal) !!} @lang('app.goal')</small></h4>
	
        <div class="progress">
            @php
                $percent_raised = $campaign->percent_raised();
            @endphp
            <div class="progress-bar" role="progressbar" aria-valuenow="{{$percent_raised}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$percent_raised <= 100 ? $percent_raised : 100}}%;">
                {{$percent_raised}}%
            </div>
        </div>

        <ul>
            <li><strong>{{$campaign->days_left()}}</strong> hari lagi</li>
            <li><strong>{{$campaign->total_payments}}</strong> Donatur</li>
        </ul>
    </div>


    

    <hr />


    <div class="input-group">
        <input type="text" id="campaign_url_input" class="form-control" value="{{route('campaign_single', [$campaign->id, $campaign->slug])}}">
        <div class="input-group-btn">
            <button class="btn btn-primary" id="campaign_url_copy_btn">@lang('app.copy_link')</button>
        </div>
    </div>

    <hr />

    <div class="socialShareWrap">
        <ul>
            <li><a href="#" class="share s_facebook"><i class="fa fa-facebook-square"></i> </a> </li>
            <li><a href="#" class="share s_twitter"><i class="fa fa-twitter-square"></i> </a> </li>
            <li><a href="#" class="share s_plus"><i class="fa fa-google-plus-square"></i> </a> </li>
            <li><a href="#" class="share s_linkedin"><i class="fa fa-linkedin-square"></i> </a> </li>
            <li><a href="#" class="share s_vk"><i class="fa fa-vk"></i> </a> </li>
            <li><a href="#" class="share s_pinterest"><i class="fa fa-pinterest-square"></i> </a> </li>
            <li><a href="#" class="share s_tumblr"><i class="fa fa-tumblr-square"></i> </a> </li>
            <li><a href="#" class="share s_delicious"><i class="fa fa-delicious"></i> </a> </li>
        </ul>
    </div>

    @php
        $is_ended = $campaign->is_ended();
    @endphp

    <div class="donate_form">

        <h2>Donasi</h2>

        @if( ! $is_ended)

           <form action="{{route('add_to_cart')}}" class="form-horizontal" method="post">
                @csrf
                <input type="hidden" name="campaign_id" value="{{$campaign->id}}" />
               <div class="donate_amount_field">
                    <div class="donate_currency">{!! get_currency_symbol(get_option('currency_sign')) !!}</div>
                    <input type="text" id="donation_amount_display" step="1" min="1" class="form-control" style="text-indent: 10px;" placeholder="@lang('app.enter_amount')" required />
                    <input type="hidden" id="donation_amount" name="amount" />
                </div>               

                <div class="amount-buttons">
                    <button type="button" class="btn btn-amount" data-amount="10000" >Rp 10,000</button>
                    <button type="button" class="btn btn-amount" data-amount="50000">Rp 50,000</button>
                    <button type="button" class="btn btn-amount" data-amount="100000">Rp 100,000</button>
                    <button type="button" class="btn btn-amount" data-amount="200000">Rp 200,000</button>
                    <!-- Tambahkan tombol lain sesuai kebutuhan -->
                </div> 
                
                <div class="donate-form-button">
                    <button type="submit" class="btn btn-filled">Donasi Sekarang</button>
                </div>
            </form>
   
        @else
            <div class="alert alert-warning">
                <h5>@lang('app.campaign_has_been_ended')</h5>
            </div>
        @endif

    </div>


    @if($campaign->rewards->count() > 0)
        <div class="rewards-wrap">

            <h2>@lang('app.or_choose_a_reward')</h2>

            @foreach($campaign->rewards as $reward)

                @php $claimed_count = $reward->payments->count(); @endphp

                <div class="reward-box @if($reward->quantity <= $claimed_count || $is_ended ) reward-disable @endif ">
                    @if($reward->quantity > $claimed_count )
                        <a href="{{route('add_to_cart', $reward->id)}}">
                            <span class="reward-amount">@lang('app.pledge') <strong>{!! get_amount($reward->amount) !!}</strong></span>
                            <span class="reward-text">{{$reward->description}}</span>
                            <span class="reward-claimed-count"> {{$claimed_count}} @lang('app.claimed_so_far') {{$reward->quantity}} </span>
                            <span class="reward-estimated-delivery"> @lang('app.estimated_delivery'): {{date('F Y', strtotime($reward->estimated_delivery))}}</span>
                            <span class="reward-button"> @lang('app.select_reward') </span>
                        </a>

                    @else
                        <span class="reward-amount">@lang('app.pledge') <strong>{!! get_amount($reward->amount) !!}</strong></span>
                        <span class="reward-text">{{$reward->description}}</span>
                        <span class="reward-claimed-count"> {{$claimed_count}} @lang('app.claimed_so_far') {{$reward->quantity}} </span>
                        <span class="reward-estimated-delivery"> @lang('app.estimated_delivery'): {{date('F Y', strtotime($reward->estimated_delivery))}}</span>
                        <span class="reward-button"> @lang('app.sold_out') </span>
                    @endif
                </div>
            @endforeach

        </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengatur nilai input jumlah donasi berdasarkan tombol yang diklik
        var amountButtons = document.querySelectorAll('.btn-amount');
        amountButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var amount = parseInt(this.getAttribute('data-amount'));
                document.getElementById('donation_amount_display').value = formatRupiah(amount);
                document.getElementById('donation_amount').value = amount;
            });
        });

        // Fungsi untuk mengubah angka menjadi format uang rupiah
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join(',').split('').reverse().join('');
            return ribuan;
        }

        // Mengubah input menjadi format uang rupiah saat memasukkan manual
        document.getElementById('donation_amount_display').addEventListener('input', function (e) {
            var amount = parseInt(this.value.replace(/[^\d]/g, ''));
            this.value = formatRupiah(amount);
            document.getElementById('donation_amount').value = amount;
        });
    });
</script>

<style>
    .amount-buttons .btn {
        border: 1px solid #ccc; /* Atur gaya border sesuai kebutuhan */
        border-radius: 5px; /* Atur border radius sesuai kebutuhan */
        margin-right: 5px; /* Atur jarak antara tombol */
        margin-bottom: 5px; /* Atur jarak antara tombol */
    }

    .amount-buttons .btn:hover {
        border-color: #333; /* Ganti warna border saat tombol dihover */
    }
</style>