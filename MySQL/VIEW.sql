CREATE VIEW project_summary AS
SELECT
    p.project_id,
    p.name AS project_name,
    p.description AS project_description,
    p.start_date,
    p.end_date,
    p.actual_end_date,
    c.full_name AS customer_name,
    s.description AS project_status,
    COUNT(r.requirement_id) AS total_requirements,
    SUM(CASE WHEN r.status_id = s.status_id THEN 1 ELSE 0 END) AS completed_requirements
FROM
    project p
JOIN
    customer c ON p.customer_id = c.customer_id
JOIN
    status s ON p.status_id = s.status_id
LEFT JOIN
    requirements r ON p.project_id = r.project_id
GROUP BY
    p.project_id, p.name, p.description, p.start_date, p.end_date, p.actual_end_date, c.full_name, s.description;

SELECT * FROM project_summary;
