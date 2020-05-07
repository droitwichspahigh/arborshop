<?php

/**
 * From create_db.php 
 * @var ArborShop\Database $db
 */

$db->dosql("DROP TABLE purchases;", FALSE);

$db->dosql("CREATE TABLE purchases (
        purchase_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        arbor_id INT NOT NULL,
        datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        price SMALLINT UNSIGNED NOT NULL,
        item_id INT UNSIGNED NOT NULL,
        collected DATETIME DEFAULT NULL,
        CONSTRAINT purchases_pk PRIMARY KEY (purchase_id)
    );");

$db->dosql("DROP TABLE spent", FALSE);

$db->dosql("CREATE TABLE spent (
        spent_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        arbor_id INT NOT NULL,
        spent SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        CONSTRAINT spent_pk PRIMARY KEY (spent_id)
    );");

$db->dosql("CREATE TABLE pointscache (
        pointscache_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        arbor_id INT NOT NULL,
        ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        arborPoints SMALLINT NOT NULL DEFAULT 0,
        CONSTRAINT pointscache_id PRIMARY KEY (pointscache_id)
    );");