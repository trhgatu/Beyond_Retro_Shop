<?php
$data = [
    'pageTitle' => 'Tài khoản',
];
require_once '../class/user.php';
$user = new User($conn);
$listUsers = $user->listUser();
?>
<tbody>

    <?php
    layout('header', $data);
    if (!empty($listUsers)):
        $count = 0; //STT
        foreach ($listUsers as $item):
            $count++;
            ?>
            <tr>

                <td>
                    <?php
                    $role_id = $item['role_id'];
                    $role_name = "";

                    $stmt = $conn->prepare("SELECT name FROM role WHERE id = :role_id");
                    $stmt->bindParam(':role_id', $role_id);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $role_name = $result['name'];
                    }
                    echo $role_name;
                    ?>
                </td>
                <td>
                    <img src="../images/avatar/<?php echo $item['avatar'] ?>" style="max-width: 170px;">
                </td>
                <td>
                    <?php echo $item['fullname'] ?>
                </td>
                <td>
                    <?php echo $item['email'] ?>
                </td>
                <td>
                    <?php echo $item['phone_number'] ?>
                </td>

                <td><a href="<?php echo _WEB_HOST ?>?module=users&action=edit&id=<?php echo $item['id'] ?>"
                        class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                </td>

            </tr>
            <?php
        endforeach;
    else:
        ?>
        <tr>
            <td colspan="7">
                <div class="alert alert-danger text-center">Không có người dùng nào</div>


        </tr>
        <?php
    endif;

    ?>


</tbody>