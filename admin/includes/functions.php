<!-- Các hàm chung của dự án -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layouts($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layout_admin/' . $layoutName . '.php')) {
        require_once(_WEB_PATH_TEMPLATE . '/layout_admin/' . $layoutName . '.php');
    }
}
function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'tulavip1234@gmail.com';                     //SMTP username
        $mail->Password = 'hpxb cvcb eiue lwey';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('trananhtu1112003@gmail.com', 'Anh Tú');
        $mail->addAddress($to);     //Add a recipient
        //Content
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $content;
        //PHPMailer SSL Certificate verifyfailed
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        );
        $sendMail = $mail->send();
        if ($sendMail) {
            return $sendMail;
        }
        //echo 'Gửi thành công';
    } catch (Exception $e) {
        echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
    }
}
//Kiểm tra phương thức GET
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}
//Kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}
//Hàm filter dữ liệu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        //Xử lý dữ liệu trước khi hiển thị ra
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

            }
        }
    }
    return $filterArr;
}
//Hàm kiểm tra email
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
//Hàm kiểm tra số nguyên
function isNumberInt($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumber;
}
//Hàm kiểm tra số thực
function isNumberFloat($number)
{
    $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}
//Hàm kiểm tra số điện thoại
function isPhone($phone_number)
{
    $checkZero = false;
    //DK1: Kí tự đầu là số 0
    if ($phone_number[0] == '0') {
        $checkZero = true;
        $phone_number = substr($phone_number, 1);
    }
    //DK2: sau đó là 9 số
    $checkNumber = false;
    if (isNumberInt($phone_number) && strlen($phone_number) == 9) {
        $checkNumber = true;
    }
    if ($checkZero && $checkNumber) {
        return true;
    }
    return false;
}
//Báo lỗi
function getMSG($msg, $type = 'success')
{
    echo '<div class = "alert alert-' . $type . '">';
    echo $msg;
    echo '</div>';
}
//Hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("Location: $path");
    exit;
}
// Hàm thông báo lỗi
function form_error($fileName, $beforeHtml = '', $afterHtml = '', $error)
{
    return (!empty($error[$fileName])) ? '<span class= "error">' . reset($error[$fileName]) . '</span>' : null;
}
//Hàm hiển thị dữ liệu cũ
function old($fileName, $oldData, $default = null)
{
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}
//Hàm kiểm tra trạng thái đăng nhập
function isLogin()
{
    $checkLogin = false;
    //Kiểm tra trạng thái đăng nhập
    if (getSession('tokenlogin')) {
        $tokenLogin = getSession('tokenlogin');
        //Kiểm tra token giống trong database
        $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('tokenlogin');
        }
    }
    return $checkLogin;
}
function uploadThumbnail($file) {
    $targetDir = "../images/";
    $targetFile = $targetDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
    $newFileName = uniqid() . '.' . $imageFileType; // Tên tập tin mới

    $uploadOk = 1;
    // Kiểm tra xem tập tin có phải là ảnh thực sự hay không
    if(isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }
    }
    // Kiểm tra kích thước tập tin
    if ($file["size"] > 500000) {
        $uploadOk = 0;
    }
    // Kiểm tra định dạng tập tin
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }
    // Kiểm tra nếu $uploadOk bằng 0
    if ($uploadOk == 0) {
        return false;
    // Nếu mọi thứ đều ok, thử tải lên tập tin
    } else {
        if (move_uploaded_file($file["tmp_name"], $targetDir . $newFileName)) {
            return $newFileName;
        } else {
            return false;
        }
    }
}