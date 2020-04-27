<?php
$sql = <<<EOT
    CREATE TABLE items (
        item_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(80) NOT NULL,
        description TEXT,
        imgpath VARCHAR(30) NOT NULL,
        price SMALLINT NOT NULL,
        allowed_yeargroups SET('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13'),
        enabled BOOLEAN NOT NULL DEFAULT '1',
        CONSTRAINT item_pk PRIMARY KEY (item_id)
    );
EOT;
dosql($sql);