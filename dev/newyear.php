<?php

dosql("DROP TABLE purchases;", FALSE);

$sql = <<< EOT
    CREATE TABLE purchases (
        purchase_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        upn VARCHAR(13) NOT NULL,
        datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        price SMALLINT UNSIGNED NOT NULL,
        item_id INT UNSIGNED NOT NULL,
        collected DATETIME DEFAULT NULL,
        CONSTRAINT purchases_pk PRIMARY KEY (purchase_id)
    );
EOT;

dosql("DROP TABLE spent", FALSE);

dosql($sql);
dosql("CREATE TABLE spent (upn VARCHAR(13) NOT NULL, spent SMALLINT UNSIGNED NOT NULL DEFAULT 0);");