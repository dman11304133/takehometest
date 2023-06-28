<style>
    /* Form container */
    .form-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f4f4f4;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Form heading */
    .form-container h2 {
        margin-top: 0;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Form fields */
    .form-container label {
        display: block;
        margin-bottom: 10px;
    }

    .form-container input[type="text"],
    .form-container select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    /* Submit button */
    .form-container button[type="submit"] {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .form-container button[type="submit"]:hover {
        background-color: #45a049;
    }
</style>

<div class="form-container">
    <form action="{{ route('funds.update', $fund->id) }}" method="POST">
        @csrf
        @method('PUT')
<h1>Update Fund information</h1>
        <input type="hidden" name="fund" value="{{ $fund->id }}">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ $fund->name }}">

        <label for="manager_id">Manager:</label>
        <select name="manager_id" id="manager_id">
            @foreach ($managers as $manager)
                <option value="{{ $manager->id }}" @if ($fund->manager_id == $manager->id) selected @endif>{{ $manager->name }}</option>
            @endforeach
        </select>

        <label for="aliases">Aliases:</label>
        <select name="aliases[]" id="aliases" multiple>
            @foreach ($aliases as $alias)
                <option value="{{ $alias->id }}" @if ($fund->aliases->contains($alias->id)) selected @endif>{{ $alias->alias }}</option>
            @endforeach
        </select>

        <label for="company_ids">Companies:</label>
        <select name="company_ids[]" id="company_ids" multiple>
            @foreach ($companies as $company)
                <option value="{{ $company->id }}" @if ($fund->companies->contains($company->id)) selected @endif>{{ $company->name }}</option>
            @endforeach
        </select>

        <button type="submit">Update Fund</button>
    </form>

</div>
