<?php
session_start();
include 'db.php';

// SECURITY: Kick out users who aren't logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$msg_type = ""; // 'success' or 'error'

if (isset($_POST['update_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // 1. Fetch the user's current hashed password from the database
    $query = "SELECT password_reg FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // 2. Verify if the entered 'Current Password' is correct
        if (password_verify($current_pass, $row['password_reg'])) {
            
            // 3. Check if the new passwords match each other
            if ($new_pass === $confirm_pass) {
                
                // 4. Hash the new password and save it
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET password_reg = '$new_hash' WHERE user_id = '$user_id'";
                
                if (mysqli_query($conn, $update_query)) {
                    $message = "Password successfully updated!";
                    $msg_type = "success";
                } else {
                    $message = "Database error. Could not save password.";
                    $msg_type = "error";
                }
            } else {
                $message = "New passwords do not match!";
                $msg_type = "error";
            }
        } else {
            $message = "Incorrect current password!";
            $msg_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>UovT - Change Password</title>
</head>
<body class="bg-[#F3F4F6] flex items-center justify-center min-h-screen font-sans">

    <div class="w-full max-w-md bg-white p-10 rounded-[2.5rem] shadow-2xl border border-slate-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-[#1D4A9F] uppercase tracking-tighter">Security Settings</h1>
            <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">Update Your Password</p>
        </div>

        <?php if($message): ?>
            <div class="mb-6 p-4 rounded-xl text-center font-bold text-sm border 
                <?= $msg_type === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                <input type="password" name="current_password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none">
            </div>
            
            <hr class="border-slate-100 my-4">

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                <input type="password" name="new_password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                <input type="password" name="confirm_password" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none">
            </div>

            <button type="submit" name="update_password" class="w-full py-4 mt-2 bg-[#1D4A9F] text-white rounded-2xl font-bold text-lg hover:bg-[#153a80] shadow-lg transition-all active:scale-95">
                Save New Password
            </button>
            
            <a href="index.php" class="block text-center w-full py-4 mt-2 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200 transition-all">
                Back to Dashboard
            </a>
        </form>
    </div>

</body>
</html>