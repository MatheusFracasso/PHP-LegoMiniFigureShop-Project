<h1>Register</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="/register">
    <label>Name</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Repeat Password</label><br>
    <input type="password" name="password2" required><br><br>

    <button type="submit">Create account</button>
</form>

<p>
    Already have an account? <a href="/login">Login</a>
</p>
