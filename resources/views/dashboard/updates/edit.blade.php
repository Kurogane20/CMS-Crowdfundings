@extends('layouts.dashboard.app')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('content')
    <div class="row">
        <div class="col-sm-8 offset-sm-2 col-xs-12">

            <form action="{{ route('update_update', [$campaign->id, $update->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title">@lang('app.title')</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $update->title }}" required>
                </div>
                
                <div class="form-group">
                    <label for="description">@lang('app.description')</label>
                    <textarea name="description" id="description" rows="10" class="form-control" required>{{ $update->description }}</textarea>
                </div>

                <div class="form-group">
                    <label>@lang('app.current_images')</label>
                    <div class="row">
                        @foreach($update->getImageUrls() as $imageUrl)
                            <div class="col-md-3">
                                <img src="{{ $imageUrl }}" height="100" class="img-responsive" style="margin: 5px;" />
                                <label>
                                    <input type="checkbox" name="delete_images[]" value="{{ basename($imageUrl) }}"> @lang('app.delete')
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="images">@lang('app.new_images')</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                </div>
                
                <button type="submit" class="btn btn-primary">@lang('app.update')</button>
            </form>


        </div>
    </div>
@endsection