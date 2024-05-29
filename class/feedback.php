<?php
class Feedback
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function sendFeedback($dataSend)
    {
        $filterAll = filter();
        $dataSend = [
            'fullname' => $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone_number' => $filterAll['phone_number'],
            'note' => $filterAll['note'],
            'sent_at' => date('Y-m-d H:i:s'),
        ];
        $sendStatus = insert('feedback', $dataSend);
        if ($sendStatus) {
            setFlashData('msg', 'Gửi thư thành công.');
            setFlashData('msg_type', 'success');
            redirect('?module=feedback&action=list');
        } else {
            setFlashData('msg', 'Gửi thư thất bại, vui lòng thử lại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=feedback&action=add');
    }
    public function list()
    {
        $feedbacks = array();
        $query = "SELECT * FROM feedback";
        $result = $this->conn->query($query);
        if ($result && $result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $feedbacks[] = $row;
            }
        }
        return $feedbacks;
    }
    public function delete()
    {
        $filterAll = filter();
        if (!empty($filterAll['id'])) {
            $feedbackId = $filterAll['id'];
            $feedbackDetail = getRaw("SELECT * FROM feedback WHERE id = $feedbackId");
            if ($feedbackDetail > 0) {
                $deleteFeedback = delete('feedback', "id = $feedbackId");
                if ($deleteFeedback) {
                    setFlashData('msg', 'Xóa thư thành công.');
                    setFlashData('msg_type', 'success');
                }
            } else {
                setFlashData('msg', 'Thư không tồn tại trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Liên kết không tồn tại.');
            setFlashData('msg_type', 'danger');
        }
        redirect('?module=feedback&action=list');
    }

}