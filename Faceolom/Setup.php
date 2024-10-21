<?php
$uploadDir = 'uploads';

// التحقق مما إذا كان المجلد موجودًا، وإذا لم يكن، قم بإنشائه
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // إنشاء المجلد مع أذونات 755
    echo "تم إنشاء مجلد uploads بنجاح.";
} else {
    echo "مجلد uploads موجود بالفعل.";
}

// ضبط الأذونات (إذا كنت ترغب في ذلك)
chmod($uploadDir, 0755);
echo "تم ضبط الأذونات على المجلد uploads.";
?>
