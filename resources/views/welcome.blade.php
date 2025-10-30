<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - E-commerce Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .card i {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        h3 {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1 class="mb-5 fw-bold">Welcome to E-commerce Admin Dashboard</h1>
        <div class="row justify-content-center g-4">
            <div class="col-md-4">
                <a href="{{ route('admin.customers.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-light p-5 shadow-lg h-100">
                        <i class="fas fa-users text-primary"></i>
                        <h3>Manage Customers</h3>
                        <p class="text-muted">View and manage all customer records</p>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-dark">
                    <div class="card bg-light p-5 shadow-lg h-100">
                        <i class="fas fa-box text-success"></i>
                        <h3>Manage Orders</h3>
                        <p class="text-muted">View and manage all orders</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
