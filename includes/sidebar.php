<?php if(in_array($userRole, ['admin'])): ?>
    <a href="/ABC-Drinking-water/manage_users.php">
        <i class="fas fa-users"></i> Users
    </a>
<?php endif; ?>

<?php if(in_array($userRole, ['admin','sales'])): ?>
    <a href="/ABC-Drinking-water/modules/sales/">
        <i class="fas fa-shopping-cart"></i> Sales
    </a>
<?php endif; ?>

<?php if(in_array($userRole, ['admin','inventory'])): ?>
    <a href="/ABC-Drinking-water/modules/inventory/">
        <i class="fas fa-boxes"></i> Inventory
    </a>
<?php endif; ?>

<?php if(in_array($userRole, ['admin','purchases'])): ?>
    <a href="/ABC-Drinking-water/modules/purchases/">
        <i class="fas fa-truck"></i> Purchases
    </a>
<?php endif; ?>

<?php if(in_array($userRole, ['admin','accountant'])): ?>
    <a href="/ABC-Drinking-water/modules/accounting/">
        <i class="fas fa-coins"></i> Accounting
    </a>
<?php endif; ?>