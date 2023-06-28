<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Fund</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"],
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 12px;
        }

        #alias_container {
            margin-bottom: 12px;
        }

        .alias-input {
            margin-bottom: 6px;
        }

        .remove-alias {
            background-color: #ff6666;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-alias:hover {
            background-color: #ff4d4d;
        }

        form button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Create Fund</h1>
<a href="{{ route('funds.index') }}">Go Back to Fund Index</a>
<form action="{{ route('funds.store') }}" method="POST">
    @csrf

    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name">
    </div>

    <div>
        <label for="start_year">Start Year:</label>
        <input type="number" id="start_year" name="start_year" required>
    </div>

    <div>
        <label for="manager_id">Manager:</label>
        <select name="manager_id" id="manager_id">
            @foreach ($managers as $manager)
                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="company_id">Company:</label>
        <select name="company_id[]" id="company_id" multiple size="4">
            @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>



    <div>
        <label for="alias_name">Alias:</label>
        <div id="alias_container">
            <input type="text" name="alias_name[]" class="alias-input">
        </div>
        <button type="button" id="add_alias">Add Alias</button>
    </div>

    <button type="submit">Create</button>
</form>

<script>
    // Script to add multiple alias input fields dynamically
    document.getElementById('add_alias').addEventListener('click', function() {
        var aliasDiv = document.createElement('div');
        aliasDiv.innerHTML = `
            <input type="text" name="alias_name[]" class="alias-input">
            <button type="button" class="remove-alias">Remove</button>
        `;
        document.getElementById('alias_container').appendChild(aliasDiv);
    });

    document.getElementById('alias_container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-alias')) {
            event.target.parentElement.remove();
        }
    });
</script>

</body>
</html>
