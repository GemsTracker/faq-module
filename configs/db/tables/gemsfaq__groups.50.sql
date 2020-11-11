
CREATE TABLE if not exists gemsfaq__groups (
        gfg_id                      bigint unsigned not null auto_increment,

        gfg_page_id                 bigint unsigned not null references gemsfaq__pages (gfp_id),
        
        gfg_id_order                int not null default 10,
        gfg_group_name              varchar(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,
        gfg_display_method          varchar(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,

        gfg_active                  boolean null default 1,
        
        gfg_changed                 timestamp not null default current_timestamp on update current_timestamp,
        gfg_changed_by              bigint unsigned not null,
        gfg_created                 timestamp not null,
        gfg_created_by              bigint unsigned not null,

        PRIMARY KEY (gfg_id),
        UNIQUE KEY (gfg_page_id, gfg_id_order)
    )
    ENGINE=InnoDB
    auto_increment = 600
    CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
