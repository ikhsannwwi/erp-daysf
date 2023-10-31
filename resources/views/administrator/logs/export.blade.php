<!DOCTYPE html>
<html>

<head>
    <title>Log Systems {{ $settings['nama_app_admin'] }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+lvjsl5+d6ZqbmzJw5x5lE5hJF5iR5CSK3xaF9Mf5XxjXG55" crossorigin="anonymous">

</head>

<body>
    <h3> Log Systems {{ $settings['nama_app_admin'] }} </h3>
    @foreach ($data as $row)
    <div class="row">
        <div class="col-3">ID:</div>
        <div class="col-9">{{ $row->id ? $row->id : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">User:</div>
        <div class="col-9">{{ $row->user->name ? $row->user->name : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Module:</div>
        <div class="col-9">{{ $row->module ? $row->module : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Action:</div>
        <div class="col-9">{{ $row->action ? $row->action : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Tanggal:</div>
        <div class="col-9">{{ $row->created_at ? $row->created_at : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Ip Address:</div>
        <div class="col-9">{{ $row->ip_address ? $row->ip_address : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Device:</div>
        <div class="col-9">{{ $row->device ? $row->device : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Browser Name:</div>
        <div class="col-9">{{ $row->browser_name ? $row->browser_name : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Browser Version:</div>
        <div class="col-9">{{ $row->browser_version ? $row->browser_version : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Data ID:</div>
        <div class="col-9">{{ $row->data_id ? $row->data_id : '' }}</div>
    </div>
    
    <div class="row">
        <div class="col-3">Data:</div>
        <div class="col-9">{{ $row->data ? $row->data : '' }}</div>
    </div>
    <br>
    <br>
    @endforeach
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-bA2GI6zK5RP6oaGfZW5UL2fvZ6IqAHA7feWzI5KtpFwR5N5B2s1IjVlNq+rwlzmkf" crossorigin="anonymous">
    </script>
</body>

</html>
