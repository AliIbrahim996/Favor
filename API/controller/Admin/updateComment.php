<?php
require_once "../Init/CommentInit.php";
header("Access-Control-Allow-Methods: DELETE");
$data = CommentInit::getData();
$comment = CommentInit::getComment();
if (!empty($data->comment_id)) {
    echo $comment->updateComment($data);
} else {
    http_response_code(204);
    echo json_encode(
        array("message" => "check your data", "flag" => 0)
    );
}