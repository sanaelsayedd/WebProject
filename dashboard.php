<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-styles.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="sidebar close">
        <div class="logo-details">
            <i class='bx bx-book-open'></i>
            <span class="logo_name">KnowledgeNest</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="index.php">
                    <i class='bx bx-home'></i>
                    <span class="link_name">Home</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="index.php">Home</a></li>
                </ul>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="dashboard.php">Dashboard</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-book'></i>
                        <span class="link_name">Books</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="books.php">Books</a></li>
                    <li><a href="books.php">Books</a></li>
                    <li><a href="Addreversation.php">Reversition Books</a></li>
                    <li><a href="AddBorrow.php">Borrowed Books</a></li>
                </ul>
            </li>
            <li>
                <div class="iocn-link">
                    <a href="#">
                        <i class='bx bx-user'></i>
                        <span class="link_name">Users</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link_name" href="#">Users</a></li>
                    <li><a href="manageUser.php">Manage Users</a></li>
                </ul>
            </li>
            <li>
                <a href="settings.php">
                    <i class='bx bx-cog'></i>
                    <span class="link_name">Settings</span>
                </a>
                <ul class="sub-menu blank">
                    <li><a class="link_name" href="settings.php">Settings</a></li>
                </ul>
            </li>
            <li>
                <div class="profile-details">
                    <div class="profile-content">
                        <i class='bx bx-user-circle'></i>
                    </div>
                    <div class="name-job">
                        <!-- <div class="profile_name"><?php echo $_SESSION['username']; ?></div> -->
                        <div class="job">Administrator</div>
                    </div>
                    <a href="logout.php"><i class='bx bx-log-out'></i></a>
                </div>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text">Dashboard</span>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class='bx bx-tachometer'></i>
                    <span class="text">Dashboard Overview</span>
                </div>

                <div class="boxes">
                    <!-- Total Books -->
                    <div class="box box1">
                        <i class='bx bx-book-alt'></i>
                        <span class="text">Total Books</span>
                        <span class="number"><?php echo $total_books ?? '0'; ?></span>
                    </div>

                    <!-- Borrowed Books -->
                    <div class="box box2">
                        <i class='bx bx-book-reader'></i>
                        <span class="text">Borrowed Books</span>
                        <span class="number"><?php echo $borrowed_books ?? '0'; ?></span>
                    </div>

                    <!-- Reservations -->
                    <div class="box box3">
                        <i class='bx bx-bookmark'></i>
                        <span class="text">Reservations</span>
                        <span class="number"><?php echo $reservations ?? '0'; ?></span>
                    </div>

                    <!-- Total Fines -->
                    <div class="box box4">
                        <i class='bx bx-money'></i>
                        <span class="text">Total Fines</span>
                        <span class="number">$<?php echo $total_fines ?? '0'; ?></span>
                    </div>

                    <!-- New cards -->
                    <div class="box box5">
                        <i class='bx bx-user'></i>
                        <span class="text">Total Users</span>
                        <span class="number"><?php echo $total_users ?? '0'; ?></span>
                    </div>

                    <div class="box box6">
                        <i class='bx bx-time'></i>
                        <span class="text">Overdue Books</span>
                        <span class="number"><?php echo $overdue_books ?? '0'; ?></span>
                    </div>

                    <div class="box box7">
                        <i class='bx bx-check-circle'></i>
                        <span class="text">Available Books</span>
                        <span class="number"><?php echo $available_books ?? '0'; ?></span>
                    </div>

                    <div class="box box8">
                        <i class='bx bx-user-check'></i>
                        <span class="text">Active Users</span>
                        <span class="number"><?php echo $active_users ?? '0'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/dashboard.js"></script>
</body>
</html>