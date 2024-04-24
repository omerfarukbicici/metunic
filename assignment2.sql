
CREATE TABLE users (
    id int,
    name varchar(255),
    user_type varchar(255)
);

CREATE TABLE traffic (
    user_id int,
    visited_on date,
    time_spent int
);

commit;

INSERT INTO users (id, name, user_type)
VALUES 
('1', 'Matt', 'user'),
('2', 'John', 'user'),
('3', 'Louis', 'admin');

INSERT INTO traffic (user_id, visited_on, time_spent)
VALUES 
('1', '2019-05-01', '15'),
('2', '2019-05-02', '20'),
('2', '2019-05-03', '10');

select * from world.users;
select * from world.traffic;

SELECT 
    visited_on,
    ROUND(AVG(time_spent) OVER (ORDER BY visited_on ROWS BETWEEN 2 PRECEDING AND CURRENT ROW), 4) AS avg_time_spent
FROM 
    traffic
WHERE 
    user_id IN (SELECT id FROM users WHERE user_type = 'user')
ORDER BY 
    visited_on;