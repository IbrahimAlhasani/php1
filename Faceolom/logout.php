<?php
session_start(); // بدء الجلسة

// تحقق مما إذا كان المستخدم مسجلاً الدخول
if (isset($_SESSION['user_id'])) {
    // إنهاء الجلسة
    session_unset(); // إزالة المتغيرات من الجلسة
    session_destroy(); // تدمير الجلسة
    header('Location: login.php'); // إعادة التوجيه لصفحة تسجيل الدخول
    exit();
} else {
    // إذا لم يكن هناك جلسة نشطة، يمكن إعادة توجيه المستخدم أو عرض رسالة
    header('Location: login.php');
    exit();
}
?>
