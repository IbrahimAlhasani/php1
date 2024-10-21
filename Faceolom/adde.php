<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '',"science_forum");

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// بدء جلسة المستخدم
session_start();
$user_id = $_SESSION['user_id'] ?? null;

// إذا لم يكن المستخدم مسجلاً الدخول، قم بإعادة التوجيه
if (!$user_id) {
    header("Location: login.php");
    exit;
}

// جلب الإشعارات
$notifications = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
if ($notifications === false) {
    die("فشل إعداد الاستعلام: " . $conn->error);
}

$notifications->bind_param("i", $user_id);
if (!$notifications->execute()) {
    die("فشل تنفيذ الاستعلام: " . $notifications->error);
}
$result = $notifications->get_result();

// تحديث حالة القراءة
if (isset($_GET['mark_read'])) {
    $notification_id = (int)$_GET['mark_read'];
    $update_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    
    if ($update_stmt === false) {
        die("فشل إعداد الاستعلام: " . $conn->error);
    }
    
    $update_stmt->bind_param("ii", $notification_id, $user_id);
    if (!$update_stmt->execute()) {
        die("فشل تنفيذ الاستعلام: " . $update_stmt->error);
    }
}

// عدد الإشعارات غير المقروءة
$count_unread = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND is_read = 0");
if ($count_unread === false) {
    die("فشل إعداد الاستعلام: " . $conn->error);
}

$count_unread->bind_param("i", $user_id);
if (!$count_unread->execute()) {
    die("فشل تنفيذ الاستعلام: " . $count_unread->error);
}
$count_result = $count_unread->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإشعارات</title>
    <link rel="stylesheet" href="adde.css"> <!-- ربط ملف CSS للتنسيق -->
   
</head>
<body dir="rtl">
    <header>
        <h1>الإشعارات (<?php echo $count_result['unread_count']; ?> غير مقروءة)</h1>
        <nav>
            <ul>
                <li><a href="my_page.php">الصفحة الشخصية</a></li>
                <li><a href="notifications.php">الإشعارات (<?php echo $count_result['unread_count']; ?>)</a></li>
                <li><a href="search.php">البحث</a></li>
                <li><a href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <ul>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($notification = $result->fetch_assoc()): ?>
                    <li class="notification" style="<?php echo $notification['is_read'] ? 'color: gray;' : 'font-weight: bold;'; ?>">
                        <?php echo htmlspecialchars($notification['message']); ?>
                        <a href="notifications.php?mark_read=<?php echo $notification['id']; ?>">تمت القراءة</a>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>لا توجد إشعارات حالياً.</li>
            <?php endif; ?>
        </ul>
    </main>
</body>
</html>
