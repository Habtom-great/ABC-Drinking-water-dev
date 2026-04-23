<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .topbar {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-bottom: 1px solid #ddd;
        }
        .content {
            margin-left: 250px;
            padding: 1rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h4 class="text-center">Dashboard</h4>
            <a href="#">Dashboard</a>
            <a href="#">Product</a>
            <a href="#">Sales</a>
            <a href="#">Purchase</a>
            <a href="#">Expense</a>
            <a href="#">Quotation</a>
            <a href="#">Transfer</a>
            <a href="#">Return</a>
            <a href="#">People</a>
            <a href="#">Places</a>
            <a href="#">Reports</a>
            <a href="#">Settings</a>
        </nav>

        <!-- Main Content Area -->
        <div class="flex-grow-1">
            <!-- Top Bar -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <input type="text" class="form-control w-50" placeholder="Search Here...">

                <div>
                    <button class="btn btn-outline-secondary btn-sm">English</button>
                    <button class="btn btn-outline-secondary btn-sm">French</button>
                    <button class="btn btn-outline-secondary btn-sm">Spanish</button>
                    <button class="btn btn-outline-secondary btn-sm">German</button>
                </div>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="profileMenu" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i> John Doe
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content">
                <h2>Welcome to the Dashboard</h2>
                <p>Use the sidebar to navigate through the features.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
kkkkkkkkkkk
