<?php
    enum PermissionCode: int {
        case Guest = 0;
        case User = 1;
        case Teacher = 2;
        case Moderator = 3;
        case Admin = 4;
    }
?>