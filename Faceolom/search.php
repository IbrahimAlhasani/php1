<?php
session_start();
include 'connect.php'; // تأكد من أن اسم الملف صحيح وموجود

// تحقق من أن الاتصال تم بنجاح
if (!isset($conn)) {
    die("فشل الاتصال بقاعدة البيانات.");
}

$error_message = "";
$search_results = [];

// التحقق من أن النموذج قد تم إرساله
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_query = $_POST['search_query'];

    // استعلام للبحث عن المستخدمين بناءً على الاسم
    $sql = "SELECT * FROM users WHERE username LIKE ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("فشل إعداد الاستعلام: " . $conn->error);
    }

    $like_query = "%" . $search_query . "%"; // يستخدم للبحث الجزئي
    $stmt->bind_param("s", $like_query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // تخزين النتائج في مصفوفة
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    } else {
        $error_message = "لا توجد نتائج مطابقة.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث - منتدى فيسـعلوم</title>
    <link rel="stylesheet" href="style_search.css"> <!-- تأكد من وجود ملف CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- لإضافة أيقونات -->
</head>
<body>
    <header>
        <h1>منتدى فيسـعلوم</h1>
        <nav>
            <ul>
                <li><a href="Homepage.php">الرئيسية</a></li>
                <li><a href="my_page.php">ملفي الشخصي</a></li>
                <li><a href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>البحث عن المستخدمين</h2>
        <form action="search.php" method="post" class="search-form">
            <input type="text" name="search_query" placeholder="أدخل اسم المستخدم..." required>
            <button type="submit"><i class="fas fa-search"></i> بحث</button>
        </form>

        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($search_results)): ?>
            <h3>نتائج البحث:</h3>
            <div class="results-container">
                <?php foreach ($search_results as $user): ?>
                    <div class="user-card">
                        <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                        <p>ID: <?php echo htmlspecialchars($user['id']); ?></p>
                        <p>البريد الإلكتروني: <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>جميع الحقوق محفوظة © 2024 منتدى فيسـعلوم</p>
    </footer>
</body>
</html>
