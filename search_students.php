<?php
session_start();

include "db.php";

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$start = ($page - 1) * $limit;

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// ==========================================
// 📊 QUICK STATS DATA FETCH (ডাইনামিক কাউন্ট)
// ==========================================
// ১. টোটাল স্টুডেন্ট কাউন্ট
$totalQuery = mysqli_query($conn, "
SELECT COUNT(*) AS total 
FROM persons 
WHERE user_id='$user_id' 
AND name LIKE '%$search%'
");
$totalRow = mysqli_fetch_assoc($totalQuery);
$total_students = $totalRow['total'];

// ২. মেল স্টুডেন্ট কাউন্ট
$maleQuery = mysqli_query($conn, "
SELECT COUNT(*) AS total_male 
FROM persons 
WHERE user_id='$user_id' 
AND name LIKE '%$search%' 
AND gender='Male'
");
$maleRow = mysqli_fetch_assoc($maleQuery);
$total_male = $maleRow['total_male'];

// ৩. ফিমেল স্টুডেন্ট কাউন্ট
$femaleQuery = mysqli_query($conn, "
SELECT COUNT(*) AS total_female 
FROM persons 
WHERE user_id='$user_id' 
AND name LIKE '%$search%' 
AND gender='Female'
");
$femaleRow = mysqli_fetch_assoc($femaleQuery);
$total_female = $femaleRow['total_female'];


// ==========================================
// 🎨 STATS CARDS UI DESIGN
// ==========================================
echo "<div style='display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap;'>";

// কার্ড ১: টোটাল স্টুডেন্ট
echo "
<div style='flex: 1; min-width: 150px; background: linear-gradient(135deg, #008b8b, #005f5f); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;'>
    <h4 style='margin: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;'>Total Students</h4>
    <p style='margin: 10px 0 0 0; font-size: 28px; font-weight: bold;'>$total_students</p>
</div>";

// কার্ড ২: ছাত্র (Male)
echo "
<div style='flex: 1; min-width: 150px; background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;'>
    <h4 style='margin: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;'>👨‍🎓 Male Students</h4>
    <p style='margin: 10px 0 0 0; font-size: 28px; font-weight: bold;'>$total_male</p>
</div>";

// কার্ড ৩: ছাত্রী (Female)
echo "
<div style='flex: 1; min-width: 150px; background: linear-gradient(135deg, #b06ab3, #4568dc); color: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;'>
    <h4 style='margin: 0; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;'>👩‍🎓 Female Students</h4>
    <p style='margin: 10px 0 0 0; font-size: 28px; font-weight: bold;'>$total_female</p>
</div>";

echo "</div>";


// =========================
// Student Fetch
// =========================

$sql = "SELECT *
        FROM persons
        WHERE user_id='$user_id'
        AND name LIKE '%$search%'
        ORDER BY id ASC
        LIMIT $start,$limit";

$result = mysqli_query($conn, $sql);

// =========================
// Table
// =========================

echo "<table border='1' width='100%' cellpadding='8' cellspacing='0'>";

echo "<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Department</th>
<th>Semester</th>
<th>Gender</th>
<th>Action</th>
</tr>";

while ($row = mysqli_fetch_assoc($result)) {

    if (!empty($row['image']) && file_exists("uploads/" . $row['image'])) {
        $image = "uploads/" . $row['image'];
    } else {
        $image = "uploads/default.png";
    }

    echo "<tr>";

    echo "<td>".$row['id']."</td>";

    echo "<td>
            <img src='".$image."' width='60' height='60' style='border-radius:50%; object-fit:cover;'>
          </td>";

    echo "<td>".$row['name']."</td>";

    echo "<td>".$row['email']."</td>";

    echo "<td>".$row['phone']."</td>";

    echo "<td>".$row['department']."</td>";

    echo "<td>".$row['semester']."</td>";

    echo "<td>".$row['gender']."</td>";

    // বাটন ক্লিকের JS ফাংশনে আইডিটিকে নিরাপদ সিঙ্গেল কোটের মধ্যে রাখা হয়েছে
    echo "<td>
        <a href='edit_student.php?id=".$row['id']."'
        style='display:inline-block;padding:6px 12px;background:#008b8b;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;margin-right:5px;'>
        Edit
        </a>

        <a href='delete_student.php?id=".$row['id']."'
        style='display:inline-block;padding:6px 12px;background:#dc3545;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;margin-right:5px;'
        onclick='return confirm(\"Are you sure?\")'>
        Delete
        </a>

        <button onclick=\"openDownloadModal('".$row['id']."')\"
        style='padding:6px 12px;background:#198754;color:#fff;border:none;border-radius:5px;cursor:pointer;font-weight:bold;'>
        📥 Download
        </button>

        <a href='id_card.php?id=".$row['id']."' 
        target='_blank' style='display:inline-block;padding:6px 12px;background:#fd7e14;color:#fff;text-decoration:none;border-radius:5px;font-weight:bold;'>
        ID Card</a>
        
    </td>";

    echo "</tr>";
}

echo "</table>";


// =========================
// Pagination
// =========================

$totalPages = ceil($total_students / $limit);

echo "<br><div style='margin-top:20px;'>";

for ($i = 1; $i <= $totalPages; $i++) {
    echo "<button onclick='loadData($i)'
    style='padding:8px 12px;margin:3px;cursor:pointer;'>
    $i
    </button>";
}

echo "</div>";
?>