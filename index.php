<?php 
// session_start() MUST be the very first line here!
session_start();
include 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_student'])) {
    try {
        $idQuery = mysqli_query($conn, "SELECT MAX(student_id) AS last_id FROM students");
        $idData  = mysqli_fetch_assoc($idQuery);
        $nextId  = $idData['last_id'] + 1;

        $name  = mysqli_real_escape_string($conn, $_POST['full_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $class = mysqli_real_escape_string($conn, $_POST['class']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        $sql = "INSERT INTO students (student_id, full_name, email, class, phone) 
                VALUES ('$nextId', '$name', '$email', '$class', '$phone')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?status=success");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        header("Location: index.php?status=error");
        exit();
    }
}

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : "";

if ($search != "") {
    $query = "SELECT * FROM students 
              WHERE full_name LIKE '%$search%' 
              OR email LIKE '%$search%' 
              OR class LIKE '%$search%' 
              ORDER BY student_id ASC";
} else {
    $query = "SELECT * FROM students ORDER BY student_id ASC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>UovT Administration Portal</title>
</head>
<body class="bg-[#F3F4F6] p-4 md:p-8 font-sans text-slate-900">

<div class="flex items-center justify-end ml-auto gap-4 mt-6 md:mt-0">
    
    <div class="text-right hidden md:block">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Logged in as</p>
        <p class="font-bold text-[#1D4A9F]"><?= htmlspecialchars($_SESSION['user_name']) ?></p>
    </div>
    
    <a href="change_password.php" class="p-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-blue-100 hover:text-blue-600 transition-colors" title="Change Password">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
    </a>

    <a href="index.php?logout=true" class="p-3 bg-slate-100 text-slate-500 rounded-xl hover:bg-red-100 hover:text-red-600 transition-colors" title="Logout">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
    </a>
    
</div>
    <div class="max-w-6xl mx-auto">
        
        <?php if (isset($_GET['status'])): ?>
            <div id="status-toast" class="fixed top-6 right-6 z-50">
                <?php if ($_GET['status'] == 'success'): ?>
                    <div class="bg-emerald-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border-b-4 border-emerald-800 animate-bounce">
                        <span class="font-bold uppercase tracking-tighter italic">✓ Student Enrolled Successfully!</span>
                    </div>
                <?php else: ?>
                    <div class="bg-rose-600 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border-b-4 border-rose-800">
                        <span class="font-bold uppercase tracking-tighter">✕ Database Error: Save Failed</span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <header class="flex flex-col md:flex-row items-center justify-between pb-8 mb-10 border-b-2 border-slate-300/50">
            <div class="flex items-center gap-6">
                <img src="asset/uovt.png" alt="UovT Logo" class="w-20 h-20 object-contain drop-shadow-sm">
                <div>
                    <h1 class="text-3xl font-extrabold text-[#1D4A9F]">University of Vocational Technology</h1>
                    <p class="text-slate-500 font-semibold tracking-wide uppercase text-sm">Student Information Management System</p>
                </div>
            </div>
            
            <button onclick="toggleEnrollmentForm()" class="mt-4 md:mt-0 px-8 py-4 bg-[#8C1B2A] text-white rounded-2xl font-black uppercase tracking-widest hover:bg-[#6e1521] shadow-xl hover:shadow-red-900/20 transition-all active:scale-95">
                New Enrollment
            </button>
        </header>

        <div id="enrollForm" class="hidden mb-12 max-w-2xl mx-auto bg-white p-12 rounded-[2.5rem] shadow-2xl border border-slate-200">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-[#1D4A9F]">Student Enrollment Form</h2>
                <button onclick="toggleEnrollmentForm()" class="text-slate-300 hover:text-red-500 text-3xl">&times;</button>
            </div>

            <form action="index.php" method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="full_name" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none transition-all focus:border-[#1D4A9F]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Official Email</label>
                        <input type="email" name="email" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none transition-all focus:border-[#1D4A9F]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Class Code</label>
                        <input type="text" name="class" required placeholder="e.g., B.Tech-IT-01" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none transition-all focus:border-[#1D4A9F]">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" name="phone" placeholder="+94..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-[#1D4A9F]/10 outline-none transition-all focus:border-[#1D4A9F]">
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" name="add_student" class="flex-1 py-5 bg-[#1D4A9F] text-white rounded-2xl font-bold text-lg hover:shadow-2xl hover:bg-[#153a80] transition-all">
                        Complete Registration
                    </button>
                    <button type="button" onclick="toggleEnrollmentForm()" class="px-8 py-5 bg-slate-100 text-slate-500 rounded-2xl font-bold hover:bg-slate-200">
                        Discard
                    </button>
                </div>
            </form>
        </div>

        <div class="mb-8 flex flex-col md:flex-row gap-4 items-center">
            <form action="index.php" method="GET" class="relative w-full max-w-lg">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Filter by Name, Email, or Class..." 
                       class="w-full pl-14 pr-6 py-5 rounded-3xl border-none shadow-xl focus:ring-4 focus:ring-[#1D4A9F]/20 outline-none">
                <svg class="w-6 h-6 absolute left-5 top-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
            <?php if($search): ?>
                <a href="index.php" class="text-sm font-bold text-[#8C1B2A] bg-red-50 px-4 py-2 rounded-full border border-red-100 italic tracking-tighter">✕ Reset Filter</a>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[11px] uppercase tracking-widest text-slate-400 font-black border-b border-slate-100">
                            <th class="p-6">Index No.</th>
                            <th class="p-6">Full Name</th>
                            <th class="p-6">Email Address</th>
                            <th class="p-6">Assigned Class</th>
                            <th class="p-6">Phone</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-slate-50/50 transition-all group">
                                    <td class="p-6 text-xs font-mono font-bold text-[#8C1B2A]">#<?= str_pad($row['student_id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td class="p-6 font-bold text-slate-800 group-hover:text-[#1D4A9F] transition-colors"><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td class="p-6 text-slate-500 text-sm italic font-medium"><?= htmlspecialchars($row['email']) ?></td>
                                    <td class="p-6">
                                        <span class="inline-flex px-3 py-1 rounded-md text-[10px] font-black bg-blue-50 text-[#1D4A9F] border border-blue-100 uppercase">
                                            <?= htmlspecialchars($row['class']) ?>
                                        </span>
                                    </td>
                                    <td class="p-6 text-slate-500 text-sm"><?= $row['phone'] ?: '—' ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-24 text-center">
                                    <p class="text-slate-300 italic text-lg uppercase tracking-widest font-bold">No Records Found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50/50 p-4 border-t border-slate-100 text-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.3em]">University of Vocational Technology — Internal Administrative Portal</p>
            </div>
        </div>
    </div>

    <script>
    function toggleEnrollmentForm() {
        const form = document.getElementById('enrollForm');
        form.classList.toggle('hidden');
        if(!form.classList.contains('hidden')) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    setTimeout(() => {
        const toast = document.getElementById('status-toast');
        if(toast) {
            toast.style.transition = 'all 0.5s ease';
            toast.style.transform = 'translateX(100px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }
    }, 4000);
    </script>
</body>
</html>