<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 text-center">Your Wishlist</h1>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <?php if (empty($wishlistItems)): ?>
        <p class="text-center text-gray-500">Your wishlist is empty.</p>
    <?php else: ?>
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($wishlistItems as $item): ?>
                <li class="border p-4 rounded shadow bg-white">
                    <h2 class="text-xl font-bold"><?= htmlspecialchars($item['title']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($item['author']) ?></p>
                    <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-32 h-48 object-cover mt-2">
                    <p class="text-lg font-semibold mt-2">$<?= number_format($item['price'], 2) ?></p>

                    <!-- Remove from Wishlist Form -->
                    <form action="/wishlist/remove" method="POST" class="inline">
                        <input type="hidden" name="book_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="remove" class="bg-red-500 text-white px-3 py-2 rounded mt-3 inline-block">Remove from Wishlist</button>
                    </form>

                    <!-- Add to Cart Form -->
                    <form action="/" method="POST" class="inline">
                        <input type="hidden" name="book_id" value="<?= $item['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" class="border p-1 rounded w-16">
                        <button type="submit" name="add_to_cart" class="bg-green-600 text-white px-3 py-2 rounded mt-3 inline-block">Add to Cart</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>