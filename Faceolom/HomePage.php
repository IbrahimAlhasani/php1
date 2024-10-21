<?php
session_start();

// عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // جلب معرف المستخدم من الجلسة

// إضافة منشور جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_content'])) {
    $post_content = $conn->real_escape_string($_POST['post_content']);
    $image = null;

    // معالجة رفع الصورة
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = 'uploads/';
        $image_name = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $image_name;
            } else {
                die("خطأ في رفع الصورة: " . $_FILES['image']['error']);
            }
        } else {
            die("نوع الملف غير مدعوم.");
        }
    }

    // إدخال المنشور إلى قاعدة البيانات
    if ($conn->query("INSERT INTO posts (content, likes, dislikes, user_id, image) VALUES ('$post_content', 0, 0, $user_id, '$image')") === FALSE) {
        die("خطأ: " . $conn->error);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// إضافة تعليق
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_content'])) {
    $post_id = (int)$_POST['post_id'];
    $comment_content = $conn->real_escape_string($_POST['comment_content']);
    
    if ($conn->query("INSERT INTO comments (post_id, user_id, content) VALUES ($post_id, $user_id, '$comment_content')") === FALSE) {
        die("خطأ: " . $conn->error);
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// معالجة تفاعل الإعجاب وعدم الإعجاب
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reaction'])) {
    $post_id = (int)$_POST['post_id'];
    $reaction = $conn->real_escape_string($_POST['reaction']);

    // تحقق إذا كان المستخدم قد تفاعل مسبقًا
    $checkReaction = $conn->query("SELECT reaction FROM user_reactions WHERE user_id = $user_id AND post_id = $post_id");

    if ($checkReaction->num_rows > 0) {
        // إذا كان هناك تفاعل مسبق
        $existingReaction = $checkReaction->fetch_assoc();

        if ($existingReaction['reaction'] == $reaction) {
            // إذا كانت نفس التفاعل، قم بإزالته
            $conn->query("DELETE FROM user_reactions WHERE user_id = $user_id AND post_id = $post_id");
            if ($reaction === 'like') {
                $conn->query("UPDATE posts SET likes = likes - 1 WHERE id = $post_id");
            } else {
                $conn->query("UPDATE posts SET dislikes = dislikes - 1 WHERE id = $post_id");
            }
        } else {
            // إذا كان تفاعل مختلف، قم بتحديثه
            $conn->query("UPDATE user_reactions SET reaction = '$reaction' WHERE user_id = $user_id AND post_id = $post_id");
            if ($reaction === 'like') {
                $conn->query("UPDATE posts SET likes = likes + 1, dislikes = dislikes - 1 WHERE id = $post_id");
            } else {
                $conn->query("UPDATE posts SET dislikes = dislikes + 1, likes = likes - 1 WHERE id = $post_id");
            }
        }
    } else {
        // إذا لم يكن هناك تفاعل مسبق، قم بإضافته
        $conn->query("INSERT INTO user_reactions (user_id, post_id, reaction) VALUES ($user_id, $post_id, '$reaction')");
        if ($reaction === 'like') {
            $conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $post_id");
        } else {
            $conn->query("UPDATE posts SET dislikes = dislikes + 1 WHERE id = $post_id");
        }
    }

    // استرجاع الأعداد المحدثة
    $result = $conn->query("SELECT likes, dislikes FROM posts WHERE id = $post_id");
    $data = $result->fetch_assoc();
    echo json_encode($data);
    exit();
}

// جلب جميع المنشورات من قاعدة البيانات
$posts = $conn->query("SELECT * FROM posts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصفحة الرئيسية</title>
    <link rel="stylesheet" href="style_homepage.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body dir="rtl">
    <header>
        <div class="HeadHome">
            <nav>
                <ul>
                    <li><a href="my_page.php">الصفحة الشخصية</a></li>
                    <li><a href="adde.php">الأشعارات</a></li>
                    <li><a href="search.php">البحث</a></li>
                    <li><a href="logout.php">تسجيل الخروج</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div class="add-post">
                <h2>أضف منشوراً جديداً</h2>
                <form method="POST" enctype="multipart/form-data">
                    <textarea name="post_content" placeholder="اكتب شيئاً..." required></textarea>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit">نشر</button>
                </form>
            </div>

            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post">
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <?php if ($post['image']): ?>
                        <img src="<?php echo htmlspecialchars('uploads/' . $post['image']); ?>" alt="صورة المنشور" style="max-width: 100%; height: auto;">
                    <?php endif; ?>

                    <div class="reactions" data-post-id="<?php echo $post['id']; ?>">
                        <button class="like-button" data-reaction="like">👍 إعجاب (<?php echo $post['likes']; ?>)</button>
                        <button class="dislike-button" data-reaction="dislike">👎 عدم الإعجاب (<?php echo $post['dislikes']; ?>)</button>
                    </div>

                    <div class="comment-form">
                        <form method="POST">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <textarea name="comment_content" placeholder="أضف تعليقاً..." required></textarea>
                            <button type="submit">إضافة تعليق</button>
                        </form>
                    </div>

                    <?php
                    $post_id = $post['id'];
                    $comments = $conn->query("SELECT * FROM comments WHERE post_id = $post_id ORDER BY id DESC");
                    if ($comments->num_rows > 0): ?>
                        <div class="comments">
                            <h4>التعليقات:</h4>
                            <?php while ($comment = $comments->fetch_assoc()): ?>
                                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <script>
    $(document).ready(function() {
        $('.like-button, .dislike-button').click(function(event) {
            event.preventDefault(); 
            
            var post_id = $(this).closest('.reactions').data('post-id');
            var reaction = $(this).data('reaction');
            
            $.post('', { post_id: post_id, reaction: reaction }, function(response) {
                var data = JSON.parse(response);
                $('.reactions[data-post-id="' + post_id + '"] .like-button').text('👍 إعجاب (' + data.likes + ')');
                $('.reactions[data-post-id="' + post_id + '"] .dislike-button').text('👎 عدم الإعجاب (' + data.dislikes + ')');
            });
        });
    });
    </script>
</body>
</html>
