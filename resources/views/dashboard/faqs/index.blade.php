@extends('layouts.dashboard.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('title_link') 
    <a href="{{route('edit_campaign', $campaign_id)}}" class="btn btn-info pull-right"><i class="fa fa-arrow-circle-o-left"></i> @lang('app.back_to_campaign')</a> 
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-8 offset-sm-2 col-xs-12">

            <form action="" class="form-horizontal" method="post" enctype="multipart/form-data" >                                @csrf


            <div class="row mb-3 {{ $errors->has('title')? 'is-invalid':'' }}">
                <label for="title" class="col-sm-4 col-form-label">@lang('app.title')</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="title" value="{{ old('title') }}" name="title" placeholder="@lang('app.title')">
                    {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                </div>
            </div>

            <div class="row mb-3 {{ $errors->has('description')? 'is-invalid':'' }}">
                <label for="description" class="col-sm-4 col-form-label">@lang('app.description')</label>
                <div class="col-sm-8">
                    <textarea class="form-control description" name="description">{{old('description')}}</textarea>
                    {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}
                </div>
            </div>


            <div class="row mb-3">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">@lang('app.save_faq')</button>
                </div>
            </div>
            </form>

        </div>

    </div>

    @if($faqs->count())
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered categories-lists">
                    <tr>
                        <th>@lang('app.title') </th>
                        <th>@lang('app.description') </th>
                        <th>@lang('app.action') </th>
                    </tr>
                    @foreach($faqs as $faq)
                        <tr>
                            <td> {{ $faq->title }}  </td>
                            <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"> {{ $faq->description }}  </td>
                            <td width="100">
                                <a href="{{ route('faq_update', [$faq->campaign_id,$faq->id]) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> </a>
                                <a href="javascript:;" class="btn btn-danger btn-xs" data-id="{{ $faq->id }}"><i class="fa fa-trash"></i> </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replaceClass = 'description';
            $('.btn-danger').on('click', function (e) {
                if (!confirm("@lang('app.are_you_sure_undone')")) {
                    e.preventDefault();
                    return false;
                }

                var selector = $(this);
                var data_id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_faq') }}',
                    data: {data_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                        }
                    }
                });
            });
        });
    </script>
@endsection