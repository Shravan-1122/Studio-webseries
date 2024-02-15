<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
   
</head>

<body>
    <div class="container mt-5">
        <div class="menu">
        <div class="mt-3">
            <div class="table-responsive">
                <table class="table table-bordered" id="users-list">
                    <thead>
                        <tr class="table-warning">
                            <td>Status</td>
                            <td>id</td>
                            <td>Name</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch{{ $post->id }}"
                                        data-post-id="{{ $post->id }}" {{ $post->active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customSwitch{{ $post->id }}">
                                        {{ $post->active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </td>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
     $(document).ready(function () {

// Function to update the status of the switch based on postId
function getStatus(postId) {
    console.log("get ajax called");
    $.ajax({
        url: '/get-user-status/' + postId,
        method: 'GET',
        success: function (response) {
            var isChecked = response.status === 'active';
            $('#customSwitch' + postId).prop('checked', isChecked);
            var label = $('#customSwitch' + postId).next('.custom-control-label');
            label.text(isChecked ? 'Active' : 'Inactive');
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

// Loop through each custom switch and update its status
$('.custom-control-input').each(function () {
    var postId = $(this).data('post-id');
    getStatus(postId);
});

// Event listener for the change event of the custom switch
$('.custom-control-input').on('change', function () {
    var isChecked = $(this).prop('checked');
    var postId = $(this).data('post-id');
    var label = $(this).next('.custom-control-label');
    console.log("post ajax called");

    // Ajax request to update the user status
    $.ajax({
        url: '/update-user-status/' + postId,
        method: 'POST',
        data: {
            status: isChecked ? 'active' : 'inactive',
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response); // Print response to console
            // Update label text based on isChecked state
            label.text(isChecked ? 'Active' : 'Inactive');
            // Update label class based on isChecked state
            if (isChecked) {
                label.addClass('switch-on');
            } else {
                label.removeClass('switch-on');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
});        
});
    </script>
</body>

</html>