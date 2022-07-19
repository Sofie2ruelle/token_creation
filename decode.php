<?php
// En temps normal on le reçoit via une API
const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMjMsInJvbGUiOlsiUk9MRV9BRE1JTiIsIlJPTEVfVVNFUiJdLCJlbWFpbCI6InVzZXJAZ21haWwuY29tIiwiaWF0IjoxNjU4MTU2MzIxLCJleHAiOjE2NTgxNTYzODF9.skRw3-QjbpUsrIHO_iT7VcpFUWTLfIE98mIFTOFWwQc';

require_once 'includes/config.php';
require_once 'classes/jwt.php'; 

$jwt = new JWT();
// var_dump($jwt->getHeader(TOKEN)); // aller voir dans le navigateur
// var_dump($jwt->check(TOKEN, SECRET));
// var_dump($jwt->isExpired(TOKEN));
var_dump($jwt->isValid(TOKEN));
