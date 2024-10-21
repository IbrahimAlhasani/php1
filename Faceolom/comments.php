<?php
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
    $comment_content = $_POST['comment_content'];
    $author = "مستخدم عشوائي"; // هنا يمكنك استبدالها باسم المستخدم الفعلي

    $query = "INSERT INTO comments (post_id, author, comment, created_at) VALUES ('$post_id', '$author', '$comment_content', NOW())";
    if ($db->query($query)) {
        header('Location: index.php');
    } else {
        echo "خطأ في إضافة التعليق!";
    }
}
?>
