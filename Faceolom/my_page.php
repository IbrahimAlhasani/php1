<?php
session_start();
include 'connect.php'; // الاتصال بقاعدة البيانات

// التحقق مما إذا كان المستخدم مسجلاً دخوله
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// جلب بيانات المستخدم من قاعدة البيانات
$user_id = $_SESSION['user_id'];

$sql = "SELECT name, number_login, status, current_level, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("فشل إعداد الاستعلام: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['name'];
    $academic_number = $user['number_login'];
    $status = $user['status'];
    $current_level = $user['current_level'];
    $email = $user['email'];
} else {
    echo "لا يمكن العثور على بيانات المستخدم.";
    exit();
}

$stmt->close();

// جلب المنشورات الخاصة بالمستخدم
$sql_posts = "SELECT * FROM posts WHERE user_id = ? ORDER BY id DESC";
$stmt_posts = $conn->prepare($sql_posts);
$stmt_posts->bind_param("i", $user_id);
$stmt_posts->execute();
$result_posts = $stmt_posts->get_result();

$stmt_posts->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الشخصية</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>الصفحة الشخصية</h1>
        <nav>
            <ul>
                <li><a href="homepage.php">الرئيسية</a></li>
                <li><a href="logout.php">تسجيل خروج</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="profile-container">
            <h2>مرحبا، <?php echo htmlspecialchars($username); ?>!</h2>
            <p>الرقم الأكاديمي: <?php echo htmlspecialchars($academic_number); ?></p>
            <p>الحالة: <?php echo htmlspecialchars($status); ?></p>
            <p>المستوى: <?php echo htmlspecialchars($current_level); ?></p>
            <p>البريد الإلكتروني: <?php echo htmlspecialchars($email); ?></p>
        </div>
        
        <!-- إضافة قسم لعرض المنشورات -->
        <div class="posts-container">
            <h3>منشوراتك</h3>
            <ul>
                <?php
                if ($result_posts->num_rows > 0) {
                    while ($post = $result_posts->fetch_assoc()) {
                        echo '<li>' . htmlspecialchars($post['content']) . '</li>';
                    }
                } else {
                    echo '<li>لا توجد منشورات لعرضها.</li>';
                }
                ?>
            </ul>
        </div>
    </main>
</body>
</html>
