USE software_project_manager;

DELIMITER $$
CREATE TRIGGER update_project_status
AFTER UPDATE ON requirements
FOR EACH ROW
BEGIN
    DECLARE completed_requirements INT;
    DECLARE total_requirements INT;
    DECLARE completed_status_id INT;

    -- obtener el ID del estado Completado
    SELECT status_id INTO completed_status_id FROM status WHERE description = 'Completado';

    -- total de requisitos del proyecto
    SELECT COUNT(*) INTO total_requirements FROM requirements WHERE project_id = NEW.project_id;

    -- requisitos completados del proyecto
    SELECT COUNT(*) INTO completed_requirements FROM requirements WHERE project_id = NEW.project_id AND status_id = completed_status_id;

    IF completed_requirements = total_requirements THEN
        UPDATE project SET status_id = completed_status_id WHERE project_id = NEW.project_id;
    END IF;
END$$

DELIMITER ;
