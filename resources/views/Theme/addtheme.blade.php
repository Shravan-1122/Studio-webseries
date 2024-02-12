<!DOCTYPE html>
<html>
<head>
    <title>Laravel Add User With Validation Demo</title>
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
<center> <h1>Add Theme Page</h1></center>
    <div class="container mt-5">
        <form method="post" id="add_create" name="add_create" action="{{ route('theme.addtheme') }}">
            @csrf
           
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" >
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" >
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Add Theme</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#add_create").validate({
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
                        required: "title is required.",
                    },
                    description: {
                        required: "description is required.",
                    },
                },
            });
        });
    </script>
</body>
</html>