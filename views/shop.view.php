<?php include 'partials/head.php'; ?>
<?php include 'partials/nav.php'; ?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 text-center">Bookstore</h1>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <form method="GET" action="/shop" class="mb-6 flex gap-2">
        <input type="text" name="search" class="border p-2 rounded w-full" 
               placeholder="Search books..." value="<?= htmlspecialchars($search) ?>">
        
        <select name="category" class="border p-2 rounded">
            <option value="">All Categories</option>
            <option value="fictional" <?= $category === 'fictional' ? 'selected' : '' ?>>Fiction</option>
            <option value="non-fictional" <?= $category === 'non-fictional' ? 'selected' : '' ?>>Non-Fiction</option>
        </select>
        
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
    </form>

    <?php if (empty($books)): ?>
        <p class="text-center text-gray-500">No books found matching your search.</p>
    <?php else: ?>
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($books as $book): ?>
                <li class="border p-4 rounded shadow bg-white">
                    <h2 class="text-xl font-bold"><?= htmlspecialchars($book['title']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($book['author']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($book['category']) ?></p>
                    <img src="images/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-32 h-48 object-cover mt-2">
                    <p class="text-lg font-semibold mt-2">$<?= number_format($book['price'], 2) ?></p>
                    <form action="/shop" method="POST">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <input type="number" name="quantity" value="1" min="1" class="border p-1 rounded w-16">
                        <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded mt-3 inline-block">Add to Cart</button>
                    </form>

                    <a href="wishlist.php?add=<?= $book['id'] ?>" class="bg-yellow-500 text-white px-3 py-2 rounded mt-3 inline-block">Wishlist</a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <?php if ($page > 1): ?>
                <a href="?search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&page=<?= $page - 1 ?>" class="px-4 py-2 bg-gray-300 rounded mx-1">Previous</a>
            <?php endif; ?>
            
            <span class="px-4 py-2 bg-gray-200 rounded"><?= $page ?> / <?= $totalPages ?></span>
            
            <?php if ($page < $totalPages): ?>
                <a href="?search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>&page=<?= $page + 1 ?>" class="px-4 py-2 bg-gray-300 rounded mx-1">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>
