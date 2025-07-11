<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div class="container">
            <h2 class="text-center pt-5">Welcome admin, {{$admin->name}}</h2>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="{{ route('updaterole') }}" id="role" method="post">
                        @foreach ($posts as $post)
                            <tr>

                                <td>{{ $post->id }}</td>
                                <th>{{ $post->name }}</th>
                                <td>{{ $post->email }}</td>
                                <td>{{ $post->role }}</td>
                                <td>
                                    <div class="col-4">
                                        @csrf
                                        @method('PUT')
                                        <select name="role[{{ $post->id }}]" id="select"
                                            class="select form-select">
                                            <option value="admin">admin</option>
                                            <option value="client">client</option>
                                        </select>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </form>
                </tbody>
            </table>
            <button type="submit" form="role" class="btn btn-primary">update changes</button>
            <a href="{{ route('logout') }}"><button type="button" class="btn btn-danger my-3">logout</button></a>
            @if (@session('error'))
                <div class="error pt-2">{{ session('error') }}</div>
            @endif
            @if (@session('success'))
                <div class='success pt-2'>{{ session('success') }}</div>
            @endif
        </div>
    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script>
        let selectedoptions = [];
        @foreach ($posts as $post)
            selectedoptions.push('{{ $post->role }}');
        @endforeach
        let selects = document.querySelectorAll('.select');
        selects.forEach((select, index) => {
            select.value = selectedoptions[index];


        });
    </script>
</body>

</html>
