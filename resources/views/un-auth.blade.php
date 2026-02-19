<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm text-center border-0">
                <div class="card-body p-5">
                    <i class="bi bi-shield-lock-fill text-danger" style="font-size: 4rem;"></i>
                    <h1 class="card-title fw-bold mt-4">403</h1>
                    <h2 class="h4 text-muted">Access Forbidden</h2>
                    <p class="mt-4">
                        Your account does not have the required permissions to view this resource.
                        Please contact the site administrator if you believe this is an error.
                    </p>
                    <div class="mt-4">
                        <a href="javascript:history.back()" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left"></i> Go Back
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-house-door"></i> Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>