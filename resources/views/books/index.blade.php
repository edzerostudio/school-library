<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 table-responsive">
                    <button type="button" class="btn btn-outline-primary mb-3" onclick="showModal(`add`, `{{ route('books.store') }}`);">
                        <i class="bi bi-person-plus-fill"></i> Add Book
                    </button>
                    <table id="books-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Author</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->description }}</td>
                                <td>{{ $book->author->name }}</td>
                                <td>{{ $book->created_at }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li><button class="dropdown-item" onclick="showModal(`show`, `{{ route('books.show', $book) }}`);">Show</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`edit`, `{{ route('books.update', $book) }}`, {{ $book }});">Edit</button></li>
                                            <li><button class="dropdown-item" onclick="showModal(`delete`, `{{ route('books.destroy', $book) }}`);">Delete</button></li>
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
    <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" id="formBookModal">
                @csrf
                    <div class="modal-header" id="bookModalHeader">
                        <h5 class="modal-title" id="bookModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="bookModalBody">
                        <div class="mb-3 form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>

                            @if ($errors->has('title'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3 form-group{{ $errors->has('description') ? ' has-danger' : '' }}"">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" rows="3" id="description" name="description" required>
                            </textarea>
                            
                            @if ($errors->has('description'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="mb-3 form-group{{ $errors->has('author_id') ? ' has-danger' : '' }}"">
                            <label for="author_id" class="form-label">Author</label>
                            <select class="form-select" aria-label="default" id="author_id" name="author_id" >
                                <option value="">{{ __('Select Author') }}</option>
                                @foreach($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" id="bookModalFooter">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#books-table').DataTable();
            var htmldata;
        } );

        function showModal(type, target, data){
            var bookModal = new bootstrap.Modal($('#bookModal'));
            resetState();

            if(type === 'show'){
                $('#bookModalLabel').html('Book Data');
                
                $.ajax({
                    type: 'GET',
                    url: target,
                    dataType: "html",
                    success: function(data) {
                        data = JSON.parse(data);

                        $("#title").val(data.title).prop( "disabled", true );
                        $("#description").val(data.description).prop( "disabled", true );
                        $("#author_id").val(data.author_id).prop( "disabled", true );
                        $("#save").hide();
                    }
                });
            } else if(type === 'add') {
                $('#bookModalLabel').html('Add Book');
                $('#formBookModal').attr('action', target);
                $('#save').attr('type', 'submit');
            } else if(type === 'edit') {
                $('#bookModalLabel').html('Edit Book');
                $('#bookModalBody').append(`{{ method_field('PUT') }}`);
                $('#formBookModal').attr('action', target);

                $("#title").val(data.title);
                $("#description").val(data.description);
                $("#author_id").val(data.author_id);

                $('#save').attr('type', 'submit');
            } else if(type === 'delete') {
                $('#bookModalLabel').html('Delete Book');
                $('#formBookModal').contents().unwrap();
                $('#bookModalBody').html('Are you sure want to delete?');
                htmldata = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{ isset($book)??route('books.destroy', $book) }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>`;
                $('#bookModalFooter').html(htmldata);
            }

            bookModal.show();
        }

        function resetState(){
            htmldata = `
            <div class="mb-3 form-group{{ $errors->has('title') ? ' has-danger' : '' }}">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>

                @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
            <div class="mb-3 form-group{{ $errors->has('description') ? ' has-danger' : '' }}"">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" rows="3" id="description" name="description" required>
                </textarea>
                
                @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
            <div class="mb-3 form-group{{ $errors->has('author_id') ? ' has-danger' : '' }}"">
                <label for="author_id" class="form-label">Author</label>
                <select class="form-select" aria-label="default" id="author_id" name="author_id" >
                    <option value="">{{ __('Select Author') }}</option>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                    @endforeach
                </select>
            </div>`;
            $('#bookModalBody').html(htmldata);
            $("#title").val('').prop( "disabled", false );
            $("#description").val('').prop( "disabled", false );
            $("#author_id").val('').prop( "disabled", false );
            $("#save").show();
        }
    </script>
</x-app-layout>
