<?php
    unlink('../../data/log.txt');
    header('Location: '.'../../index.php?pages=admin/LogFile');
?>