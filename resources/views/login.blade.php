<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
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
    <center><h1>Login Page</h1></center>

    <div class="container mt-5">
        <div class="text-right mb-3">
            <a href="{{ route('register') }}" class="btn btn-primary">Click here to Register</a>
        </div>
       
        <form method="post" id="loginForm" action="{{ route('UserController.login') }}">
            @csrf
            
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control">
                @error('email')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                @error('password')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            @if ($errors->has('error'))
            <div class="alert alert-danger">{{ $errors->first('error') }}</div>
            @endif
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
        $(document).ready(function () {
            if ($("#loginForm").length > 0) {
                $("#loginForm").validate({
                    rules: {
                        email: {
                            required: true,
                            maxlength: 60,
                            email: true,
                        },
                        password: {
                            required: true,
                        },
                    },
                    messages: {
                        email: {
                            required: "Email is required.",
                            email: "Please enter a valid email address.",
                            maxlength: "The email should not exceed 60 characters.",
                        },
                        password: {
                            required: "Password is required.",
                        },
                    },
                });
            }
        });
    </script>
</body>

</html>