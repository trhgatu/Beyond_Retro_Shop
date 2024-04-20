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

function layout_admin($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layout_admin/' . $layoutName . '.php')) {
        require_once (_WEB_PATH_TEMPLATE . '/layout_admin/' . $layoutName . '.php');
    }
}
function layout($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php')) {
        require_once (_WEB_PATH_TEMPLATE . '/layout/' . $layoutName . '.php');
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
    return (!empty($error[$fileName])) ? '<span class= "error" style="color: red">' . reset($error[$fileName]) . '</span>' : null;
}
//Hàm hiển thị dữ liệu cũ
function old($fileName, $oldData, $default = null)
{
    return (!empty($oldData[$fileName])) ? $oldData[$fileName] : $default;
}
//Hàm kiểm tra trạng thái đăng nhập
function isUserLogin()
{
    $checkLogin = false;
    if (getSession('tokenlogin')) {
        $tokenLogin = getSession('tokenlogin');

        $queryToken = oneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin'");
        if (!empty($queryToken)) {
            $checkLogin = true;
        } else {
            removeSession('tokenlogin');
        }
    }
    return $checkLogin;
}
function isAdminLogin()
{
    $checkLogin = false;

    // Kiểm tra sự tồn tại của token đăng nhập trong session
    if (getSession('tokenlogin_admin')) {
        $tokenLogin = getSession('tokenlogin_admin');

        // Kiểm tra tính hợp lệ của token trong cơ sở dữ liệu
        $queryToken = oneRaw("SELECT user_id FROM tokenlogin_admin WHERE token = '$tokenLogin'");
        if (!empty($queryToken)) {
            // Token hợp lệ, đánh dấu đăng nhập thành công
            $checkLogin = true;
        } else {
            // Nếu token không hợp lệ, loại bỏ nó khỏi session
            removeSession('tokenlogin_admin');
        }
    }
    return $checkLogin;
}


