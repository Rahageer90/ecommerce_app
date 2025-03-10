<?php include 'partials/head.php'; ?>
<?php include 'partials/nav.php'; ?>

<header class="bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 text-center">Login</h1>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
    <form method="POST" action="/login" class="max-w-md mx-auto">
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password</label>
            <input type="password" name="password" id="password" class="border p-2 rounded w-full" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Login</button>
    </form>
</main>

<?php include 'partials/footer.php'; ?>