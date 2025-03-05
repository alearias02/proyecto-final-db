-- Trigger para la tabla users_tb
CREATE OR REPLACE TRIGGER FIDE_USERS_TB_ID_TRG
BEFORE INSERT ON FIDE_USERS_TB
FOR EACH ROW
BEGIN
    IF :NEW.user_id IS NULL THEN
        SELECT NVL(MAX(user_id), 0) + 1 INTO :NEW.user_id FROM users_tb;
    END IF;
END;
/