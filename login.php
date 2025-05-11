<?php
require 'includes/config.php';
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 👉 Αναζήτηση χρήστη στη βάση
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 👉 Αν βρέθηκε χρήστης
    if ($user && password_verify($password, $user['password'])) {
        // ✅ Δημιουργία session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // 👉 Μεταφορά στο dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        $errors[] = "Λάθος email ή κωδικός.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mx-auto mt-10 max-w-md bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Είσοδος</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <?php foreach ($errors as $error): ?>
                <div>• <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" required class="border p-2 w-full rounded" />
        </div>
        <div>
            <label class="block font-semibold">Κωδικός</label>
            <input type="password" name="password" required class="border p-2 w-full rounded" />
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Είσοδος</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
