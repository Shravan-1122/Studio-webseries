<!DOCTYPE html>
<html>
<head>
    <title>View Episode, Season, and Web Series</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <h2>Episode</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $episode->episode_title }}</td>
                        <td>{{ $episode->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h2>Season</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $season->season_title }}</td>
                        <td>{{ $season->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <h2>Web Series</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $webSeries->title }}</td>
                        <td>{{ $webSeries->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>