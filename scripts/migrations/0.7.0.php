<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md.
 */

return function($conn, $em) {

    $name = $this->name();

    array_map([$conn, 'exec'], [
        "ALTER TABLE {$name}_category DROP FOREIGN KEY FK_53A4EDAA9B648F1F",
        "DROP INDEX IDX_53A4EDAA9B648F1F ON {$name}_category",
        "ALTER TABLE {$name}_category CHANGE modifydate updateDate DATETIME DEFAULT NULL, CHANGE modifyuserid updateUserId INT DEFAULT NULL",
        "ALTER TABLE {$name}_category ADD CONSTRAINT FK_53A4EDAA894646E4 FOREIGN KEY (updateUserId) REFERENCES core_user (id) ON DELETE SET NULL",
        "CREATE INDEX IDX_53A4EDAA894646E4 ON {$name}_category (updateUserId)",
    ]);

};