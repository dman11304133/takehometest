<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Funds</title>
    <style>
        .create-link {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }

        .create-link:hover {
            background-color: #0056b3;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .edit-link {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
        }

        .edit-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h1>Funds</h1>
<a class="create-link" href="{{ route('funds.create') }}">Create Fund</a> ||
<a class="create-link" href="{{ route('funds.potential-duplicates') }}">Potential Duplicate Loans</a> ||
<a href="{{ route('admin.create-manager') }}" class="create-link">Create Manager</a> ||
<a href="{{ route('admin.create-company') }}" class="create-link">Create Company</a>

@if (session('message'))
    <div class="alert alert-info">
        {{ session('message') }}
    </div>
@endif

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Manager</th>
        <th>Company</th>
        <th>Aliases</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($funds as $fund)
        <tr>
            <td>
                <span>{{ $fund->name }}</span>
            </td>
            <td>
                <span>{{ $fund->manager->name }}</span>
            </td>
            <td>
                @if ($fund->fundCompanyInvestments && $fund->fundCompanyInvestments->isNotEmpty())
                    @foreach ($fund->fundCompanyInvestments as $investment)
                        <span>{{ $investment->company->name }}</span><br>
                    @endforeach
                @endif
            </td>

            <td>
                @foreach ($fund->aliases as $alias)
                    {{ $alias->alias }}<br>
                @endforeach
            </td>
            <td>
                <a class="edit-link">Edit</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
