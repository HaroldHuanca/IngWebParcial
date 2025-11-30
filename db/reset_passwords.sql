-- Actualizar todas las contraseñas a '123456' (encriptado)
-- Hash generado: $2y$12$bfNUo2x9gXFlBESXxUrUFuSWaER7tnzG86EJTpcpJ6xo78Bnh13Aa

UPDATE users SET password_hash = '$2y$12$bfNUo2x9gXFlBESXxUrUFuSWaER7tnzG86EJTpcpJ6xo78Bnh13Aa' WHERE google_id IS NULL AND facebook_id IS NULL;
