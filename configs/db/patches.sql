
-- GEMS VERSION: 67
-- PATCH: Mailable priority levels
ALTER TABLE gemsfaq__items CHANGE gfi_title gfi_title varchar(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' null;

UPDATE gemsfaq__items SET gfi_body = REPLACE(gfi_body, '[b]', '<b>');
UPDATE gemsfaq__items SET gfi_body = REPLACE(gfi_body, '[/b]', '</b>');
UPDATE gemsfaq__items SET gfi_body = REPLACE(gfi_body, '[i]', '<i>');
UPDATE gemsfaq__items SET gfi_body = REPLACE(gfi_body, '[/i]', '</i>');
