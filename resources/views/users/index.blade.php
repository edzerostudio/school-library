<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 table-responsive">
                    <button type="button" class="btn btn-outline-primary mb-3" onclick="showModal(`add`, `{{ route('users.store') }}`);">
                        <i class="bi bi-person-plus-fill"></i> Add User
                    </button>
                    <table id="users-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><button class="dropdown-item" onclick="showModal(`show`, `{{ route('users.show', $user) }}`);">Show</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`edit`, `{{ route('users.update', $user) }}`, {{ $user }});">Edit</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`delete`, `{{ route('users.destroy', $user) }}`);">Delete</button></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" id="formUserModal">
                @csrf
                    <div class="modal-header" id="userModalHeader">
                        <h5 class="modal-title" id="userModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="userModalBody">
                        <div class="mb-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3 form-group{{ $errors->has('username') ? ' has-danger' : '' }}"">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            
                            @if ($errors->has('username'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3 form-group{{ $errors->has('email') ? ' has-danger' : '' }}"">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3 form-group{{ $errors->has('role') ? ' has-danger' : '' }}"">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" aria-label="default" id="role" name="role" >
                                <option value="">{{ __('Select Role') }}</option>
                                <option value="Admin">Admin</option>
                                <option value="Non-Admin">Non-Admin</option>
                            </select>
                            
                            @if ($errors->has('role'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer" id="userModalFooter">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#users-table').DataTable();
            var htmldata;
        } );

        function showModal(type, target, data){
            var userModal = new bootstrap.Modal($('#userModal'));
            resetState();

            if(type === 'show'){
                $('#userModalLabel').html('User Data');
                
                $.ajax({
                    type: 'GET',
                    url: target,
                    dataType: "html",
                    success: function(data) {
                        data = JSON.parse(data);

                        $("#name").val(data.name).prop( "disabled", true );
                        $("#username").val(data.username).prop( "disabled", true );
                        $("#email").val(data.email).prop( "disabled", true );
                        $("#role").val(data.role).prop( "disabled", true );
                        $("#save").hide();
                    }
                });
            } else if(type === 'add') {
                $('#userModalLabel').html('Add User');
                htmldata = `
                <div class="mb-3 form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
                            
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-3 form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>`;
                $('#userModalBody').append(htmldata);
                $('#formUserModal').attr('action', target);
                $('#save').attr('type', 'submit');
            } else if(type === 'edit') {
                $('#userModalLabel').html('Edit User');
                $('#userModalBody').append(`{{ method_field('PUT') }}`);
                $('#formUserModal').attr('action', target);

                $("#name").val(data.name);
                $("#username").val(data.username);
                $("#email").val(data.email);
                $("#role").val(data.role);

                $('#save').attr('type', 'submit');
            } else if(type === 'delete') {
                $('#userModalLabel').html('Delete User');
                $('#formUserModal').contents().unwrap();
                $('#userModalBody').html('Are you sure want to delete?');
                htmldata = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{ route('users.destroy', $user) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>`;
                $('#userModalFooter').html(htmldata);
            }

            userModal.show();
        }

        function resetState(){
            htmldata = `
                <div class="mb-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>

                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-3 form-group{{ $errors->has('username') ? ' has-danger' : '' }}"">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    
                    @if ($errors->has('username'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-3 form-group{{ $errors->has('email') ? ' has-danger' : '' }}"">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="mb-3 form-group{{ $errors->has('role') ? ' has-danger' : '' }}"">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" aria-label="default" id="role" name="role" >
                        <option value="">{{ __('Select Role') }}</option>
                        <option value="Admin">Admin</option>
                        <option value="Non-Admin">Non-Admin</option>
                    </select>
                    
                    @if ($errors->has('role'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('role') }}</strong>
                        </span>
                    @endif
                </div>`;
            $('#userModalBody').html(htmldata);
            $("#name").val('').prop( "disabled", false );
            $("#username").val('').prop( "disabled", false );
            $("#email").val('').prop( "disabled", false );
            $("#role").val('').prop( "disabled", false );
            $("#save").show();
        }
    </script>
</x-app-layout>
