<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Authors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 table-responsive">
                    <button type="button" class="btn btn-outline-primary mb-3" onclick="showModal(`add`, `{{ route('authors.store') }}`);">
                        <i class="bi bi-person-plus-fill"></i> Add Author
                    </button>
                    <table id="authors-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Join Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authors as $author)
                            <tr>
                                <td>{{ $author->name }}</td>
                                <td>{{ $author->created_at }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><button class="dropdown-item" onclick="showModal(`show`, `{{ route('authors.show', $author) }}`);">Show</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`edit`, `{{ route('authors.update', $author) }}`, {{ $author }});">Edit</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`delete`, `{{ route('authors.destroy', $author) }}`);">Delete</button></li>
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
    <div class="modal fade" id="authorModal" tabindex="-1" aria-labelledby="authorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" id="formAuthorModal">
                @csrf
                    <div class="modal-header" id="authorModalHeader">
                        <h5 class="modal-title" id="authorModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="authorModalBody">
                        <div class="mb-3 form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer" id="authorModalFooter">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#authors-table').DataTable();
            var htmldata;
        } );

        function showModal(type, target, data){
            var authorModal = new bootstrap.Modal($('#authorModal'));
            resetState();

            if(type === 'show'){
                $('#authorModalLabel').html('Author Data');
                
                $.ajax({
                    type: 'GET',
                    url: target,
                    dataType: "html",
                    success: function(data) {
                        data = JSON.parse(data);

                        $("#name").val(data.name).prop( "disabled", true );
                        $("#save").hide();
                    }
                });
            } else if(type === 'add') {
                $('#authorModalLabel').html('Add Author');
                $('#formAuthorModal').attr('action', target);
                $('#save').attr('type', 'submit');
            } else if(type === 'edit') {
                $('#authorModalLabel').html('Edit Author');
                $('#authorModalBody').append(`{{ method_field('PUT') }}`);
                $('#formAuthorModal').attr('action', target);

                $("#name").val(data.name);

                $('#save').attr('type', 'submit');
            } else if(type === 'delete') {
                $('#authorModalLabel').html('Delete Author');
                $('#formAuthorModal').contents().unwrap();
                $('#authorModalBody').html('Are you sure want to delete?');
                htmldata = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{ isset($author)??route('authors.destroy', $author) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>`;
                $('#authorModalFooter').html(htmldata);
            }

            authorModal.show();
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
                </div>`;
            $('#authorModalBody').html(htmldata);
            $("#name").val('').prop( "disabled", false );
            $("#save").show();
        }
    </script>
</x-app-layout>
