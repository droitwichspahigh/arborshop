<?php

dosql("DROP TABLE purchases;", FALSE);
$sql = <<< EOT
    CREATE TABLE purchases (
        purchase_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        arbor_id INT NOT NULL,
        datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        price SMALLINT UNSIGNED NOT NULL,
        item_id INT UNSIGNED NOT NULL,
        collected DATETIME DEFAULT NULL,
        CONSTRAINT purchases_pk PRIMARY KEY (purchase_id)
    );
EOT;
dosql($sql);

dosql("DROP TABLE spent", FALSE);

$sql = <<< EOT
    CREATE TABLE spent (
        spent_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        arbor_id INT NOT NULL,
        spent SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        CONSTRAINT spent_pk PRIMARY KEY (spent_id)
    );
EOT;
dosql($sql);