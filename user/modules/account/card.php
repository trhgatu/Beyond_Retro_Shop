<div class="card">
    <div class="card-body">
        <h4 class="align-items-center text-center" style="padding-top: 10px; padding-bottom: 20px;">Tài
            khoản của tôi</h4>
        <div class="d-flex flex-column align-items-center text-center">
            <img src="../images/avatar/<?php echo $profileUser['avatar'] ?>" alt="Admin"
                class="rounded-circle p-1 bg-primary" width="110">
            <div class="mt-3">
                <h4>
                    <?php echo $profileUser['fullname'] ?>
                </h4>
                <p class="text-muted font-size-sm">
                    Số điện thoại: <?php echo $profileUser['phone_number'] ?>
                </p>
            </div>
        </div>
        <hr class="my-4">
        <ul class="list-group list-group-flush">
            <li class="list-group-item align-items-center flex-wrap">
                <h6 class="mb-0">
                    <a href="?module=account&action=confirmpassword">
                        <img src="../img/password.png" style="width: 25px; height: 25px">
                        Đổi mật khẩu
                    </a>
                </h6>
            </li>
            <li class="list-group-item  align-items-center flex-wrap">

                <h6 class="mb-0">
                    <a href="?module=orders&action=list">
                        <img src="https://down-vn.img.susercontent.com/file/f0049e9df4e536bc3e7f140d071e9078"
                            style="width: 25px; height: 25px">
                        Đơn mua
                    </a>
                </h6>
            </li>
            <li class="list-group-item  align-items-center flex-wrap">
                <?php
                $userId = getSession('user_id');
                $listAddress = oneRaw("SELECT * FROM addresses WHERE user_id = $userId");
                ?>
                <h6 class="mb-0">
                    <a href="?module=address&action=list">
                        <img src="../img/location.png" style="width: 25px; height: 25px">
                        Địa chỉ
                    </a>
                </h6>
            </li>
            <li class="list-group-item  align-items-center flex-wrap">
                <h6 class="mb-0">
                    <a href="?module=authen&action=logout" class="btn btn-primary btn-danger">

                        Đăng xuất
                    </a>
                </h6>
            </li>
        </ul>
    </div>
</div>