<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .text-center {
            text-align: center;
        }

        .mt-3 {
            margin-top: 10px; /* Adjust as needed */
        }

        /* Add more styles as needed for customization */
    </style>
</head>

<body>
    <div class="text-center">
        {!! $data->barcode !!}
        <div class="mt-3">{{ $data->kode }}</div>
    </div>
</body>

</html>
