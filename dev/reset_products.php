<?php

/** @var ArborShop\Database $db */

$db->dosql("
    CREATE TABLE items (
        item_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(80) NOT NULL,
        description TEXT,
        imgpath VARCHAR(30),
        price SMALLINT NOT NULL,
        allowed_yeargroups SET('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13'),
        enabled BOOLEAN NOT NULL DEFAULT '1',
        CONSTRAINT item_pk PRIMARY KEY (item_id)
    );");

$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled) 
        VALUES ("Cheeky Nando\'s voucher", "Enjoy a cheeky Nando\'s, with a &pound;10 voucher", "nandos.png", "500", "8,9,10,11", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Free two hour driving lesson", "More detail to follow...", "l_plates.png", "100", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Free Prom Ticket", "Dance the night away - and it\'s all on us!", "prom_couple.png", "100", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("VIP Sixth Form Take-out", "VIP Sixth Form takeout", "takeaway.png", "75", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("1-month gym membership", "Boost your fitness and reduce stress levels. All for free! For a whole month!", "pumping_weights.png", "75", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("&pound;10 Nando\'s voucher", "Cheeky Nando\'s?  Don\'t mind if I do!", "nandos.png", "75", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Free lunch", "Choose a main meal from the canteen for lunch!", "takeaway.png", "60", "8,9,10,11,12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Lucky bag", "Choose a lucky bag, full of surprises!", "paper_bag.png", "60", "8,9,10,11,12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Free study guide for your subject", "Need a revision guide or text book? Have a voucher for &pound;20 on us", "gift_voucher.png", "50", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Free breakfast", "Choose one item from the canteen for breakfast at break time!", "takeaway.png", "40", "8,9,10,11,12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Spotify for a month", "Music at your fingertips for a whole month.", "spotify.png", "40", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Queue jump pass", "Be first in the queue at lunchtime!", "takeaway.png", "35", "8,9,10,11", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Little Apple treats - Panini", "Select a delicious panini of your choice!", "panini.png", "30", "12,13", "0");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Stationery surprise", "Choose a fun item of stationery!", "stationery.png", "20", "8,9,10,11,12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Ice lolly", "Choose an ice lolly to enjoy after your lunch!", "ice_lolly.png", "20", "8,9,10,11,12,13", "0");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Playlist power!", "Control the music in the Sixth Form Study Area at break and lunch time.", "turntable.png", "20", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Bonus Flexible Study session", "Fancy coming in late or leaving early as a special treat? Enjoy a one hour home study session on us - must be P1 or P5.", "cartoon_house.png", "20", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Sweet treat", "Choose a sweet treat to enjoy after your lunch!", "sweet.png", "15", "8,9,10,11", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Savoury snack", "Choose some crisps", "crisps.png", "15", "8,9,10,11", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Little Apple treats - soft drink", "Enjoy a refreshing beverage after a hard morning\'s study!", "drinks_can.png", "15", "12,13", "1");');
$db->dosql('INSERT INTO items (name, description, imgpath, price, allowed_yeargroups, enabled)
        VALUES ("Revision Flashcards", "Purchase a pack of revision flashcards with every 10 points you earn!", "book_pile.png", "10", "11", "1");');