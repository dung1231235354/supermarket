<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
    'index.php'        => ['icon' => '🏠', 'label' => 'Dashboard'],
    'products.php'     => ['icon' => '📦', 'label' => 'Products'],
    'suppliers.php'    => ['icon' => '🏭', 'label' => 'Suppliers'],
    'customers.php'    => ['icon' => '👤', 'label' => 'Customers'],
    'transactions.php' => ['icon' => '🧾', 'label' => 'Transactions'],
];
?>
<aside class="sidebar">
  <div class="sidebar-logo">
    <span class="logo-icon">⬡</span>
    <span class="logo-text">InventoryPro</span>
  </div>
  <nav class="sidebar-nav">
    <?php foreach ($navItems as $page => $item): ?>
      <a href="<?= $page ?>" class="nav-item <?= $currentPage === $page ? 'active' : '' ?>">
        <span class="nav-icon"><?= $item['icon'] ?></span>
        <span class="nav-label"><?= $item['label'] ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
  <div class="sidebar-footer">
    <span>Group 3 &copy; <?= date('Y') ?></span>
  </div>
</aside>


