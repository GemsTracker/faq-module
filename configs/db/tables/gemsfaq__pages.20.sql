

CREATE TABLE if not exists gemsfaq__pages (
        gfp_id                     bigint unsigned not null auto_increment PRIMARY KEY,
    
        gfp_action                 varchar(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,
        
        gfp_label                  varchar(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,
        gfp_title                  varchar(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' null,
        
        gfp_menu_position          varchar(40) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' not null,
        gfp_menu_relative          int signed not null default 2,
        gfp_button                 boolean null default 0,
            
        gfp_active                 boolean null default 1,
        
        gfp_changed                timestamp not null default current_timestamp on update current_timestamp,
        gfp_changed_by             bigint unsigned not null,
        gfp_created                timestamp not null,
        gfp_created_by             bigint unsigned not null,

        UNIQUE KEY(gfp_action)
    )
    ENGINE=InnoDB
    auto_increment = 60
    CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
