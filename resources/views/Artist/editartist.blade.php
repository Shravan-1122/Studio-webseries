<!DOCTYPE html>
<html>
<head>
    <title>Edit Artist</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 500px;
        }

        .error {
            display: block;
            padding-top: 5px;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <form method="post" id="edit_artist_form" action="{{ route('artist.update', $artist->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ $artist->name }}" class="form-control">
                <div class="error" id="name_error"></div>
            </div>
            <div class="form-group">
                <label>Age</label>
                <input type="text" name="age" value="{{ $artist->age }}" class="form-control">
                <div class="error" id="age_error"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {
            $("#edit_artist_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    age: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Name is required.",
                    },
                    age: {
                        required: "Age is required.",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "name")
                        error.appendTo("#name_error");
                    else if (element.attr("name") == "age")
                        error.appendTo("#age_error");
                    else
                        error.insertAfter(element);
                },
            });
        });
    </script>
</body>
</html>