-- Cambiamos el rol del usuario a 1 (Administrador)
UPDATE roles_users 
SET role_id = 1 
WHERE user_id = 2;