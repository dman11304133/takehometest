<!DOCTYPE html>
<html>
<head>
    <title>Potentially Duplicate Funds</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Potentially Duplicate Funds</h1>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Manager</th>
        <th>Start Year</th>
        <th>Company</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($funds as $fund)
        <tr>
            <td>{{ $fund->name }}</td>
            <td>{{ optional($fund->manager)->name }}</td>
            <td>{{ $fund->start_year }}</td>
            <td>{{ optional($fund->company)->name }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No potentially duplicate funds found.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<a href="{{ route('funds.index') }}">Go Back to Fund Index</a>
</body>
</html>
