@extends('layouts.dashboard.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('content')

    <div class="row">
        
        <div class="col-xs-12">
            <div>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                    <i class="fa fa-plus"></i> Tambah Pengguna
                </button> 
            </div>

            @if($users->count() > 0)
                <p>{{number_format($users_count)}} @lang('app.total_users_found')</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>@lang('app.name')</td>
                            <td>@lang('app.email')</td>
                            <td>Role</td>
                            <td>@lang('app.actions')</td>
                        </tr>

                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <img src="{{ $user->get_gravatar(30) }}" class="img-thumbnail img-circle" width="30" />
                                    {{ $user->name }}
                                </td>
                                <td>{{$user->email}}</td>
                                <td>
                                    {{$user->user_type}}
                                </td>

                                <td>
                                    <a href="{{route('users_view', $user->id)}}" class="btn btn-default btn-sm"><i class="fa fa-eye"></i> </a>
                                    @if($user->active_status == 0)
                                        <a href="{{route('user_status', [$user->id, 'approve'])}}" class="btn btn-default btn-sm" data-toggle="tooltip" title="@lang('app.approve')"><i class="fa fa-ban"></i> </a>

                                        <a href="{{route('user_status', [$user->id, 'block'])}}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="@lang('app.block')"><i class="fa fa-ban"></i> </a>

                                    @elseif($user->active_status == '1')
                                        <a href="{{route('user_status', [$user->id, 'block'])}}" class="btn btn-danger btn-sm" data-toggle="tooltip" title="@lang('app.block')"><i class="fa fa-ban"></i> </a>

                                    @elseif($user->active_status == 2)
                                        <a href="{{route('user_status', [$user->id, 'approve'])}}" class="btn btn-success btn-sm" data-toggle="tooltip" title="@lang('app.approve')"><i class="fa fa-check-circle-o"></i> </a>
                                    @endif

                                    <a href="{{route('users_edit', $user->id)}}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
                

                {!! $users->links() !!}

            @else
                <h3>@lang('app.there_is_no_user')</h3>
            @endif

        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add User Form -->
                    <form action="{{ route('create_new_user') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="active_status">Active Status</label>
                            <select class="form-control" id="active_status" name="active_status" style="margin-bottom: 10px;">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_type">Role</label>
                            <select class="form-control" id="user_type" name="user_type" style="margin-bottom: 10px;">
                                <option value="admin">admin</option>
                                <option value="user">user</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-js')

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('#addUserModal').on('show.bs.modal', function (e) {
            // Clear form fields when modal is shown
            $('#name').val('');
            $('#email').val('');
            $('#active_status').val('1');
        });
    });
</script>
