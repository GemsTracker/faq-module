

CREATE TABLE if not exists gemsfaq__items (
        gfi_id                      bigint unsigned not null auto_increment,


        gfi_group_id                bigint unsigned not null references gemsfaq__groups (gfg_id),
        
        gfi_id_order                int not null default 10,
        gfi_iso_langs               varchar(40) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null default 'en',
        gfi_display_method          varchar(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,
        gfi_title                   varchar(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' null,
        gfi_body                    text CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' null,

        gfi_active                  boolean null default 1,

        gfi_changed                 timestamp not null default current_timestamp on update current_timestamp,
        gfi_changed_by              bigint unsigned not null,
        gfi_created                 timestamp not null,
        gfi_created_by              bigint unsigned not null,

        PRIMARY KEY (gfi_id),
        UNIQUE KEY(gfi_group_id, gfi_id_order)
    )
    ENGINE=InnoDB
    auto_increment = 8000
    CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

