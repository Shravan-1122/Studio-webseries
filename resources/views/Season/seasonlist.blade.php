<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Season List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <style>
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
            margin: 0 auto;
        }

        .menu {
            position: relative;
            display: inline-block;
        }

        .menu-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
        }

        .menu-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            z-index: 1;
        }

        .menu-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .menu-content a:hover {
            background-color: #f1f1f1;
        }

        .menu:hover .menu-content {
            display: block;
        }

        .menu:hover .menu-btn {
            background-color: #3e8e41;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="menu">
            <button class="menu-btn"> Menu </button>
            <div class="menu-content">
                <a href="{{ route('web.list') }}"> Web Series List</a>
                <a href="{{ route('StudioController.artistlist') }}">Artists</a>
                <a href="{{ route('theme.list') }}">Themes</a>
            </div>
        </div>
        <div class="mt-3">
            <center>
                <h1>Season List</h1>
            </center>

            <div class="text-right mb-3">
                <a href="http://127.0.0.1:8000/addseason" class="btn btn-primary">+Add Season</a>
            </div>
            <table class="table table-bordered" id="users-list">
                <thead>
                    <tr class="table-warning">

                        <td>id</td>
                        <td>title</td>
                        <td>Description</td>

                        <td>Theme</td>
                        <td>artists</td>
                        <td>craetedby</td>
                        <td>updatedby</td>
                        <td>actions</td>
                    </tr>
                </thead>
                <tbody>
                @foreach ($webartists as $post1)
                    @foreach ($posts as $post)
                    <tr>

                        <td>{{ $post->id }}</td>
                        <td>{{ $post->season_title }}</td>
                        <td>{{ $post->description }}</td>
                        <!-- <td>
                           {{$post->webid}}
                        </td> -->
                        <td>{{ $themeName }}</td>

                        <td>
                        @foreach ($post1->artists as $artist)
                            {{ $artist->name }},
                            @endforeach
                            @foreach ($post->artists as $artist)
                            {{ $artist->name }},
                            @endforeach
                        </td>
                        <td>{{optional($post->createdByUser)->name }}</td>
                        <td>{{optional($post->updatedByUser)->name }}</td>

                        <td>
                            <a href="{{ route('season.edit', ['id' => $post->id]) }}" class="btn btn-primary">Edit</a>
                            <a href="#" class="btn btn-danger delete-btn" data-id="{{ $post->id }}">Delete</a>
                            <a href="{{ route('season.view', ['id' => $post->id]) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this Season?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" id="deleteArtistLink" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#users-list').DataTable({
                paging: false
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            $('.custom-control-input').on('change', function () {
                var isChecked = $(this).prop('checked');
                var postId = $(this).data('post-id');


                console.log('AJAX request triggered for post ID:', postId);


                $.ajax({
                    url: '/update-status/' + postId,
                    method: 'POST',
                    data: {
                        status: isChecked ? 'active' : 'inactive',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
    <style>
        .custom-control-input:checked+.custom-control-label::before {
            background-color: #4CAF50 !important;
        }

        .custom-control-label.switch-on {
            background-color: #4CAF50 !important;
            color: white;
        }
    </style>
    <script>
        $(document).ready(function () {

            $('.custom-control-input').on('change', function () {
                var isChecked = $(this).prop('checked');
                var label = $(this).siblings('.custom-control-label');
                label.toggleClass('switch-on', isChecked);
                label.text(isChecked ? 'Active' : 'Inactive');
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#users-list').DataTable();
        });


        $('.delete-btn').on('click', function () {
            var id = $(this).data('id');
            var deleteUrl = "{{ route('season.delete', ':id') }}".replace(':id', id);
            $('#deleteArtistLink').attr('href', deleteUrl);
            $('#deleteConfirmationModal').modal('show');
        });
    </script>
</body>

</html>