<?php
class Admin{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function showProfile()
    {
        if (isAdminLogin()) {
            $token = getSession('tokenlogin_admin');
            $query = "SELECT user_id FROM tokenlogin_admin WHERE token = :token";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $user_id = $row['user_id'];

                $query_user = "SELECT * FROM user WHERE id = :user_id";
                $stmt_user = $this->conn->prepare($query_user);
                $stmt_user->bindParam(':user_id', $user_id);
                $stmt_user->execute();

                if ($stmt_user->rowCount() > 0) {
                    return $stmt_user->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        return null;
    }

}
?>