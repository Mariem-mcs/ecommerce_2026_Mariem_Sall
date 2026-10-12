<?php
require_once __DIR__ . '/../../core/core.php';

$base = $base ?? '../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-commerce Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 0; background: #f1f3f6; color: #222; }

        /* Navbar */
        nav { background: #1f2937; padding: 14px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        nav ul { list-style: none; display: flex; gap: 22px; margin: 0; padding: 0; align-items: center; }
        nav ul li a { color: #e5e7eb; text-decoration: none; font-weight: 600; transition: color 0.15s; }
        nav ul li a:hover { color: #ffcc00; }
        nav ul li a i { margin-right: 5px; }
        nav ul li.user { margin-left: auto; color: #facc15; font-weight: 600; }
        nav ul li.admin-badge {
            background: #dc2626; color: white; padding: 4px 10px; border-radius: 4px;
            font-size: 11px; font-weight: 700; letter-spacing: 1px;
        }

        .container { max-width: 1100px; margin: 35px auto; padding: 0 20px; }

        /* Page header */
        .page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 25px; }
        .page-header i { font-size: 28px; color: #2563eb; background: #e0ecff; padding: 14px; border-radius: 10px; }
        .page-header h1 { margin: 0; font-size: 26px; color: #1f2937; }
        .page-header p { margin: 2px 0 0 0; color: #6b7280; font-size: 14px; }

        /* Alerts */
        .success { color: #065f46; background: #d1fae5; padding: 12px 16px; border-radius: 8px; margin: 12px 0; border-left: 4px solid #10b981; }
        .error { color: #991b1b; background: #fee2e2; padding: 12px 16px; border-radius: 8px; margin: 12px 0; border-left: 4px solid #dc2626; }

        /* Panels */
        .panel { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 25px; }
        .panel h3 { margin-top: 0; color: #1f2937; font-size: 18px; display: flex; align-items: center; gap: 8px; }
        .panel h3 i { color: #2563eb; }

        /* Inline form */
        .form-inline { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .form-inline input[type="text"] {
            flex: 1; min-width: 220px; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;
        }
        .form-inline input[type="text"]:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.15); }

        /* Buttons */
        .btn { background: #2563eb; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer;
               font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background 0.15s; font-size: 14px; }
        .btn:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }

        /* Data table */
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .data-table th {
            background: #f3f4f6; color: #374151; text-align: left; padding: 12px 15px;
            font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .data-table th:first-child { border-top-left-radius: 8px; }
        .data-table th:last-child { border-top-right-radius: 8px; }
        .data-table td { padding: 14px 15px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        .data-table tr:hover td { background: #f9fafb; }
        .data-table td:last-child { white-space: nowrap; }

        /* Action pills */
        .action-link { padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 13px;
                       font-weight: 600; margin-right: 5px; display: inline-block; }
        .action-edit { background: #dbeafe; color: #1e40af; }
        .action-edit:hover { background: #bfdbfe; }
        .action-delete { background: #fee2e2; color: #991b1b; }
        .action-delete:hover { background: #fecaca; }

        /* Empty state */
        .empty-state { text-align: center; padding: 40px 20px; color: #9ca3af; }
        .empty-state i { font-size: 55px; color: #d1d5db; margin-bottom: 12px; display: block; }
        .empty-state p { margin: 0; font-size: 15px; }
        .empty-state small { color: #d1d5db; }

        /* Card grid (homepage) */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-top: 25px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06);
                text-decoration: none; color: #222; transition: all 0.15s; }
        .card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .card-icon { font-size: 40px; color: #2563eb; margin-bottom: 12px; }
        .card h3 { margin: 0 0 8px 0; color: #1f2937; }
        .card p { margin: 0; color: #6b7280; font-size: 14px; }

        /* Layout with sidebar */
        .page-with-sidebar { display: grid; grid-template-columns: 220px 1fr; gap: 25px; }
        @media (max-width: 768px) { .page-with-sidebar { grid-template-columns: 1fr; } }

        /* Sidebar */
        .sidebar-block { background: white; border-radius: 10px; padding: 18px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .sidebar-block h4 { margin: 0 0 12px 0; color: #1f2937; font-size: 15px; display: flex; align-items: center; gap: 8px; }
        .sidebar-block h4 i { color: #2563eb; }
        .sidebar-block ul { list-style: none; margin: 0; padding: 0; }
        .sidebar-block ul li { margin-bottom: 6px; }
        .sidebar-block ul li a { color: #374151; text-decoration: none; padding: 6px 10px; display: block; border-radius: 5px; font-size: 14px; transition: background 0.15s; }
        .sidebar-block ul li a:hover { background: #eff6ff; color: #2563eb; }

        /* Product grid */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; margin-top: 20px; }
        .product-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); transition: all 0.15s; text-decoration: none; color: inherit; display: block; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 180px; object-fit: cover; background: #f3f4f6; display: block; }
        .product-card .no-image { width: 100%; height: 180px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 42px; }
        .product-card .info { padding: 14px; }
        .product-card .title { font-weight: 600; color: #1f2937; font-size: 14px; margin: 0 0 6px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .product-card .price { color: #2563eb; font-weight: 700; font-size: 16px; margin: 0; }
        .product-card .meta { color: #9ca3af; font-size: 12px; margin-top: 6px; }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="<?= $base ?>index.php"><i class="fa fa-home"></i> Home</a></li>
            <li style="flex: 1; max-width: 320px; margin-left: 15px;">
                <form action="<?= $base ?>views/search_results.php" method="GET" style="display: flex;">
                    <input type="text" name="user_query" placeholder="Search products..."
                           style="flex: 1; padding: 7px 12px; border: none; border-radius: 6px 0 0 6px; font-size: 14px;">
                    <button type="submit"
                            style="background: #2563eb; color: white; border: none; padding: 7px 14px; border-radius: 0 6px 6px 0; cursor: pointer;">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </li>
            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <li class="admin-badge">ADMIN</li>
                    <li><a href="<?= $base ?>views/admin/brand.php"><i class="fa fa-tags"></i> Brands</a></li>
                    <li><a href="<?= $base ?>views/admin/category.php"><i class="fa fa-folder-open"></i> Categories</a></li>
                    <li><a href="<?= $base ?>views/admin/product.php"><i class="fa fa-cube"></i> Products</a></li>
                <?php endif; ?>
                <li class="user"><i class="fa fa-user"></i> Welcome, <?= htmlspecialchars($_SESSION['customer_name']) ?></li>
                <li><a href="<?= $base ?>logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="<?= $base ?>views/register.php"><i class="fa fa-user-plus"></i> Register</a></li>
                <li><a href="<?= $base ?>views/login.php"><i class="fa fa-sign-in"></i> Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="container">