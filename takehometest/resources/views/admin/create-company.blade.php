<!DOCTYPE html>
<html>
<head>
    <title>Create Company</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            color: #333333;
        }

        form {
            margin-top: 20px;
            width: 300px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        button[type="submit"],
        .back-button {
            padding: 8px 12px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover,
        .back-button:hover {
            background-color: #0056b3;
        }

        p {
            color: green;
        }
    </style>
</head>
<body>
<h1>Create Company</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form action="{{ route('admin.store-company') }}" method="POST">
    @csrf
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <button type="submit">Create Company</button>
        <a href="{{ route('funds.index') }}">Go Back to Fund Index</a>
    </div>
</form>
</body>
</html>
