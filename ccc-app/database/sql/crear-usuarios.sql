use mydb;

#Borramos todos los usuarios que existieran
DELETE FROM usuarios;
truncate usuarios;

#Usuario de prueba con hash
#Realizando en la terminal:
#	php artisan tinker
#	echo Hash::make("nombre_usuario");


INSERT INTO usuarios (nombre, password, permisos, creado, actualizado)
		VALUES ("sudo", "$2y$12$OQwyWGAILw2QuuPyiba7nOflfsDOB7ztMxRvwWy9S308JT46Qu8Hu", 50, NOW(), NOW());

INSERT INTO usuarios (nombre, password, permisos, creado, actualizado)
		VALUES ("administrador", "$2y$12$JZ0gCIv6ndWOl9REKQVe5.onzsSngSxboSml3ZOzw2rdsf6un/P5C", 40, NOW(), NOW());

INSERT INTO usuarios (nombre, password, permisos, creado, actualizado)
		VALUES ("moderador", "$2y$12$hRSoRPqILuk8SR41KOXrCed8izBxnZtXQqe7qat/1IE2RWrymcLa2", 30, NOW(), NOW());

INSERT INTO usuarios (nombre, password, permisos, creado, actualizado)
		VALUES ("editor", "$2y$12$nw4K8qhN/2p4..X6y4O.u.DdfY4c6HqWKcRQ36ZNhypLWBJ5jbGIO", 20, NOW(), NOW());

INSERT INTO usuarios (nombre, password, permisos, creado, actualizado)
		VALUES ("lector", "$2y$12$hiRURSpUeeuXrbQBtl2kNOIjWdoN6STICblMbUUXOhoZ7/1ZduIwK", 10, NOW(), NOW());


