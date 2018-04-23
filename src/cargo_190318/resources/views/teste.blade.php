<?php
$arr = ['téste', 'tiãoo', 'morrôía'];
$arr = array_map('utf8_encode', $arr);
echo json_encode($arr);
