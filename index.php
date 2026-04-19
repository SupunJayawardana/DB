<?php 
include 'db.php'; // This MUST be the first thing in the file
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php
    $result = $conn->query("SELECT * FROM students ORDER BY student_id ASC");
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '
            <div class="p-6 rounded-3xl bg-slate-800/40 border border-slate-700/50 hover:border-blue-500/50 transition-all">
                <span class="text-xs font-mono text-blue-400">'.$row['full_name'].'</span>
                <h3 class="text-lg font-semibold mb-2">'.$row['email'].'</h3>
            </div>';
        }
    } else {
        echo '<p class="text-slate-500 italic">No lectures planned yet.</p>';
    }
    ?>
</div>