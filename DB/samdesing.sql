--inserciones en oracle
--status_id
INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (0, 'DESACTIVADO', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (1, 'ACTIVO', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (2, 'Pago Confirmado', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (3, 'En Preparación', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (4, 'Enviado', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (5, 'Entregado', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (6, 'Cancelado', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (7, 'Pago Rechazado', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (8, 'Pago Pendiente', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (9, 'Orden Creada', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (10, 'Direccion Principal', 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB (Status_ID, Description, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (11, 'Direccion Secundaria', 'admin', SYSTIMESTAMP, NULL, NULL);
COMMIT;



--roles
INSERT INTO FIDE_SAMDESIGN.FIDE_ROL_TB (rol_id, rol_name, Created_By, status_id) VALUES (1, 'ROLE_ADMIN', 'Admin', 1);
INSERT INTO FIDE_SAMDESIGN.FIDE_ROL_TB (rol_id, rol_name, Created_By, status_id) VALUES (2, 'ROLE_VENDEDOR', 'Admin', 1);
INSERT INTO FIDE_SAMDESIGN.FIDE_ROL_TB (rol_id, rol_name, Created_By, status_id) VALUES (3, 'ROLE_USER', 'Admin', 1);
COMMIT;
--USERS
INSERT INTO FIDE_SAMDESIGN.FIDE_USERS_TB 
(User_ID, User_Name, User_Email, Password, Rol_ID, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES 
(1, 'admin', 'jorge@example.com', 'jorgeSAM', 1, 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_USERS_TB 
(User_ID, User_Name, User_Email, Password, Rol_ID, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES 
(2, 'admin2', 'ale@example.com', 'aleSAM', 1, 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_USERS_TB 
(User_ID, User_Name, User_Email, Password, Rol_ID, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES 
(3, 'vendedor', 'carlos@example.com', 'carlosSAM', 2, 1, 'admin', SYSTIMESTAMP, NULL, NULL);

COMMIT;


--paises
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (506, 'Costa Rica', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (507, 'Panamá', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (505, 'Nicaragua', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (52, 'México', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (57, 'Colombia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (Country_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (1, 'Estados Unidos', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
COMMIT;


--PROVINCIAS
INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (1, 'San José', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (2, 'Alajuela', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (3, 'Cartago', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (4, 'Heredia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (5, 'Guanacaste', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (6, 'Puntarenas', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (State_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) 
VALUES (7, 'Limón', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
COMMIT;


INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (101, 'San José', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (102, 'Escazú', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (103, 'Desamparados', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (104, 'Puriscal', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (105, 'Tarrazú', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (106, 'Aserrí', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (107, 'Mora', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (108, 'Goicoechea', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (109, 'Santa Ana', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (110, 'Alajuelita', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (111, 'Vásquez de Coronado', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (112, 'Acosta', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (113, 'Tibás', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (114, 'Moravia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (115, 'Montes de Oca', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (116, 'Turrubares', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (117, 'Dota', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (118, 'Curridabat', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (119, 'Pérez Zeledón', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (120, 'León Cortés Castro', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

-- Provincia de Alajuela
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (201, 'Alajuela', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (202, 'San Ramón', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (203, 'Grecia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (204, 'San Mateo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (205, 'Atenas', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (206, 'Naranjo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (207, 'Palmares', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (208, 'Poás', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (209, 'Orotina', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (210, 'San Carlos', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (211, 'Zarcero', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (212, 'Sarchí', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (213, 'Upala', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (214, 'Los Chiles', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (215, 'Guatuso', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (City_ID, Name, Status_ID, Created_By, Created_On, Modified_By, Modified_On) VALUES (216, 'Río Cuarto', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

--cartago
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (301, 'Cartago', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (302, 'Paraíso', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (303, 'La Unión', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (304, 'Jiménez', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (305, 'Turrialba', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (306, 'Alvarado', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (307, 'Oreamuno', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (308, 'El Guarco', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

-- heredia
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (401, 'Heredia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (402, 'Barva', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (403, 'Santo Domingo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (404, 'Santa Bárbara', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (405, 'San Rafael', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (406, 'San Isidro', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (407, 'Belén', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (408, 'Flores', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (409, 'San Pablo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

--guanacaste
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (501, 'Liberia', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (502, 'Nicoya', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (503, 'Santa Cruz', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (504, 'Bagaces', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (505, 'Carrillo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (506, 'Cañas', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (507, 'Abangares', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (508, 'Tilarán', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (509, 'Nandayure', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (510, 'La Cruz', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (511, 'Hojancha', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

--puntarenas
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (601, 'Puntarenas', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (602, 'Esparza', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (603, 'Buenos Aires', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (604, 'Montes de Oro', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (605, 'Osa', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (606, 'Quepos', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (607, 'Golfito', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (608, 'Coto Brus', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (609, 'Parrita', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (610, 'Corredores', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (611, 'Garabito', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);

--limon
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (701, 'Limón', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (702, 'Pococí', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (703, 'Siquirres', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (704, 'Talamanca', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (705, 'Matina', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
INSERT INTO FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB VALUES (706, 'Guácimo', 1, 'Admin', CURRENT_TIMESTAMP, NULL, NULL);
commit;

--inserts category
INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (1, 'Suetas', 'Categoría para suetas', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (2, 'Guantes', 'Categoría para guantes', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (3, 'Camisas Sublimadas', 'Categoría para camisas sublimadas', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (4, 'Camisa Sublimada Trabajo', 'Categoría para camisa sublimada de trabajo', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (5, 'Capas Impermeables', 'Categoría para capas impermeables', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (6, 'Repuestos', 'Categoría para repuestos', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (7, 'Chalecos', 'Categoría para chalecos', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (8, 'Jacket Protectiva', 'Categoría para jacket protectiva', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (9, 'Accesorios', 'Categoría para accesorios', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (10, 'Llantas', 'Categoría para llantas', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB 
    (Category_ID, Description, Comments, Status_ID, Created_By, Created_On, Modified_By, Modified_On)
VALUES
    (11, 'Uniformes Cross', 'Categoría para uniformes cross', 1, 'admin', SYSTIMESTAMP, NULL, NULL);

COMMIT;

INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (1, 'Transferencia Bancaria', 'Pago mediante transferencia a cuentas bancarias nacionales', 1, 'ADMIN', CURRENT_TIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (2, 'Sinpe Móvil', 'Pago instantáneo a través del sistema Sinpe Móvil del Banco Central', 1, 'ADMIN', CURRENT_TIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (3, 'Tarjeta de Crédito', 'Pago por medio de tarjetas Visa o Mastercard', 1, 'ADMIN', CURRENT_TIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (4, 'Tarjeta de Débito', 'Pago con tarjeta de débito nacional o internacional', 1, 'ADMIN', CURRENT_TIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (5, 'Pago Efectivo', 'El cliente paga en efectivo al recibir el producto en su domicilio', 1, 'ADMIN', CURRENT_TIMESTAMP);

    INSERT INTO FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB 
    (Payment_Method_ID, Payment_Method_Name, Description, Status_ID, Created_By, Created_On)
VALUES 
    (6, 'Tarjeta AMEX', 'El cliente paga con una tarjeta AMEX', 1, 'ADMIN', CURRENT_TIMESTAMP);
COMMIT;

--SEQUENCE
CREATE SEQUENCE FIDE_CART_SEQ START WITH 1 INCREMENT BY 1;
CREATE SEQUENCE FIDE_CART_LINE_SEQ START WITH 1 INCREMENT BY 1;
commit;
CREATE SEQUENCE FIDE_BILLING_SEQ START WITH 1000001 INCREMENT BY 1;
CREATE SEQUENCE FIDE_ORDER_LINE_SEQ START WITH 1 INCREMENT BY 1;



--INSERT INTO sam_design.categoria (id_categoria,descripcion,activo) VALUES 
--('1','Respuestos del Motor',true), 
--('2','Respuestos de frenos',true),
-- ('3','Accesorios y decoraciones',true),
-- ('4','Respuestos de Llantas',true),
-- ('5','Otros respuestos',true),
-- ('6','Camisa',true),
-- ('7','Impermeable',true);

--PRODUCTOS
-- INSERT INTO sam_design.camisa (id_camisa,id_categoria,descripcion,detalle,talla,precio,existencias,ruta_imagen,activo) VALUES 
-- ('1',6,'Vue JS','Camisa con logo de VueJS', 'L','12000','5','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F1.jpg?alt=media&token=0b6f8618-b8d1-4312-b624-a2039cca0874',true),
-- ('2',6,'Angular','Camisa con logo de Angular', 'M','12000','10','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F2.jpg?alt=media&token=485172d4-b682-4058-9c05-97d2df0d359f',true),
-- ('3',6,'React JS','Camisa con logo de React JS', 'S','12000','5','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F3.jpg?alt=media&token=fcbb2ff4-9af0-45dc-8bc9-87c0097fa810',true),
-- ('4',6,'REDUX','Camisa con logo de REDUX', 'XL','12000','10','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F4.jpg?alt=media&token=ad8e1e75-72e0-4845-8094-1cbbc01c6980',true),
-- ('5',6,'SASS','Camisa con logo de SASS', 'S','12000','3','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F6.jpg?alt=media&token=9f0744c3-e5f0-4e47-8641-9ed623ed7a01',true),
-- ('6',6,'HTML5','Camisa con logo de HTML5', 'L','12000','6','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F7.jpg?alt=media&token=7fe0ba87-f5e1-4b82-b1f4-0018e4e31771',true),
-- ('7',6,'GITHub','Camisa con logo de GITHub', 'XL','12000','5','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F8.jpg?alt=media&token=75d50b48-4995-4512-81d3-23312f406bd3',true),
-- ('8',6,'Wordpress','Camisa con logo de Wordpress', 'S','12000','5','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2F14.jpg?alt=media&token=20711a78-7c43-4a17-88d6-f9b25a5f28c5',true),
-- ('9',6,'Camper Red','Jersey Radical Racing Camper Red', 'XL','22000','12','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-red.jpg?alt=media&token=25e18312-6337-45aa-957d-c8cabacdc6e9',true),
-- ('10',6,'DH Camo','Jersey Radical Racing DH Camo', 'M','22000','15','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-camo.jpg?alt=media&token=014082e1-6bde-4826-bf0a-430cfce6c2df',true),
-- ('11',6,'Camper Sand','Jersey Radical Camper Sand', 'L','22000','10','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-sand.jpg?alt=media&token=a6d9ad33-10ab-42d0-b21d-7c573bab39c6',true),
-- ('12',6,'Camper Combat Camo','Jersey Radical Racing Camper Combat', 'L','22000','15','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-combat.png?alt=media&token=6232a663-46ab-4ecd-a575-01e8d343588d',true),
-- ('13',6,'MotoCross Custom','Jersey Motocross Custom', 'M','22000','15','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-custom.jpg?alt=media&token=f319c416-8c80-466d-b7f6-976ed36fb20e',true),
-- ('14',6,'Fox Red','Jersey Fox Red color', 'XL','32000','20','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FCamisa%2Fjersey-fox-red.jpg?alt=media&token=91aea43b-d234-4370-9438-2cd53638760a',true);


-- INSERT INTO sam_design.impermeable (id_impermeable,id_categoria,descripcion,detalle,talla,precio,existencias,ruta_imagen,activo) VALUES 
-- ('1',7,'MotoCAPA SAM DESIGN AZUL','MotoCAPA color AZUL, reflectiva y totalmente contra agua', 'L','32000','5','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FImpermeable%2Fsueta1.jpg?alt=media&token=89da7d25-a443-45bb-ba80-92d25bb7bd36',true),
-- ('2',7,'MotoCAPA SAM DESIGN AMARILLA','MotoCAPA color AMARILLA, reflectiva y totalmente contra agua', 'XL','32000','7','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FImpermeable%2Fsueta2.jpg?alt=media&token=27b2b455-4efa-406e-830a-f6231d5cbc9f',true),
-- ('3',7,'MotoCAPA SAM DESIGN NEGRA','MotoCAPA color NEGRA, reflectiva y totalmente contra agua', 'M','32000','10','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FImpermeable%2Fsueta3.jpg?alt=media&token=91967df4-149a-4ad5-a4af-4644ba144b2a',true),
-- ('4',7,'MotoCAPA ROJA','MotoCAPA color ROJA, reflectiva y totalmente contra agua', 'S','32000','3','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FImpermeable%2FCAPA%20ROJA.jpg?alt=media&token=18b44636-3d0c-47cc-8a94-83011a119087',true),
-- ('5',7,'MotoCAPA NEGRA','MotoCAPA color NEGRA BASICA, reflectiva y totalmente contra agua', 'L','22000','10','https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2FImpermeable%2FMOTOCAPA%20BASICA.jpg?alt=media&token=38ff832c-d8a0-47eb-b7e7-44113023f0aa',true)
-- ;

  
-- /*Se insertan 1 tipos de repuesto*/  
INSERT INTO sam_design.repuesto (id_repuesto,id_categoria,descripcion,detalle,precio,existencias,ruta_imagen,activo) VALUES
(1,1,'KIT DE PISTÓN','KIT DE PISTÓN YAMAHA DT175 74-81 0,5 mm O/S FORSETI 66,5 mm ANILLOS CLIPS PIN',30000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fkit-de-piston.jpg?alt=media&token=f56cb7ba-7764-41d5-94a1-3c74482f05ee',true),
-- (2,2,'Disco de freno','Disco de Freno Delantero 300MM',7000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fdisco-delantero.png?alt=media&token=9b20cb52-4481-4d90-80a7-41c480921305',true),
-- (3,1,'Carburador','Carburador para Yamaha DT175 DT-175 Dt 175 1978 1979 1980 1981',27000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fcarburador-dt125.jpg?alt=media&token=56a12944-a24a-43b6-9afb-72a3e494f1c5',true),
-- (4,4,'Mufla','Silenciador de Escape Yamaha DT125 DT175 MX Para 1977A 1991',93000,1,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fmufla-dt.jpg?alt=media&token=a166e613-de49-45f4-8ab0-be3a0e7caaae',true),
-- (5,5,'Empaque DT','Empaques para Yamaha DT175 DT-175 Dt 175 1978 1979 1980 1981',13500,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fempaques-dt.jpg?alt=media&token=142db6cb-61a2-4465-9dee-84fc4f0ff80b',true),
-- (6,3,'Tapas Laterales','Plasticos laterales para Yamaha DT175 DT-175 Dt 175 1978 1979 1980 1981',27000,2,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2FTAPAS-LATERALES.jpg?alt=media&token=d22ca69f-66fa-4fad-9fd1-58d830948d4f',true),
-- (7,3,'Tapas delanteras','Plasticos frontales NEGRA para Yamaha DT175 DT-175 Dt 175 1978 1979 1980 1981',22000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Ftapas-frontales.jpg?alt=media&token=d74a86b1-a27c-466e-a73e-58a5c81cfe7d',true),
-- (8,2,'Pastillas de frenos','Pastillas de frenos para Yamaha DT175 DT-175 Dt 175',9000,2,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Frepuesto4.png?alt=media&token=af891110-2e44-4074-99fe-8c96c529fdc4',true),
-- (9,3,'Tacometro DT','Velocimetro para Yamaha DT125 DT-175 Dt 175 1980 1981',25000,3,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fvelocimetro.jpeg?alt=media&token=dda7b421-210d-45bb-b14b-e60cee8e01d2',true),
-- (10,1,'Cadena y piñon','Cadena y piñon para Yamaha DT175 DT125 1978 1979',67000,2,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Frepuesto6.png?alt=media&token=2dbb50cb-182c-4601-a8e4-2056e36be237',true),
-- (11,4,'LLanta continental','LLanta continental',37000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fllanta-continental2.png?alt=media&token=0a1650d8-69a5-47a9-857e-672b8f2b170d',true),
-- (12,4,'LLanta Michelin','LLanta Michelin',37000,4,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2FLlantas-Michelin2.jpg?alt=media&token=993cdd74-6c51-4c77-8a38-6d564624b229',true),
-- (13,4,'LLanta Pirelli','LLanta Pirelli',37000,1,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2FLLANTAs-PIRELLI2.jpg?alt=media&token=df793841-a5f5-463f-82a6-20d554a8ec87',true),
-- (14,3,'Asiento alcolchado DT','Asiento para DT-175 Azul',37000,1,'https://http2.mlstatic.com/D_NQ_NP_695556-MLV50452120585_062022-O.webp',true),
-- (15,3,'Foco de luz DT','Foco de luz para DT-175/DT-125 NO INCLUYE BOMBILLOS',17000,3,'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fluces-dt125.jpg?alt=media&token=847f38ed-0956-4f66-9c32-959e40212358',true)
-- ;  
  
-- Inserciones de ejemplo para llantas (en FIDE_SAMDESIGN.FIDE_PRODUCT_TB)
INSERT INTO FIDE_SAMDESIGN.FIDE_PRODUCT_TB 
(Product_ID, Description, Category_Type_ID, Comments, Unit_price, Image_path, Status_ID, Created_By, Created_On)
VALUES 
(1, 'Llantas Continental', 4, 'Llanta de alto rendimiento para uso urbano y off-road', 37000, 'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2Fllanta-continental2.png?alt=media&token=0a1650d8-69a5-47a9-857e-672b8f2b170d', 1, 'admin', SYSTIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PRODUCT_TB 
(Product_ID, Description, Category_Type_ID, Comments, Unit_price, Image_path, Status_ID, Created_By, Created_On)
VALUES 
(2, 'Llantas Michelin', 4, 'Diseño moderno, excelente agarre en lluvia y seco', 37000, 'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2FLlantas-Michelin2.jpg?alt=media&token=993cdd74-6c51-4c77-8a38-6d564624b229', 1, 'admin', SYSTIMESTAMP);

INSERT INTO FIDE_SAMDESIGN.FIDE_PRODUCT_TB 
(Product_ID, Description, Category_Type_ID, Comments, Unit_price, Image_path, Status_ID, Created_By, Created_On)
VALUES 
(3, 'Llantas Pirelli', 4, 'Llanta de gran durabilidad, ideal para motos deportivas', 37000, 'https://firebasestorage.googleapis.com/v0/b/sam-design-a951a.appspot.com/o/SAM%20DESIGN%2Frepuesto%2FLLANTAs-PIRELLI2.jpg?alt=media&token=df793841-a5f5-463f-82a6-20d554a8ec87', 1, 'admin', SYSTIMESTAMP);