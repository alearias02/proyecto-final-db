-- Procedimiento para insertar STATUS registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_STATUS_TB_SP
(
    P_STATUS_ID    IN NUMBER,
    P_DESCRIPTION  IN VARCHAR2,
    P_CREATED_BY   IN VARCHAR2,
    P_CREATED_ON   IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID, Description, Created_By, Created_On)
    VALUES (P_STATUS_ID, P_DESCRIPTION, P_CREATED_BY, P_CREATED_ON);
    COMMIT;
END INSERTAR_FIDE_STATUS_TB_SP;

-- Procedimiento para modificar STATUS registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_STATUS_TB_SP
(
    P_STATUS_ID    IN NUMBER,
    P_DESCRIPTION  IN VARCHAR2,
    P_MODIFIED_BY  IN VARCHAR2,
    P_MODIFIED_ON  IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_STATUS_TB
    SET Description = P_DESCRIPTION,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE Status_ID = P_STATUS_ID;
    COMMIT;
END MODIFICAR_FIDE_STATUS_TB_SP;

-- Procedimiento para eliminar STATUS registros
CREATE OR REPLACE PROCEDURE ELIMINAR_FIDE_STATUS_TB_SP
(
    P_STATUS_ID IN NUMBER
)
IS
BEGIN
    DELETE FROM FIDE_SAMDESIGN.FIDE_STATUS_TB WHERE Status_ID = P_STATUS_ID;
    COMMIT;
END ELIMINAR_FIDE_STATUS_TB_SP;


-- Procedimiento para insertar ROL registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_ROL_TB_SP
(
    P_ROL_ID      IN NUMBER,
    P_ROL_NAME    IN VARCHAR2,
    P_STATUS_ID   IN NUMBER,
    P_CREATED_BY  IN VARCHAR2,
    P_CREATED_ON  IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_ROL_TB(rol_id, rol_name, Status_ID, Created_By, Created_On)
    VALUES (P_ROL_ID, P_ROL_NAME, P_STATUS_ID, P_CREATED_BY, P_CREATED_ON);
    COMMIT;
END INSERTAR_FIDE_ROL_TB_SP;

-- Procedimiento para modificar ROL registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_ROL_TB_SP
(
    P_ROL_ID      IN NUMBER,
    P_ROL_NAME    IN VARCHAR2,
    P_STATUS_ID   IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_ROL_TB
    SET rol_name    = P_ROL_NAME,
        Status_ID   = P_STATUS_ID,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE rol_id = P_ROL_ID;
    COMMIT;
END MODIFICAR_FIDE_ROL_TB_SP;

-- Procedimiento para eliminar ROL registros
CREATE OR REPLACE PROCEDURE ELIMINAR_FIDE_ROL_TB_SP
(
    P_ROL_ID IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_ROL_TB
    SET Status_ID   = 0,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE rol_id = P_ROL_ID;
    COMMIT;
END ELIMINAR_FIDE_ROL_TB_SP;

-- Procedimiento para insertar USERS
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_USERS_TB_SP
(
    P_USER_ID     IN NUMBER,
    P_USER_NAME   IN VARCHAR2,
    P_USER_EMAIL  IN VARCHAR2,
    P_PASSWORD    IN VARCHAR2,
    P_ROL_ID      IN NUMBER,
    P_STATUS_ID   IN NUMBER,
    P_CREATED_BY  IN VARCHAR2,
    P_CREATED_ON  IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_USERS_TB(User_ID, User_Name, User_Email, Password, Rol_ID, Status_ID, Created_By, Created_On)
    VALUES (P_USER_ID, P_USER_NAME, P_USER_EMAIL, P_PASSWORD, P_ROL_ID, P_STATUS_ID, P_CREATED_BY, P_CREATED_ON);
    COMMIT;
END INSERTAR_FIDE_USERS_TB_SP;

-- Procedimiento para modificar USERS
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_USERS_TB_SP
(
    P_USER_ID     IN NUMBER,
    P_USER_NAME   IN VARCHAR2,
    P_USER_EMAIL  IN VARCHAR2,
    P_PASSWORD    IN VARCHAR2,
    P_ROL_ID      IN NUMBER,
    P_STATUS_ID   IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_USERS_TB
    SET User_Name   = P_USER_NAME,
        User_Email  = P_USER_EMAIL,
        Password    = P_PASSWORD,
        Rol_ID      = P_ROL_ID,
        Status_ID   = P_STATUS_ID,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE User_ID = P_USER_ID;
    COMMIT;
END MODIFICAR_FIDE_USERS_TB_SP;

-- Procedimiento para desactivar USERS
CREATE OR REPLACE PROCEDURE ELIMINAR_FIDE_USERS_TB_SP
(
    P_USER_ID     IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_USERS_TB
    SET Status_ID   = 0,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE User_ID = P_USER_ID;
    COMMIT;
END ELIMINAR_FIDE_USERS_TB_SP;

-- Procedimiento para insertar CATEGORY
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_CATEGORY_TYPE_TB_SP
(
    P_CATEGORY_ID IN NUMBER,
    P_DESCRIPTION IN VARCHAR2,
    P_COMMENTS    IN VARCHAR2,
    P_STATUS_ID   IN NUMBER,
    P_CREATED_BY  IN VARCHAR2,
    P_CREATED_ON  IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB(Category_ID, Description, Comments, Status_ID, Created_By, Created_On)
    VALUES (P_CATEGORY_ID, P_DESCRIPTION, P_COMMENTS, P_STATUS_ID, P_CREATED_BY, P_CREATED_ON);
    COMMIT;
END INSERTAR_FIDE_CATEGORY_TYPE_TB_SP;

-- Procedimiento para modificar CATEGORY
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_CATEGORY_TYPE_TB_SP
(
    P_CATEGORY_ID IN NUMBER,
    P_DESCRIPTION IN VARCHAR2,
    P_COMMENTS    IN VARCHAR2,
    P_STATUS_ID   IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB
    SET Description = P_DESCRIPTION,
        Comments    = P_COMMENTS,
        Status_ID   = P_STATUS_ID,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE Category_ID = P_CATEGORY_ID;
    COMMIT;
END MODIFICAR_FIDE_CATEGORY_TYPE_TB_SP;

-- Procedimiento para desactivar CATEGORY 
CREATE OR REPLACE PROCEDURE ELIMINAR_FIDE_CATEGORY_TYPE_TB_SP
(
    P_CATEGORY_ID IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB
    SET Status_ID   = 0,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE Category_ID = P_CATEGORY_ID;
    COMMIT;
END ELIMINAR_FIDE_CATEGORY_TYPE_TB_SP;

-- Procedimiento para insertar VENDOR
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_VENDOR_TB_SP
(
    P_VENDOR_ID         IN NUMBER,
    P_VENDOR_NAME       IN VARCHAR2,
    P_VENDOR_ADDRESS_ID IN NUMBER,
    P_VENDOR_EMAIL      IN VARCHAR2,
    P_VENDOR_BALANCE    IN FLOAT,
    P_CREATED_BY        IN VARCHAR2,
    P_CREATED_ON        IN TIMESTAMP,
    P_STATUS_ID         IN NUMBER
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_VENDOR_TB(Vendor_ID, Vendor_Name, Vendor_Address_ID, Vendor_eMail, Vendor_Balance, Created_By, Created_On, Status_ID)
    VALUES (P_VENDOR_ID, P_VENDOR_NAME, P_VENDOR_ADDRESS_ID, P_VENDOR_EMAIL, P_VENDOR_BALANCE, P_CREATED_BY, P_CREATED_ON, P_STATUS_ID);
    COMMIT;
END INSERTAR_FIDE_VENDOR_TB_SP;

-- Procedimiento para modificar VENDOR
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_VENDOR_TB_SP
(
    P_VENDOR_ID         IN NUMBER,
    P_VENDOR_NAME       IN VARCHAR2,
    P_VENDOR_ADDRESS_ID IN NUMBER,
    P_VENDOR_EMAIL      IN VARCHAR2,
    P_VENDOR_BALANCE    IN FLOAT,
    P_STATUS_ID         IN NUMBER,
    P_MODIFIED_BY       IN VARCHAR2,
    P_MODIFIED_ON       IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_VENDOR_TB
    SET Vendor_Name        = P_VENDOR_NAME,
        Vendor_Address_ID  = P_VENDOR_ADDRESS_ID,
        Vendor_eMail       = P_VENDOR_EMAIL,
        Vendor_Balance     = P_VENDOR_BALANCE,
        Status_ID          = P_STATUS_ID,
        Modified_By        = P_MODIFIED_BY,
        Modified_On        = P_MODIFIED_ON
    WHERE Vendor_ID = P_VENDOR_ID;
    COMMIT;
END MODIFICAR_FIDE_VENDOR_TB_SP;

-- Procedimiento para desactivar VENDOR
CREATE OR REPLACE PROCEDURE ELIMINAR_FIDE_VENDOR_TB_SP
(
    P_VENDOR_ID   IN NUMBER,
    P_MODIFIED_BY IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_VENDOR_TB
    SET Status_ID   = 0,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON
    WHERE Vendor_ID = P_VENDOR_ID;
    COMMIT;
END ELIMINAR_FIDE_VENDOR_TB_SP;

-- Procedimiento para insertar CUSTOMER registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_CUSTOMER_TB_SP
(
    P_CUSTOMER_ID           IN NUMBER,
    P_CUSTOMER_NAME         IN VARCHAR2,
    P_CUSTOMER_EMAIL        IN VARCHAR2,
    P_CUSTOMER_PHONE_NUMBER IN VARCHAR2,
    P_STATUS_ID             IN NUMBER,
    P_CREATED_BY            IN VARCHAR2,
    P_CREATED_ON            IN TIMESTAMP,
    P_MODIFIED_BY           IN VARCHAR2,
    P_MODIFIED_ON           IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_CUSTOMER_TB
    (
        Customer_ID,
        Customer_Name,
        Customer_Email,
        Customer_Phone_number,
        Status_ID,
        Created_By,
        Created_On,
        Modified_By,
        Modified_On
    )
    VALUES
    (
        P_CUSTOMER_ID,
        P_CUSTOMER_NAME,
        P_CUSTOMER_EMAIL,
        P_CUSTOMER_PHONE_NUMBER,
        P_STATUS_ID,
        P_CREATED_BY,
        P_CREATED_ON,
        P_MODIFIED_BY,
        P_MODIFIED_ON
    );
    COMMIT;
END INSERTAR_FIDE_CUSTOMER_TB_SP;
/

-- Procedimiento para insertar ADDRESS registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_ADDRESS_TB_SP
(
    P_ADDRESS_ID   IN NUMBER,
    P_ADDRESS      IN VARCHAR2,
    P_ID_STATE     IN NUMBER,
    P_ID_CITY      IN NUMBER,
    P_ZIP_CODE     IN NUMBER,
    P_ID_COUNTRY   IN NUMBER,
    P_STATUS_ID    IN NUMBER,
    P_CREATED_BY   IN VARCHAR2,
    P_CREATED_ON   IN TIMESTAMP,
    P_MODIFIED_BY  IN VARCHAR2,
    P_MODIFIED_ON  IN TIMESTAMP,
    P_ID_CUSTOMER  IN NUMBER
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_ADDRESS_TB
    (
        Address_ID,
        Address,
        ID_State,
        ID_City,
        ZIP_Code,
        ID_Country,
        Status_ID,
        Created_By,
        Created_On,
        Modified_By,
        Modified_On,
        ID_Customer
    )
    VALUES
    (
        P_ADDRESS_ID,
        P_ADDRESS,
        P_ID_STATE,
        P_ID_CITY,
        P_ZIP_CODE,
        P_ID_COUNTRY,
        P_STATUS_ID,
        P_CREATED_BY,
        P_CREATED_ON,
        P_MODIFIED_BY,
        P_MODIFIED_ON,
        P_ID_CUSTOMER
    );
    COMMIT;
END INSERTAR_FIDE_ADDRESS_TB_SP;
/

-- Procedimiento para insertar PRODUCT registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_PRODUCT_TB_SP
(
    P_PRODUCT_ID       IN NUMBER,
    P_DESCRIPTION      IN VARCHAR2,
    P_CATEGORY_TYPE_ID IN NUMBER,
    P_COMMENTS         IN VARCHAR2,
    P_UNIT_PRICE       IN NUMBER,
    P_QUANTITY_ONHAND  IN NUMBER,
    P_QUANTITY_LEND    IN NUMBER,
    P_TOTAL_QTY        IN NUMBER,
    P_STATUS_ID        IN NUMBER,
    P_CREATED_BY       IN VARCHAR2,
    P_CREATED_ON       IN DATE,
    P_MODIFIED_BY      IN VARCHAR2,
    P_MODIFIED_ON      IN DATE
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_PRODUCT_TB
    (
        Product_ID,
        Description,
        Category_Type_ID,
        Comments,
        Unit_price,
        Quantity_OnHand,
        Quantity_Lend,
        Total_Qty,
        Status_ID,
        Created_By,
        Created_On,
        Modified_By,
        Modified_On
    )
    VALUES
    (
        P_PRODUCT_ID,
        P_DESCRIPTION,
        P_CATEGORY_TYPE_ID,
        P_COMMENTS,
        P_UNIT_PRICE,
        P_QUANTITY_ONHAND,
        P_QUANTITY_LEND,
        P_TOTAL_QTY,
        P_STATUS_ID,
        P_CREATED_BY,
        P_CREATED_ON,
        P_MODIFIED_BY,
        P_MODIFIED_ON
    );
    COMMIT;
END INSERTAR_FIDE_PRODUCT_TB_SP;
/

-- Procedimiento para insertar INVENTORY registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_INVENTORY_TB_SP
(
    P_INVENTORY_ID       IN NUMBER,
    P_PRODUCT_ID         IN NUMBER,
    P_COMMENTS           IN VARCHAR2,
    P_QUANTITY_STOCK     IN NUMBER,
    P_QUANTITY_RESERVED  IN NUMBER,
    P_QUANTITY_THRESHOLD IN NUMBER,
    P_STATUS_ID          IN NUMBER,
    P_LAST_RESTOCK       IN TIMESTAMP,
    P_CREATED_BY         IN VARCHAR2,
    P_CREATED_ON         IN TIMESTAMP,
    P_MODIFIED_BY        IN VARCHAR2,
    P_MODIFIED_ON        IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_INVENTORY_TB
    (
        Inventory_ID,
        Product_ID,
        Comments,
        Quantity_Stock,
        Quantity_Reserved,
        Quantity_Threshold,
        Status_ID,
        Last_Restock,
        Created_By,
        Created_On,
        Modified_By,
        Modified_On
    )
    VALUES
    (
        P_INVENTORY_ID,
        P_PRODUCT_ID,
        P_COMMENTS,
        P_QUANTITY_STOCK,
        P_QUANTITY_RESERVED,
        P_QUANTITY_THRESHOLD,
        P_STATUS_ID,
        P_LAST_RESTOCK,
        P_CREATED_BY,
        P_CREATED_ON,
        P_MODIFIED_BY,
        P_MODIFIED_ON
    );
    COMMIT;
END INSERTAR_FIDE_INVENTORY_TB_SP;
/

-- Procedimiento para insertar INVENTORY_LINES registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_INVENTORY_LINES_TB_SP
(
    P_INVENTORY_LINES_ID IN NUMBER,
    P_INVENTORY_ID       IN NUMBER,
    P_PRODUCT_ID         IN NUMBER,
    P_COMMENTS           IN VARCHAR2,
    P_QUANTITY_STOCKED   IN NUMBER,
    P_QUANTITY_RESERVED  IN NUMBER,
    P_QUANTITY_THRESHOLD IN NUMBER,
    P_STATUS_ID          IN NUMBER,
    P_LAST_RESORT        IN TIMESTAMP,
    P_CREATED_BY         IN VARCHAR2,
    P_CREATED_ON         IN TIMESTAMP,
    P_MODIFIED_BY        IN VARCHAR2,
    P_MODIFIED_ON        IN TIMESTAMP
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_INVENTORY_LINES_TB
    (
        Inventory_Lines_ID,
        Inventory_ID,
        Product_ID,
        Comments,
        Quantity_Stocked,
        Quantity_Reserved,
        Quantity_Threshold,
        Status_ID,
        Last_Resort,
        Created_By,
        Created_On,
        Modified_By,
        Modified_On
    )
    VALUES
    (
        P_INVENTORY_LINES_ID,
        P_INVENTORY_ID,
        P_PRODUCT_ID,
        P_COMMENTS,
        P_QUANTITY_STOCKED,
        P_QUANTITY_RESERVED,
        P_QUANTITY_THRESHOLD,
        P_STATUS_ID,
        P_LAST_RESORT,
        P_CREATED_BY,
        P_CREATED_ON,
        P_MODIFIED_BY,
        P_MODIFIED_ON
    );
    COMMIT;
END INSERTAR_FIDE_INVENTORY_LINES_TB_SP;
/

-- Procedimiento para insertar ORDER registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_ORDER_TB_SP
(
    P_ORDER_ID          IN NUMBER,
    P_CUSTOMER_ID       IN NUMBER,
    P_ORDER_DATE        IN TIMESTAMP,
    P_ORDER_AMOUNT      IN NUMBER,
    P_ORDER_TAX         IN NUMBER,
    P_COMMENTS          IN VARCHAR2,
    P_DISPATCH          IN NUMBER,
    P_FULLFIELD         IN NUMBER,
    P_STATUS_ID         IN NUMBER,
    P_PAYMENT_METHOD_ID IN NUMBER,
    P_CREATED_ON        IN TIMESTAMP,
    P_CREATED_BY        IN VARCHAR2,
    P_MODIFIED_ON       IN TIMESTAMP,
    P_MODIFIED_BY       IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_ORDER_TB
    (
        Order_ID,
        Customer_ID,
        Order_Date,
        Order_Amount,
        Order_Tax,
        Comments,
        Dispatch,
        Fullfield,
        Status_ID,
        Payment_Method_ID,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_ORDER_ID,
        P_CUSTOMER_ID,
        P_ORDER_DATE,
        P_ORDER_AMOUNT,
        P_ORDER_TAX,
        P_COMMENTS,
        P_DISPATCH,
        P_FULLFIELD,
        P_STATUS_ID,
        P_PAYMENT_METHOD_ID,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_ORDER_TB_SP;
/

-- Procedimiento para insertar ORDER_LINES registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_ORDER_LINES_TB_SP
(
    P_ORDER_LINE_ID IN NUMBER,
    P_ORDER_ID      IN NUMBER,
    P_PRODUCT_ID    IN NUMBER,
    P_QTY_ITEM      IN NUMBER,
    P_COMMENTS      IN VARCHAR2,
    P_STATUS_ID     IN NUMBER,
    P_TOTAL_PRICE   IN NUMBER,
    P_CREATED_ON    IN TIMESTAMP,
    P_CREATED_BY    IN VARCHAR2,
    P_MODIFIED_ON   IN TIMESTAMP,
    P_MODIFIED_BY   IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_ORDER_LINES_TB
    (
        Order_Line_ID,
        Order_ID,
        Product_ID,
        Qty_Item,
        Comments,
        Status_ID,
        Total_Price,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_ORDER_LINE_ID,
        P_ORDER_ID,
        P_PRODUCT_ID,
        P_QTY_ITEM,
        P_COMMENTS,
        P_STATUS_ID,
        P_TOTAL_PRICE,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_ORDER_LINES_TB_SP;
/

-- Procedimiento para insertar BILLING registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_BILLING_TB_SP
(
    P_BILLING_ID         IN NUMBER,
    P_ORDER_ID           IN NUMBER,
    P_CUSTOMER_ID        IN NUMBER,
    P_INVOICED_ADDRESS_ID IN NUMBER,
    P_BILLING_DATE       IN TIMESTAMP,
    P_TOTAL_AMOUNT       IN NUMBER,
    P_COMMENTS           IN VARCHAR2,
    P_STATUS_ID          IN NUMBER,
    P_PAYMENT_METHOD_ID  IN NUMBER,
    P_CREATED_ON         IN TIMESTAMP,
    P_CREATED_BY         IN VARCHAR2,
    P_MODIFIED_ON        IN TIMESTAMP,
    P_MODIFIED_BY        IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_BILLING_TB
    (
        Billing_ID,
        Order_ID,
        Customer_ID,
        Invoiced_Address_ID,
        Billing_Date,
        Total_Amount,
        Comments,
        Status_ID,
        Payment_Method_ID,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_BILLING_ID,
        P_ORDER_ID,
        P_CUSTOMER_ID,
        P_INVOICED_ADDRESS_ID,
        P_BILLING_DATE,
        P_TOTAL_AMOUNT,
        P_COMMENTS,
        P_STATUS_ID,
        P_PAYMENT_METHOD_ID,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_BILLING_TB_SP;
/

-- Procedimiento para insertar CART registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_CART_TB_SP
(
    P_CART_ID           IN NUMBER,
    P_CUSTOMER_ID       IN NUMBER,
    P_ADDRESS_ID        IN NUMBER,
    P_ORDER_DATE        IN TIMESTAMP,
    P_COMMENTS          IN VARCHAR2,
    P_STATUS_ID         IN NUMBER,
    P_PAYMENT_METHOD_ID IN NUMBER,
    P_CREATED_ON        IN TIMESTAMP,
    P_CREATED_BY        IN VARCHAR2,
    P_MODIFIED_ON       IN TIMESTAMP,
    P_MODIFIED_BY       IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_CART_TB
    (
        Cart_ID,
        Customer_ID,
        Address_ID,
        Order_Date,
        Comments,
        Status_ID,
        Payment_Method_ID,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_CART_ID,
        P_CUSTOMER_ID,
        P_ADDRESS_ID,
        P_ORDER_DATE,
        P_COMMENTS,
        P_STATUS_ID,
        P_PAYMENT_METHOD_ID,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_CART_TB_SP;
/

-- Procedimiento para insertar CART_LINES registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_CART_LINES_TB_SP
(
    P_CART_LINE_ID IN NUMBER,
    P_CART_ID      IN NUMBER,
    P_PRODUCT_ID   IN NUMBER,
    P_QTY_ITEM     IN NUMBER,
    P_COMMENTS     IN VARCHAR2,
    P_STATUS_ID    IN NUMBER,
    P_TOTAL_PRICE  IN NUMBER,
    P_CREATED_ON   IN TIMESTAMP,
    P_CREATED_BY   IN VARCHAR2,
    P_MODIFIED_ON  IN TIMESTAMP,
    P_MODIFIED_BY  IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_CART_LINES_TB
    (
        Cart_Line_ID,
        Cart_ID,
        Product_ID,
        Qty_Item,
        Comments,
        Status_ID,
        Total_Price,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_CART_LINE_ID,
        P_CART_ID,
        P_PRODUCT_ID,
        P_QTY_ITEM,
        P_COMMENTS,
        P_STATUS_ID,
        P_TOTAL_PRICE,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_CART_LINES_TB_SP;
/

-- Procedimiento para insertar SPECIAL_ORDER registros
CREATE OR REPLACE PROCEDURE INSERTAR_FIDE_SPECIAL_ORDER_TB_SP
(
    P_ORDER_ID    IN NUMBER,
    P_CUSTOMER_ID IN NUMBER,
    P_ORDER_DATE  IN TIMESTAMP,
    P_ORDER_QTY   IN NUMBER,
    P_COMMENTS    IN VARCHAR2,
    P_STATUS_ID   IN NUMBER,
    P_CREATED_ON  IN TIMESTAMP,
    P_CREATED_BY  IN VARCHAR2,
    P_MODIFIED_ON IN TIMESTAMP,
    P_MODIFIED_BY IN VARCHAR2
)
IS
BEGIN
    INSERT INTO FIDE_SAMDESIGN.FIDE_SPECIAL_ORDER_TB
    (
        Order_ID,
        Customer_ID,
        Order_Date,
        Order_Qty,
        Comments,
        Status_ID,
        Created_On,
        Created_By,
        Modified_On,
        Modified_By
    )
    VALUES
    (
        P_ORDER_ID,
        P_CUSTOMER_ID,
        P_ORDER_DATE,
        P_ORDER_QTY,
        P_COMMENTS,
        P_STATUS_ID,
        P_CREATED_ON,
        P_CREATED_BY,
        P_MODIFIED_ON,
        P_MODIFIED_BY
    );
    COMMIT;
END INSERTAR_FIDE_SPECIAL_ORDER_TB_SP;
/

-- Procedimiento para modificar CUSTOMER registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_CUSTOMER_TB_SP
(
    P_CUSTOMER_ID           IN NUMBER,
    P_CUSTOMER_NAME         IN VARCHAR2,
    P_CUSTOMER_EMAIL        IN VARCHAR2,
    P_CUSTOMER_PHONE_NUMBER IN VARCHAR2,
    P_STATUS_ID             IN NUMBER,
    P_MODIFIED_BY           IN VARCHAR2,
    P_MODIFIED_ON           IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_CUSTOMER_TB
    SET Customer_Name         = P_CUSTOMER_NAME,
        Customer_Email        = P_CUSTOMER_EMAIL,
        Customer_Phone_number = P_CUSTOMER_PHONE_NUMBER,
        Status_ID             = P_STATUS_ID,
        Modified_By           = P_MODIFIED_BY,
        Modified_On           = P_MODIFIED_ON
    WHERE Customer_ID = P_CUSTOMER_ID;
    COMMIT;
END MODIFICAR_FIDE_CUSTOMER_TB_SP;
/

-- Procedimiento para modificar ADDRESS registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_ADDRESS_TB_SP
(
    P_ADDRESS_ID   IN NUMBER,
    P_ADDRESS      IN VARCHAR2,
    P_ID_STATE     IN NUMBER,
    P_ID_CITY      IN NUMBER,
    P_ZIP_CODE     IN NUMBER,
    P_ID_COUNTRY   IN NUMBER,
    P_STATUS_ID    IN NUMBER,
    P_MODIFIED_BY  IN VARCHAR2,
    P_MODIFIED_ON  IN TIMESTAMP,
    P_ID_CUSTOMER  IN NUMBER
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_ADDRESS_TB
    SET Address     = P_ADDRESS,
        ID_State    = P_ID_STATE,
        ID_City     = P_ID_CITY,
        ZIP_Code    = P_ZIP_CODE,
        ID_Country  = P_ID_COUNTRY,
        Status_ID   = P_STATUS_ID,
        Modified_By = P_MODIFIED_BY,
        Modified_On = P_MODIFIED_ON,
        ID_Customer = P_ID_CUSTOMER
    WHERE Address_ID = P_ADDRESS_ID;
    COMMIT;
END MODIFICAR_FIDE_ADDRESS_TB_SP;
/

-- Procedimiento para modificar PRODUCT registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_PRODUCT_TB_SP
(
    P_PRODUCT_ID       IN NUMBER,
    P_DESCRIPTION      IN VARCHAR2,
    P_CATEGORY_TYPE_ID IN NUMBER,
    P_COMMENTS         IN VARCHAR2,
    P_UNIT_PRICE       IN NUMBER,
    P_QUANTITY_ONHAND  IN NUMBER,
    P_QUANTITY_LEND    IN NUMBER,
    P_TOTAL_QTY        IN NUMBER,
    P_STATUS_ID        IN NUMBER,
    P_MODIFIED_BY      IN VARCHAR2,
    P_MODIFIED_ON      IN DATE
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_PRODUCT_TB
    SET Description      = P_DESCRIPTION,
        Category_Type_ID = P_CATEGORY_TYPE_ID,
        Comments         = P_COMMENTS,
        Unit_price       = P_UNIT_PRICE,
        Quantity_OnHand  = P_QUANTITY_ONHAND,
        Quantity_Lend    = P_QUANTITY_LEND,
        Total_Qty        = P_TOTAL_QTY,
        Status_ID        = P_STATUS_ID,
        Modified_By      = P_MODIFIED_BY,
        Modified_On      = P_MODIFIED_ON
    WHERE Product_ID = P_PRODUCT_ID;
    COMMIT;
END MODIFICAR_FIDE_PRODUCT_TB_SP;
/

-- Procedimiento para modificar INVENTORY registros
CREATE OR REPLACE PROCEDURE MODIFICAR_FIDE_INVENTORY_TB_SP
(
    P_INVENTORY_ID       IN NUMBER,
    P_PRODUCT_ID         IN NUMBER,
    P_COMMENTS           IN VARCHAR2,
    P_QUANTITY_STOCK     IN NUMBER,
    P_QUANTITY_RESERVED  IN NUMBER,
    P_QUANTITY_THRESHOLD IN NUMBER,
    P_STATUS_ID          IN NUMBER,
    P_LAST_RESTOCK       IN TIMESTAMP,
    P_MODIFIED_BY        IN VARCHAR2,
    P_MODIFIED_ON        IN TIMESTAMP
)
IS
BEGIN
    UPDATE FIDE_SAMDESIGN.FIDE_INVENTORY_TB
    SET Product_ID         = P_PRODUCT_ID,
        Comments           = P_COMMENTS,
        Quantity_Stock     = P_QUANTITY_STOCK,
        Quantity_Reserved  = P_QUANTITY_RESERVED,
        Quantity_Threshold = P_QUANTITY_THRESHOLD,
        Status_ID          = P_STATUS_ID,
        Last_Restock       = P_LAST_RESTOCK,
        Modified_By        = P_MODIFIED_BY,
        Modified_On        = P_MODIFIED_ON
    WHERE Inventory_ID = P_INVENTORY_ID;
    COMMIT;
END MODIFICAR_FIDE_INVENTORY_TB_SP;
/