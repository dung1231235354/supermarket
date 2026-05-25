<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Supermarket &mdash; Master Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Fraunces:wght@700;900&display=swap" rel="stylesheet">

<style>
    body { margin: 0; font-family: 'DM Sans', sans-serif; background-color: #fdf8e7; color: #1a1a1a; overflow-x: hidden; }
    a { text-decoration: none; color: inherit; }

    /* --- NAVIGATION BAR --- */
    .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; text-transform: uppercase; font-size: 13px; font-weight: 700; border-bottom: 2px solid #000; background-color: #fff; }
    
    .nav-group { display: flex; flex-direction: column; gap: 5px; border-right: 2px dashed #e0e0e0; padding-right: 20px; margin-right: 20px; }
    .nav-group-title { font-size: 10px; color: #888; margin-bottom: 5px; }
    .nav-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    
    .nav-left { display: flex; align-items: center; max-width: 65%; }
    .nav-right { display: flex; gap: 15px; align-items: center; }

    .logo { font-family: 'Fraunces', serif; font-size: 26px; font-weight: 900; text-transform: none; letter-spacing: -1px; line-height: 0.9; text-align: right; }

    /* --- BUTTONS --- */
    .cta-btn { background-color: #fff; color: #000; border: 2px solid #000; padding: 8px 12px; font-size: 11px; font-family: 'DM Sans', sans-serif; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: all 0.2s ease; box-shadow: 2px 2px 0px #000; }
    .cta-btn:hover { background-color: #000 !important; color: #f4c242 !important; box-shadow: 0px 0px 0px #000; transform: translate(2px, 2px); }
    .btn-yellow { background-color: #f4c242; }
    .btn-black { background-color: #000; color: #f4c242; }

    /* --- HERO --- */
    .hero { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; height: 75vh; padding: 0 20px; }
    .hero h1 { font-family: 'Fraunces', serif; font-size: 64px; font-weight: 900; margin-bottom: 20px; line-height: 1.1; letter-spacing: -1px; }
    .hero p { font-size: 18px; max-width: 650px; margin: 0 auto 30px auto; line-height: 1.5; font-weight: 500; }

    /* --- MODALS --- */
    .modal-overlay { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
    .modal-overlay:target { display: flex; }
    .modal-bg-close { position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: default; }
    .modal-container { position: relative; background-color: #fdf8e7; border: 4px solid #000; box-shadow: 15px 15px 0px #000; z-index: 1011; padding: 40px; width: 95vw; max-height: 85vh; overflow-y: auto; }
    .close-btn { position: absolute; top: 15px; right: 25px; font-size: 35px; font-weight: 900; color: #000; cursor: pointer; }
    .close-btn:hover { color: #d32f2f; }
    .modal-container h2 { font-family: 'Fraunces', serif; font-size: 36px; margin-top: 0; margin-bottom: 15px; }

    /* --- TABLES & FORMS --- */
    .table-section { margin-bottom: 50px; overflow-x: auto; }
    .table-section h3 { font-family: 'Fraunces', serif; font-size: 24px; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: -0.5px; }
    
    .data-table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; white-space: nowrap; }
    .data-table th { font-weight: 700; text-transform: uppercase; font-size: 11px; padding: 12px; background-color: #000; color: #f4c242; border: 2px solid #000; }
    .data-table td { padding: 12px; border: 2px solid #000; background-color: #fff; }
    .status-badge { padding: 6px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #000; border: 1px solid #000; display: inline-block; }

    .update-form { display: flex; flex-direction: column; gap: 15px; max-width: 500px; margin: 0 auto; }
    .form-group label { font-weight: 700; font-size: 11px; text-transform: uppercase; display: block; margin-bottom: 5px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 2px solid #000; font-family: 'DM Sans', sans-serif; box-sizing: border-box; }
    .form-row-double { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
</style>
</head>
<body>

    <header class="navbar">
        <div class="nav-left">
            <div class="nav-group">
                <div class="nav-group-title">ADD NEW DATA</div>
                <div class="nav-buttons">
                    <a href="add_product.html"><button class="cta-btn btn-black">+ Product</button></a>
                    <a href="add_customer.html"><button class="cta-btn btn-black">+ Customer</button></a>
                    <a href="add_supplier.html"><button class="cta-btn btn-black">+ Supplier</button></a>
                    <a href="add_transaction.html"><button class="cta-btn btn-black">+ Transaction</button></a>
                </div>
            </div>
            
            <div class="nav-group" style="border-right: none;">
                <div class="nav-group-title">UPDATE EXISTING</div>
                <div class="nav-buttons">
                    <a href="#updateModal"><button class="cta-btn">Product</button></a>
                    <a href="update_customer.html"><button class="cta-btn">Customer</button></a>
                    <a href="update_supplier.html"><button class="cta-btn">Supplier</button></a>
                    <a href="update_transaction.html"><button class="cta-btn">Trans State</button></a>
                </div>
            </div>
        </div>
        
        <div class="logo">
            Supermarket<br>Inventory
        </div>
        
        <div class="nav-right">
            <a href="#infoModal"><button class="cta-btn btn-yellow" style="padding: 12px 20px;">View Database</button></a>
        </div>
    </header>

    <section class="hero">
        <h1>Command your inventory.<br>Master your data.</h1>
        <p>Your centralized master dashboard for creating and updating products, managing customer and supplier profiles, and securely logging transactions.</p>
        
        <form action="search.php" method="GET" style="display: flex; gap: 10px; justify-content: center; align-items: stretch; margin-top: 10px; margin-bottom: 30px; width: 100%; max-width: 800px;">
            <select name="type" style="padding: 15px; border: 3px solid #000; font-family: 'DM Sans', sans-serif; font-size: 14px; font-weight: 700; box-shadow: 5px 5px 0px #000; outline: none; background-color: #fff; cursor: pointer;">
                <option value="all">All Data</option>
                <option value="product">Products</option>
                <option value="customer">Customers</option>
                <option value="supplier">Suppliers</option>
                <option value="transaction">Transactions</option>
            </select>
            <input type="text" name="query" placeholder="Enter keywords or IDs..." required style="padding: 18px; border: 3px solid #000; font-family: 'DM Sans', sans-serif; font-size: 15px; flex-grow: 1; box-shadow: 5px 5px 0px #000; outline: none;">
            <button type="submit" class="cta-btn btn-yellow" style="padding: 18px 30px; font-size: 14px; box-shadow: 5px 5px 0px #000;">Search</button>
        </form>

        <a href="#aboutModal">
            <button class="cta-btn btn-black" style="font-size: 12px; padding: 12px 30px;">About Us</button>
        </a>
    </section>

    <div id="updateModal" class="modal-overlay">
        <a href="#" class="modal-bg-close"></a>
        <div class="modal-container" style="max-width: 600px;">
            <a href="#" class="close-btn">&times;</a>
            <h2>Update Product</h2>
            <p style="font-size: 12px; margin-top: 0;">Leave boxes blank to keep current data.</p>
            <form class="update-form" action="update_product.php" method="POST">
                <div class="form-group" style="background-color: #f4c242; padding: 10px; border: 2px solid #000;">
                    <label>Product ID (Required):</label>
                    <input type="number" name="product_id" required min="1">
                </div>
                
                <div class="form-group"><label>New Name:</label><input type="text" name="name"></div>
                
                <div class="form-row-double">
                    <div class="form-group"><label>New Category:</label><input type="text" name="category"></div>
                    <div class="form-group"><label>New Supplier ID:</label><input type="number" name="supplier_id" min="1"></div>
                </div>

                <div class="form-row-double">
                    <div class="form-group"><label>New Price ($):</label><input type="number" name="price" step="0.01" min="0"></div>
                    <div class="form-group"><label>New Stock Count:</label><input type="number" name="stock" min="0"></div>
                </div>
                
                <div class="form-row-double">
                    <div class="form-group"><label>New Mfg Date:</label><input type="date" name="manufacture_date"></div>
                    <div class="form-group"><label>New Exp Date:</label><input type="date" name="expiry_date"></div>
                </div>

                <div class="form-group">
                    <label>Change Status:</label>
                    <select name="status">
                        <option value="">-- Don't Change --</option>
                        <option value="available">Available</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="discontinued">Discontinued</option>
                    </select>
                </div>
                <div class="form-group"><label>New Description:</label><textarea name="description" rows="2"></textarea></div>
                
                <button type="submit" class="cta-btn btn-yellow" style="width: 100%; font-size: 14px; padding: 15px;">Apply Changes</button>
            </form>
        </div>
    </div>

    <div id="infoModal" class="modal-overlay">
        <a href="#" class="modal-bg-close"></a>
        <div class="modal-container" style="max-width: 1300px;">
            <a href="#" class="close-btn">&times;</a>
            
            <h2 style="text-align: center; margin-bottom: 10px; font-size: 42px;">Master Database Viewer</h2>
            
            <div style="text-align: center; margin-bottom: 40px;">
                <a href="delete.html">
                    <button class="cta-btn" style="color: #d32f2f; border-color: #d32f2f; padding: 12px 25px; font-size: 13px;">
                        🗑️ Open Delete Control Panel
                    </button>
                </a>
            </div>

            <?php
            $conn = new mysqli("localhost", "root", "", "inventory_db");
            if ($conn->connect_error) {
                echo "<p>Database Connection Failed: " . $conn->connect_error . "</p>";
            } else {
            ?>

            <div class="table-section">
                <h3>Transactions History</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Trans ID</th>
                            <th>Date / Time</th>
                            <th>Cust ID</th>
                            <th>Items Purchased (System)</th> 
                            <th>Note / Summary</th>
                            <th>Tax</th>
                            <th>Discount</th>
                            <th>Total Amount</th>
                            <th>Method</th>
                            <th>Created At</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_trans = "SELECT t.transaction_id, t.date, t.customer_id, t.total_amount, t.payment_method, t.tax, t.discount, t.item_purchased, t.created_at, t.status, 
                                      GROUP_CONCAT(CONCAT('• ', p.name, ' (x', i.quantity, ')') SEPARATOR '<br>') AS items_list 
                                      FROM transaction t 
                                      LEFT JOIN involve i ON t.transaction_id = i.transaction_id 
                                      LEFT JOIN product p ON i.product_id = p.product_id 
                                      GROUP BY t.transaction_id ORDER BY t.transaction_id DESC";
                        
                        $result = $conn->query($sql_trans);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $badge_color = ($row['status'] === 'completed') ? "#f4c242" : "#e0e0e0";
                                echo "<tr>
                                        <td>#" . $row["transaction_id"] . "</td>
                                        <td>" . $row["date"] . "</td>
                                        <td>" . ($row["customer_id"] ?? "Guest") . "</td>
                                        <td>" . ($row["items_list"] ?? "No items") . "</td>
                                        <td>" . ($row["item_purchased"] ?: "N/A") . "</td>
                                        <td>$" . number_format($row["tax"], 2) . "</td>
                                        <td>$" . number_format($row["discount"], 2) . "</td>
                                        <td><strong>$" . number_format($row["total_amount"], 2) . "</strong></td>
                                        <td>" . ucfirst($row["payment_method"]) . "</td>
                                        <td>" . $row["created_at"] . "</td>
                                        <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                                      </tr>";
                            }
                        } else { echo "<tr><td colspan='11' style='text-align:center;'>No data found.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section">
                <h3>Inventory Products</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Supp ID</th>
                            <th>Mfg Date</th>
                            <th>Exp Date</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_prod = "SELECT * FROM product ORDER BY product_id DESC";
                        $result = $conn->query($sql_prod);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $badge_color = ($row['status'] === 'available') ? "#a2e8a2" : "#ff9e9e";
                                echo "<tr>
                                        <td>#" . $row["product_id"] . "</td>
                                        <td><strong>" . $row["name"] . "</strong></td>
                                        <td>" . ($row["category"] ?: "N/A") . "</td>
                                        <td>$" . number_format($row["price"], 2) . "</td>
                                        <td>" . $row["stock"] . " units</td>
                                        <td>" . ($row["supplier_id"] ?: "N/A") . "</td>
                                        <td>" . ($row["manufacture_date"] ?: "N/A") . "</td>
                                        <td>" . ($row["expiry_date"] ?: "N/A") . "</td>
                                        <td>" . ($row["description"] ?: "N/A") . "</td>
                                        <td><span class='status-badge' style='background-color: $badge_color;'>" . str_replace('_', ' ', $row['status']) . "</span></td>
                                      </tr>";
                            }
                        } else { echo "<tr><td colspan='10' style='text-align:center;'>No data found.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section">
                <h3>Customer Directory</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Cust ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>DOB</th>
                            <th>Gender</th>
                            <th>Reg Date</th>
                            <th>Points</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_cust = "SELECT * FROM customer ORDER BY customer_id DESC";
                        $result = $conn->query($sql_cust);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $badge_color = ($row['status'] === 'active') ? "#a2e8a2" : "#e0e0e0";
                                echo "<tr>
                                        <td>#" . $row["customer_id"] . "</td>
                                        <td><strong>" . $row["name"] . "</strong></td>
                                        <td>" . ($row["phone"] ?: "N/A") . "</td>
                                        <td>" . ($row["email"] ?: "N/A") . "</td>
                                        <td>" . ($row["address"] ?: "N/A") . "</td>
                                        <td>" . ($row["date_of_birth"] ?: "N/A") . "</td>
                                        <td>" . ($row["gender"] ?: "N/A") . "</td>
                                        <td>" . ($row["registration_date"] ?: "N/A") . "</td>
                                        <td>" . $row["points"] . "</td>
                                        <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                                      </tr>";
                            }
                        } else { echo "<tr><td colspan='10' style='text-align:center;'>No data found.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-section" style="margin-bottom: 0;">
                <h3>Supplier Network</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Supp ID</th>
                            <th>Company Name</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Country</th>
                            <th>Website</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_supp = "SELECT * FROM supplier ORDER BY supplier_id DESC";
                        $result = $conn->query($sql_supp);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $badge_color = ($row['status'] === 'active') ? "#a2e8a2" : "#ff9e9e";
                                echo "<tr>
                                        <td>#" . $row["supplier_id"] . "</td>
                                        <td><strong>" . $row["name"] . "</strong></td>
                                        <td>" . ($row["type"] ?: "N/A") . "</td>
                                        <td>" . ($row["contact"] ?: "N/A") . "</td>
                                        <td>" . ($row["email"] ?: "N/A") . "</td>
                                        <td>" . ($row["address"] ?: "N/A") . "</td>
                                        <td>" . ($row["country"] ?: "N/A") . "</td>
                                        <td>" . ($row["website"] ?: "N/A") . "</td>
                                        <td>" . ($row["description"] ?: "N/A") . "</td>
                                        <td><span class='status-badge' style='background-color: $badge_color;'>" . ucfirst($row['status']) . "</span></td>
                                      </tr>";
                            }
                        } else { echo "<tr><td colspan='10' style='text-align:center;'>No data found.</td></tr>"; }
                        ?>
                    </tbody>
                </table>
            </div>

            <?php 
                $conn->close(); 
            } 
            ?>

        </div>
    </div>

    <div id="aboutModal" class="modal-overlay">
        <a href="#" class="modal-bg-close"></a>
        <div class="modal-container" style="max-width: 900px; padding: 0;">
            <a href="#" class="close-btn" style="z-index: 10; background: #fff; padding: 0 10px; right: 15px;">&times;</a>
            
            <div style="display: flex; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <img src="produce.JPG" alt="Fresh Produce" style="width: 100%; height: 100%; object-fit: cover; display: block; border-right: 4px solid #000;">
                </div>
                
                <div style="flex: 1; min-width: 300px; padding: 50px;">
                    <h2 style="font-family: 'Fraunces', serif; font-size: 36px; margin-top: 0; margin-bottom: 15px;">About Us</h2>
                    <div style="font-size: 15px; line-height: 1.6; margin-top: 20px;">
                        <p style="font-family: 'Fraunces', serif; font-size: 26px; font-weight: 700; border-left: 5px solid #f4c242; padding-left: 20px; margin: 0 0 25px 0; line-height: 1.2;">
                            Curating the world's finest culinary ingredients.
                        </p>
                        <p>We are dedicated to providing top-tier chefs and passionate home cooks with unparalleled access to premium, artisanal, and luxury supermarket goods.</p>
                        <p>Our bespoke inventory management system ensures peak freshness, precise stock tracking, and seamless relationships with our global network of suppliers and valued customers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
