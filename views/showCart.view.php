<?php include __DIR__ . '/../views/partials/head.php'; ?>
<?php include __DIR__ . '/../views/partials/nav.php'; ?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 text-center">Your Cart</h1>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <?php if (empty($cartItems)): ?>
        <p class="text-center text-gray-500">Your cart is empty.</p>
    <?php else: ?>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Book</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td class="border px-4 py-2 flex items-center">
                            <img src="images/<?= htmlspecialchars($item['image']) ?>" class="w-16 h-24 mr-4">
                            <?= htmlspecialchars($item['title']) ?>
                        </td>
                        <td class="border px-4 py-2 text-center"><?= $item['quantity'] ?></td>
                        <td class="border px-4 py-2 text-center">$<?= number_format($item['price'], 2) ?></td>
                        <td class="border px-4 py-2 text-center">$<?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                        <td class="border px-4 py-2 text-center">
                            <form method="POST" action="/cart" class="inline">
                                <input type="hidden" name="remove" value="<?= $item['id'] ?>">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4 text-right">
            <p class="text-lg font-semibold">Total: $<?= number_format($total, 2) ?></p>
            <button class="bg-green-600 text-white px-4 py-2 rounded mt-3">Proceed to Checkout</button>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
