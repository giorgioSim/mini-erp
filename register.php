<?php
require 'includes/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 👉 Έλεγχος ονόματος
    if (empty($name)) {
        $errors[] = "Το όνομα είναι υποχρεωτικό.";
    }

    // 👉 Έλεγχος εγκυρότητας email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Μη έγκυρο email.";
    }

    // 👉 Έλεγχος μήκους κωδικού
    if (strlen($password) < 6) {
        $errors[] = "Ο κωδικός πρέπει να έχει τουλάχιστον 6 χαρακτήρες.";
    }

    // 👉 Έλεγχος επιβεβαίωσης κωδικού
    if ($password !== $confirm_password) {
        $errors[] = "Οι κωδικοί δεν ταιριάζουν.";
    }

    if (empty($errors)) {
        // Έλεγχος αν υπάρχει ήδη χρήστης με αυτό το email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Αυτό το email χρησιμοποιείται ήδη.";
        } else {
            // Hash του κωδικού
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 👉 Εισαγωγή χρήστη με όνομα
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            $success = true;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container mx-auto mt-10 max-w-md bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Εγγραφή Χρήστη</h2>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            Η εγγραφή ήταν επιτυχής! <a href="login.php" class="underline">Συνέχισε στην είσοδο</a>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <?php foreach ($errors as $error): ?>
                <div>• <?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold">Όνομα</label>
            <input type="text" name="name" required class="border p-2 w-full rounded" />
        </div>
        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" required class="border p-2 w-full rounded" />
        </div>
        <div>
            <label class="block font-semibold">Κωδικός</label>
            <input type="password" name="password" required class="border p-2 w-full rounded" />
        </div>
        <div>
            <label class="block font-semibold">Επιβεβαίωση Κωδικού</label>
            <input type="password" name="confirm_password" required class="border p-2 w-full rounded" />
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Εγγραφή</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
