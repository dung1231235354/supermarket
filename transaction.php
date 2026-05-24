<?php
require_once 'db.php';

$action  = $_GET['action'] ?? 'list';
$id      = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id    = (int)($_POST['customer_id'] ?? 0);
    $date           = sanitize($conn, $_POST['date'] ?? date('Y-m-d'));
    $total_amount   = (int)($_POST['total_amount'] ?? 0);
    $payment_method = sanitize($conn, $_POST['payment_method'] ?? 'cash');
    $item           = sanitize($conn, $_POST['item'] ?? ''); // Đổi từ note thành item
    $tax            = (int)($_POST['tax'] ?? 0);
    $discount       = (int)($_POST['discount'] ?? 0);
    $created_at     = sanitize($conn, $_POST['created_at'] ?? date('Y-m-d'));
    $product_ids    = $_POST['product_ids'] ?? [];
    $quantities     = $_POST['quantities'] ?? [];
    $unit_prices    = $_POST['unit_prices'] ?? [];

    $cust_val = $customer_id > 0 ? $customer_id : 'NULL';

    if (!empty($_POST['transaction_id'])) {
        $tid = (int)$_POST['transaction_id'];
        // Thay note='$note' bằng item='$item'
        $sql = "UPDATE transaction SET customer_id=$cust_val, date='$date', total_amount=$total_amount,
                payment_method='$payment_method', item='$item', tax=$tax, discount=$discount,
                created_at='$created_at' WHERE transaction_id=$tid";
        $conn->query($sql);
        
        $conn->query("DELETE FROM involve WHERE transaction_id=$tid");
        foreach ($product_ids as $i => $pid) {
            $pid_int  = (int)$pid;
            $qty      = (int)($quantities[$i] ?? 1);
            $uprice   = (float)($unit_prices[$i] ?? 0);
            if ($pid_int > 0) {
                $conn->query("INSERT IGNORE INTO involve (product_id, transaction_id, quantity, unit_price)
                              VALUES ($pid_int, $tid, $qty, $uprice)");
            }
        }
        $message = 'Transaction updated successfully!';
    } else {
        
        $sql = "INSERT INTO transaction (customer_id, date, total_amount, payment_method, item, tax, discount, created_at)
                VALUES ($cust_val,'$date',$total_amount,'$payment_method','$item',$tax,$discount,'$created_at')";
        $conn->query($sql);
        $tid = $conn->insert_id;
        foreach ($product_ids as $i => $pid) {
            $pid_int = (int)$pid;
            $qty     = (int)($quantities[$i] ?? 1);
            $uprice  = (float)($unit_prices[$i] ?? 0);
            if ($pid_int > 0) {
                $conn->query("INSERT IGNORE INTO involve (product_id, transaction_id, quantity, unit_price)
                              VALUES ($pid_int, $tid, $qty, $uprice)");
            }
        }
        $message = 'Transaction added successfully!';
    }
    $action = 'list';
}

if ($action === 'delete' && $id) {
    $conn->query("DELETE FROM involve WHERE transaction_id=$id");
    $conn->query("DELETE FROM transaction WHERE transaction_id=$id");
    $message = 'Transaction deleted.';
    $action  = 'list';
}

$editRow      = null;
$editProducts = [];
if ($action === 'edit' && $id) {
    $res     = $conn->query("SELECT * FROM transaction WHERE transaction_id=$id");
    $editRow = $res ? $res->fetch_assoc() : null;
    if (!$editRow) {
        $action = 'list';
    } else {
        $inv = $conn->query("SELECT i.*, p.name FROM involve i LEFT JOIN product p ON i.product_id=p.product_id WHERE i.transaction_id=$id");
        while ($row = $inv->fetch_assoc()) $editProducts[] = $row;
    }
}

$customersDD = $conn->query("SELECT customer_id, name FROM customer WHERE status='active' ORDER BY name");
$productsDD  = $conn->query("SELECT product_id, name, price FROM product WHERE status='available' ORDER BY name");

$search = sanitize($conn, $_GET['search'] ?? '');
$where  = $search ? "WHERE c.name LIKE '%$search%'" : '';

$transactions = $conn->query("
    SELECT t.*, c.name AS customer_name,
           GROUP_CONCAT(p.name SEPARATOR ', ') AS products
    FROM transaction t
    LEFT JOIN customer c ON t.customer_id = c.customer_id
    LEFT JOIN involve i  ON t.transaction_id = i.transaction_id
    LEFT JOIN product p  ON i.product_id = p.product_id
    $where
    GROUP BY t.transaction_id
    ORDER BY t.transaction_id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transactions &mdash; InventoryPro</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="main-content">
  <header class="topbar">
    <div class="topbar-left">
      <h1 class="page-title">Transactions</h1>
      <span class="page-sub">Sales records &amp; order history</span>
    </div>
    <div class="topbar-right">
      <a href="transactions.php?action=add" class="btn btn-primary">+ New Transaction</a>
    </div>
  </header>

  <?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if ($action === 'add' || $action === 'edit'): ?>
  <div class="card form-card">
    <div class="card-header">
      <h2 class="card-title"><?= $action === 'edit' ? '✏️ Edit Transaction' : '➕ New Transaction' ?></h2>
    </div>
    <form method="POST" action="transactions.php" id="txForm">
      <?php if ($editRow): ?>
        <input type="hidden" name="transaction_id" value="<?= $editRow['transaction_id'] ?>">
      <?php endif; ?>
      <div class="form-grid">
        <div class="form-group">
          <label>Customer</label>
          <select name="customer_id">
            <option value="">— Walk-in —</option>
            <?php if ($customersDD): $customersDD->data_seek(0); while ($c = $customersDD->fetch_assoc()): ?>
              <option value="<?= $c['customer_id'] ?>" <?= ($editRow['customer_id'] ?? '') == $c['customer_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endwhile; endif; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date" value="<?= $editRow['date'] ?? date('Y-m-d') ?>">
        </div>
        <div class="form-group">
          <label>Payment Method</label>
          <select name="payment_method">
            <?php foreach (['cash','card','transfer','e-wallet'] as $pm): ?>
              <option value="<?= $pm ?>" <?= ($editRow['payment_method'] ?? '') === $pm ? 'selected' : '' ?>><?= ucfirst($pm) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Tax (%)</label>
          <input type="number" name="tax" min="0" value="<?= $editRow['tax'] ?? 10 ?>">
        </div>
        <div class="form-group">
          <label>Discount (%)</label>
          <input type="number" name="discount" min="0" value="<?= $editRow['discount'] ?? 0 ?>">
        </div>
        <div class="form-group">
          <label>Total Amount (₫)</label>
          <input type="number" name="total_amount" min="0" value="<?= $editRow['total_amount'] ?? 0 ?>" id="totalAmount">
        </div>
        <div class="form-group">
          <label>Created At</label>
          <input type="date" name="created_at" value="<?= $editRow['created_at'] ?? date('Y-m-d') ?>">
        </div>
        <div class="form-group full">
          <label>Item Description / Purchased Products</label>
          <textarea name="item" rows="2" placeholder="example: EtaCola (5), BetaBeans (10)..."><?= htmlspecialchars($editRow['item'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="involve-section">
        <div class="involve-header">
          <h3>Products Involved</h3>
          <button type="button" class="btn btn-sm" onclick="addProductRow()">+ Add Product</button>
        </div>
        <table class="data-table" id="productTable">
          <thead>
            <tr><th>Product</th><th>Unit Price ($)</th><th>Qty</th><th></th></tr>
          </thead>
          <tbody id="productRows">
            <?php if ($editProducts): foreach ($editProducts as $ep): ?>
            <tr>
              <td>
                <select name="product_ids[]" class="product-select" onchange="fillPrice(this)">
                  <option value="">— Select —</option>
                  <?php if ($productsDD): $productsDD->data_seek(0); while ($pd = $productsDD->fetch_assoc()): ?>
                    <option value="<?= $pd['product_id'] ?>" data-price="<?= $pd['price'] ?>"
                      <?= $ep['product_id'] == $pd['product_id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($pd['name']) ?>
                    </option>
                  <?php endwhile; endif; ?>
                </select>
              </td>
              <td><input type="number" name="unit_prices[]" step="0.01" min="0" value="<?= $ep['unit_price'] ?>" class="unit-price"></td>
              <td><input type="number" name="quantities[]" min="1" value="<?= $ep['quantity'] ?>" class="qty"></td>
              <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">✕</button></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
              <td>
                <select name="product_ids[]" class="product-select" onchange="fillPrice(this)">
                  <option value="">— Select —</option>
                  <?php if ($productsDD): $productsDD->data_seek(0); while ($pd = $productsDD->fetch_assoc()): ?>
                    <option value="<?= $pd['product_id'] ?>" data-price="<?= $pd['price'] ?>"><?= htmlspecialchars($pd['name']) ?></option>
                  <?php endwhile; endif; ?>
                </select>
              </td>
              <td><input type="number" name="unit_prices[]" step="0.01" min="0" value="0" class="unit-price"></td>
              <td><input type="number" name="quantities[]" min="1" value="1" class="qty"></td>
              <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">✕</button></td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Save Transaction</button>
        <a href="transactions.php" class="btn btn-ghost">Cancel</a>
      </div>
    </form>
  </div>

  <script>
  const productOptions = `<?php
    $productOptionsHTML = '';
    if ($productsDD) {
        $productsDD->data_seek(0);
        while ($pd = $productsDD->fetch_assoc()) {
            $productOptionsHTML .= '<option value="' . $pd['product_id'] . '" data-price="' . $pd['price'] . '">' . htmlspecialchars($pd['name']) . '</option>';
        }
    }
    echo addslashes($productOptionsHTML);
  ?>`;

  function addProductRow() {
    const tbody = document.getElementById('productRows');
    const row = document.createElement('tr');
    row.innerHTML = `
      <td><select name="product_ids[]" class="product-select" onchange="fillPrice(this)">
        <option value="">— Select —</option>${productOptions}
      </select></td>
      <td><input type="number" name="unit_prices[]" step="0.01" min="0" value="0" class="unit-price"></td>
      <td><input type="number" name="quantities[]" min="1" value="1" class="qty"></td>
      <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">✕</button></td>
    `;
    tbody.appendChild(row);
  }

  function fillPrice(sel) {
    const opt   = sel.options[sel.selectedIndex];
    const price = opt.getAttribute('data-price') || 0;
    sel.closest('tr').querySelector('.unit-price').value = price;
  }
  </script>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <h2 class="card-title">All Transactions (<?= $transactions ? $transactions->num_rows : 0 ?>)</h2>
      <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search customer…" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-sm">Search</button>
        <?php if ($search): ?><a href="transactions.php" class="btn btn-sm btn-ghost">Clear</a><?php endif; ?>
      </form>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>#</th><th>Customer</th><th>Date</th><th>Products</th>
            <th>Total</th><th>Tax</th><th>Discount</th><th>Payment</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($transactions && $transactions->num_rows > 0): ?>
            <?php while ($tx = $transactions->fetch_assoc()): ?>
            <tr>
              <td><code>#<?= $tx['transaction_id'] ?></code></td>
              <td><?= htmlspecialchars($tx['customer_name'] ?? 'Walk-in') ?></td>
              <td><?= $tx['date'] ?></td>
              <td class="products-cell"><?= htmlspecialchars($tx['products'] ?? '—') ?></td>
              <td><strong><?= number_format($tx['total_amount']) ?> ₫</strong></td>
              <td><?= $tx['tax']% ?></td>
              <td><?= $tx['discount']% ?></td>
              <td><span class="badge badge-info"><?= $tx['payment_method'] ?></span></td>
              <td class="actions">
                <a href="transactions.php?action=edit&id=<?= $tx['transaction_id'] ?>" class="btn btn-sm">Edit</a>
                <a href="transactions.php?action=delete&id=<?= $tx['transaction_id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete transaction #<?= $tx['transaction_id'] ?>?')">Delete</a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="9" class="empty-row">No transactions found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>