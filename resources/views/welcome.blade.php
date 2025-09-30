<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Excel import</title>
</head>
<body>
    <h1>Excel import</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('memory'))
        <p>memory top usage: {{ session('memory') }}</p>
    @endif

    @if (session('skipped_count'))
        <p>Skipped: {{ session('skipped_count') }}</p>
    @endif

    @if (session('execution_time'))
        <p>Execution time: {{ session('execution_time') }}</p>
    @endif

    <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Choose an excel file:</label>
            <input type="file" name="file" id="file" required>
        </div>

        <button type="submit">Import</button>
    </form>
</body>
</html>

