@php
    if(!isset($offset)) $offset = 0;
    if(!isset($limit)) $limit = 1000;
    if(!isset($where)) $where = null;
    if(isset($checked) && !isset($checked['typecheck'])) $checked['typecheck'] = 'selected';

    $query = DefRequestController::listReturn($table, $offset, $limit, $where);
    foreach($query as $list):
        if(isset($checked) && $list->$key == $checked['value']) $list->checked = $checked['typecheck'];
        else $list->checked = '';
        eval('eval("echo \'' . addslashes($slot) . '\';");');
    endforeach;
@endphp
