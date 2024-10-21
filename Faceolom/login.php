<?php

session_start();
include 'connect.php'; // استدعاء ملف الاتصال

// التحقق من أن النموذج قد تم إرساله
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $number_login = $_POST['number_login'];
    $pass_login = $_POST['pass_login'];

    // استعلام للتحقق من المستخدم وكلمة المرور
    $sql = "SELECT * FROM users WHERE number_login=? AND pass_login=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $number_login, $pass_login); // حماية من SQL Injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // تم العثور على المستخدم
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; // تخزين معرف المستخدم في الجلسة

        header('Location: HomePage.php');
    } else {
        // المستخدم غير موجود
        echo "الرقم الأكاديمي أو كلمة السر غير صحيحة.";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - منتدى فيسـعلوم</title>
    <link rel="stylesheet" href="loginn.css">
</head>
<body>
    <header>
        <h1>منتدى فيسـعلوم</h1>
        <img src="images/image1.jpg" alt="logo" class="logo">
    </header>

    <main>
        <div class="welcome-message">
            <h2>مرحبًا بك في منتدى فيسـعلوم!</h2>
            <p>يرجى تسجيل الدخول للوصول إلى المحتوى الأكاديمي والتواصل مع الآخرين.</p>
        </div>

        <div class="login-form">
            <h2>تسجيل الدخول</h2>
            <form action="login.php" method="post">
                <div class="input-group">
                    <label for="login">الرقم الأكاديمي</label>
                    <input type="number" name="number_login" required>
                </div>
                <div class="input-group">
                    <label for="password">كلمة السر</label>
                    <input type="password" name="pass_login" required>
                </div>
                <button type="submit" name="submit">تسجيل الدخول</button>
            </form>
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <footer>
            <p>برمجة المهندس زكريا أغا والمهندس رسام طلعت © 2024 منتدى فيسـعلوم</p>
        </footer>
    </main>
</body>
</html>