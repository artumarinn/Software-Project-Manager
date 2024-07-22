DROP PROCEDURE add_project;

DELIMITER $$

CREATE PROCEDURE add_project(
    IN p_name VARCHAR(100),
    IN p_description VARCHAR(200),
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_actual_end_date DATE,
    IN p_customer_id INT,
    IN p_status_id INT,
    IN p_payment_id INT
)
BEGIN
    INSERT INTO project (
        name,
        description,
        start_date,
        end_date,
        actual_end_date,
        customer_id,
        status_id,
        payment_id
    )
    VALUES (
        p_name,
        p_description,
        p_start_date,
        p_end_date,
        p_actual_end_date,
        p_customer_id,
        p_status_id,
        p_payment_id
    );
END$$

DELIMITER ;
