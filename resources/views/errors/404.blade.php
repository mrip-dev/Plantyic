<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Optional: Bootstrap Icons for a nice touch -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-primary">404</h1>
            <p class="fs-3">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                Oops! Page Not Found.
            </p>
            <p class="lead">
                The page you are looking for does not exist or has been moved.
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="bi bi-house-door"></i> Go to Homepage
            </a>
        </div>
    </div>

</body>
</html>