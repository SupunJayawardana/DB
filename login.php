<?php
session_start();
include 'db.php';

$error = "";

if (isset($_POST['login'])) {
    // This line forces whatever you type into lowercase (e.g., ADMIN becomes admin)
    $username = strtolower(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE LOWER(username) = '$username'");
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password_reg'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['full_name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>UovT Login</title>
</head>
<body class="bg-[#F3F4F6] flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white p-10 rounded-[2.5rem] shadow-2xl border border-slate-200">
        <div class="text-center mb-8">
            <img src="asset/uovt.png" alt="UovT Logo" class="w-24 h-24 mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-black text-[#1D4A9F] uppercase tracking-tighter">Staff Login</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Internal Portal Access</p>
        </div>

        <?php if($error): ?>
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-center font-bold text-sm border border-red-100">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-6">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Username</label>
                <input type="text" name="username" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                <input type="password" name="password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none">
            </div>
            <button type="submit" name="login" class="w-full py-4 bg-[#1D4A9F] text-white rounded-2xl font-bold text-lg hover:bg-[#153a80] shadow-lg transition-all active:scale-95">
                Sign In
            </button>
        </form>
    </div>

</body>
</html>