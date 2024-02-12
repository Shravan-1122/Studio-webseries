<!DOCTYPE html>
<html>
<head>
    <title>Edit Theme</title>
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
<center> <h1>Edit Theme Page</h1></center>
    <div class="container mt-5">
        <form method="post" id="edit_theme_form" action="{{ route('theme.update', $theme->id) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ $theme->title }}" class="form-control">
                <div class="error" id="title_error"></div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" value="{{ $theme->description }}" class="form-control">
                <div class="error" id="description_error"></div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script>
        $(document).ready(function() {
            $("#edit_theme_form").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    title: {
                        required: "Title is required.",
                    },
                    description: {
                        required: "Description is required.",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "title")
                        error.appendTo("#title_error");
                    else if (element.attr("name") == "description")
                        error.appendTo("#description_error");
                    else
                        error.insertAfter(element);
                },
            });
        });
    </script>
</body>
</html>