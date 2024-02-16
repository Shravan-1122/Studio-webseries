<!DOCTYPE html>
<html>
<head>
    <title>Laravel Edit Web Series</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Include Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
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

        .select2-results__option {
            padding: 8px 12px;
        }
    </style>
</head>
<body>
<center> <h1>Edit Season</h1></center>
    <div class="container mt-5">
        <form method="post" id="edit_form" action="{{ route('season.update', $Season->id) }}">
            @csrf
            @method('PUT')
           
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $Season->season_title }}">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" class="form-control" value="{{ $Season->description }}">
            </div>
            <div class="form-group">
    <label>Artists</label>
    <select name="artist_ids[]" class="form-control" multiple id="artistSelect">
        @foreach ($artists as $id => $name)
            <option value="{{ $id }}" {{ in_array($id, $selectedArtistIds) ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
</div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Update web series</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
          
            $('#artistSelect').select2({
                templateResult: formatArtistOption,
                templateSelection: formatArtistSelection
            });

        
            function formatArtistOption(artist) {
                if (!artist.id) { return artist.text; }
                var $artist = $('<span>' + artist.text + '</span>');
                var $checkbox = $('<input type="checkbox" class="select2-results__checkbox" />');
                $checkbox.prop('checked', artist.selected);
                $checkbox.on('click', function() {
                    var isChecked = $(this).prop('checked');
                    $('#artistSelect').select2('trigger', isChecked ? 'select' : 'unselect', {
                        data: artist
                    });
                });
                $artist.prepend($checkbox);
                return $artist;
            }

           
            function formatArtistSelection(artist) {
                return artist.text;
            }

          
            $("#edit_form").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                    theme_id: {
                        required: true,
                    },
                    'artist_ids[]': {
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
                    theme_id: {
                        required: "Theme selection is required.",
                    },
                    'artist_ids[]': {
                        required: "Artist selection is required.",
                    },
                },
            });
        });
    </script>
</body>
</html>