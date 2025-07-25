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
    <div class="container d-flex justify-content-center">
        <form action="{{ Route('login') }}" class="col-6" method="post">
            @csrf
            @method('POST')


            <div class="form-group col-md-8 my-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"
                    placeholder="Email">
            </div>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
            <div class="form-group col-md-8 my-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
              <div class="form-group col-md-8 my-4">
                  <input type="checkbox" class="form-check-input" name="remember" id="remember">
                  <label for="remember">remember me</label>
            </div>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary">login</button>
            @if (session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
            @if (session('status'))
                <p class="success">{{session('status')}}</p>
            @endif
            <p class="my-4"><a href="{{ route('password.request') }}">forget password</a></p>
            <p class="my-4">new user? <a href="{{ route('register') }}">register</a></p>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    
</body>

</html>
