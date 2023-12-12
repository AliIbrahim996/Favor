<?php
require_once "../Init/CommentInit.php";
header("Access-Control-Allow-Methods: POST");
$data = CommentInit::getData();
$comment = CommentInit::getComment();
if (Validation::checkEmptyCommentData($data)) {
    echo $comment->addComment($data);
} else {
    http_response_code(204);
    echo json_encode(array(
        "message" => "check your data!",
        "flag" => 0
    ));
}