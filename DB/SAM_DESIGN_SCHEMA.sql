
CREATE USER FIDE_SAMDESIGN IDENTIFIED BY SAMDESIGN;
GRANT CONNECT TO FIDE_SAMDESIGN;
GRANT RESOURCE TO FIDE_SAMDESIGN;
GRANT DBA TO FIDE_SAMDESIGN;

--1
CREATE TABLE FIDE_SAMDESIGN.FIDE_STATUS_TB (
    Status_ID   NUMBER CONSTRAINT FIDE_PK_STATUS_ID PRIMARY KEY,
    Description VARCHAR2(255),
    Created_By  VARCHAR2(50),
    Created_On  TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

-- 2
CREATE TABLE FIDE_SAMDESIGN.FIDE_ROL_TB (
    rol_id       NUMBER CONSTRAINT FIDE_PK_ROL_ID PRIMARY KEY,
    rol_name     VARCHAR2(100),
    Status_ID    NUMBER,
    Created_By   VARCHAR2(100),
    Created_On   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Modified_By  VARCHAR2(100),
    Modified_On  TIMESTAMP,
    FOREIGN KEY (Status_ID)
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 3
CREATE TABLE FIDE_SAMDESIGN.FIDE_USERS_TB (
    User_ID  NUMBER CONSTRAINT FIDE_PK_USER_ID PRIMARY KEY,
    User_Name   VARCHAR2(100),
    User_Email  VARCHAR2(100) CONSTRAINT FIDE_USER_EMAIL_NN NOT NULL,
    Password    VARCHAR2(100),
    Rol_ID     NUMBER,
    Status_ID   NUMBER,
    Created_By  VARCHAR2(50),
    Created_On  TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (Status_ID)
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (Rol_ID)
        REFERENCES FIDE_SAMDESIGN.FIDE_ROL_TB(Rol_ID)
);

-- 4
CREATE TABLE FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB (
    Category_ID  NUMBER CONSTRAINT FIDE_PK_CATEGORY_ID PRIMARY KEY,
    Description  VARCHAR2(255),
    Comments     VARCHAR2(255),
    Status_ID    NUMBER,
    Created_By   VARCHAR2(50),
    Created_On   TIMESTAMP,
    Modified_By  VARCHAR2(50),
    Modified_On  TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 5
CREATE TABLE FIDE_SAMDESIGN.FIDE_VENDOR_TB (
    Vendor_ID          NUMBER CONSTRAINT FIDE_PK_VENDOR_ID PRIMARY KEY,
    Vendor_Name        VARCHAR2(100),
    Vendor_Address_ID  NUMBER,
    Vendor_eMail       VARCHAR2(100) CONSTRAINT FIDE_VENDOR_EMAIL_NN NOT NULL,
    Vendor_Balance     FLOAT,
    Created_By         VARCHAR2(50),
    Created_On         TIMESTAMP,
    Modified_By        VARCHAR2(50),
    Modified_On        TIMESTAMP,
    Status_ID          NUMBER,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 6
CREATE TABLE FIDE_SAMDESIGN.FIDE_EMPLOYEE_TB (
    Employee_ID        NUMBER CONSTRAINT FIDE_PK_EMPLOYEE_ID PRIMARY KEY,
    Employee_Name      VARCHAR2(100),
    Employee_LastName1 VARCHAR2(100),
    Employee_LastName2 VARCHAR2(100),
    Employee_eMail     VARCHAR2(100) CONSTRAINT FIDE_EMPLOYEE_EMAIL_NN NOT NULL,
    Employee_Position  VARCHAR2(100),
    Employee_Salary    FLOAT,
    Created_By         VARCHAR2(50),
    Created_On         TIMESTAMP,
    Modified_By        VARCHAR2(50),
    Modified_On        TIMESTAMP,
    Status_ID          NUMBER,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 7
CREATE TABLE FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB (
    Payment_Method_ID   NUMBER CONSTRAINT FIDE_PK_PAYMENT_METHOD_ID PRIMARY KEY,
    Payment_Method_Name VARCHAR2(255),
    Description         VARCHAR2(255),
    Status_ID           NUMBER,
    Created_By          VARCHAR2(50),
    Created_On          TIMESTAMP,
    Modified_By         VARCHAR2(50),
    Modified_On         TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 8
CREATE TABLE FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB (
    City_ID     NUMBER CONSTRAINT FIDE_PK_CITY_ID PRIMARY KEY,
    Name        VARCHAR2(255),
    Status_ID   NUMBER,
    Created_By  VARCHAR2(50),
    Created_On  TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 9
CREATE TABLE FIDE_SAMDESIGN.FIDE_COUNTRIES_TB (
    Country_ID  NUMBER CONSTRAINT FIDE_PK_COUNTRY_ID PRIMARY KEY,
    Name        VARCHAR2(255),
    Status_ID   NUMBER,
    Created_By  VARCHAR2(50),
    Created_On  TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 10
CREATE TABLE FIDE_SAMDESIGN.FIDE_STATE_ADDRESS_TB (
    State_ID    NUMBER CONSTRAINT FIDE_PK_STATE_ID PRIMARY KEY,
    Name        VARCHAR2(255),
    Status_ID   NUMBER,
    Created_By  VARCHAR2(50),
    Created_On  TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 11
CREATE TABLE FIDE_SAMDESIGN.FIDE_CUSTOMER_TB (
    Customer_ID           NUMBER CONSTRAINT FIDE_PK_CUSTOMER_ID PRIMARY KEY,
    Customer_Name         VARCHAR2(100),
    Customer_Email        VARCHAR2(100) CONSTRAINT FIDE_CUSTOMER_EMAIL_NN NOT NULL,
    Customer_Phone_number VARCHAR2(50),
    Status_ID             NUMBER,
    Created_By            VARCHAR2(50),
    Created_On            TIMESTAMP,
    Modified_On           TIMESTAMP,
    Modified_By           VARCHAR2(50),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 15
CREATE TABLE FIDE_SAMDESIGN.FIDE_ADDRESS_TB (
    Address_ID   NUMBER CONSTRAINT FIDE_PK_ADDRESS_ID PRIMARY KEY,
    Address      VARCHAR2(250),
    ID_City      NUMBER,
    ZIP_Code     NUMBER,
    ID_Country   NUMBER,
    Status_ID    NUMBER,
    Created_By   VARCHAR2(100),
    Created_On   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Modified_By  VARCHAR2(100),
    Modified_On  TIMESTAMP,
    ID_Customer  NUMBER,
    FOREIGN KEY (ID_City) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CITY_ADDRESS_TB(City_ID),
    FOREIGN KEY (ID_Country) 
        REFERENCES FIDE_SAMDESIGN.FIDE_COUNTRIES_TB(Country_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (ID_Customer) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CUSTOMER_TB(Customer_ID)
);

-- 12
CREATE TABLE FIDE_SAMDESIGN.FIDE_PRODUCT_TB (
    Product_ID       NUMBER CONSTRAINT FIDE_PK_PRODUCT_ID PRIMARY KEY,
    Description      VARCHAR2(255),
    Category_Type_ID NUMBER,
    Comments         VARCHAR2(255),
    Unit_price       NUMBER,
    Quantity_OnHand  NUMBER,
    Quantity_Lend    NUMBER,
    Total_Qty        NUMBER,
    Status_ID        NUMBER,
    Created_By       VARCHAR2(50),
    Created_On       DATE,
    Modified_By      VARCHAR2(50),
    Modified_On      DATE,
    FOREIGN KEY (Category_Type_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CATEGORY_TYPE_TB(Category_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 13
CREATE TABLE FIDE_SAMDESIGN.FIDE_INVENTORY_TB (
    Inventory_ID       NUMBER CONSTRAINT FIDE_PK_INVENTORY_ID PRIMARY KEY,
    Product_ID         NUMBER,
    Comments           VARCHAR2(255),
    Quantity_Stock     NUMBER,
    Quantity_Reserved  NUMBER,
    Quantity_Threshold NUMBER,
    Status_ID          NUMBER,
    Last_Restock       TIMESTAMP,
    Created_By         VARCHAR2(50),
    Created_On         TIMESTAMP,
    Modified_By        VARCHAR2(50),
    Modified_On        TIMESTAMP,
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (Product_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PRODUCT_TB(Product_ID)
);

-- 14
CREATE TABLE FIDE_SAMDESIGN.FIDE_INVENTORY_LINES_TB (
    Inventory_Lines_ID  NUMBER CONSTRAINT FIDE_PK_INVENTORY_LINES_ID PRIMARY KEY,
    Inventory_ID        NUMBER,
    Product_ID          NUMBER,
    Comments            VARCHAR2(500),
    Quantity_Stocked    NUMBER,
    Quantity_Reserved   NUMBER,
    Quantity_Threshold  NUMBER,
    Status_ID           NUMBER,
    Last_Resort         TIMESTAMP,
    Created_By          VARCHAR2(100),
    Created_On          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Modified_By         VARCHAR2(100),
    Modified_On         TIMESTAMP,
    FOREIGN KEY (Inventory_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_INVENTORY_TB(Inventory_ID),
    FOREIGN KEY (Product_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PRODUCT_TB(Product_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 16
CREATE TABLE FIDE_SAMDESIGN.FIDE_ORDER_TB (
    Order_ID          NUMBER CONSTRAINT FIDE_PK_ORDER_ID PRIMARY KEY,
    Customer_ID       NUMBER,
    Order_Date        TIMESTAMP,
    Order_Amount      NUMBER,
    Order_Tax         NUMBER,
    Comments          VARCHAR2(255),
    Dispatch          NUMBER(1) DEFAULT 0,  -- Replacing BOOLEAN with NUMBER(1)
    Fullfield         NUMBER(1) DEFAULT 0,  -- Replacing BOOLEAN with NUMBER(1)
    Status_ID         NUMBER,
    Payment_Method_ID NUMBER,
    Created_On        TIMESTAMP,
    Created_By        VARCHAR2(50),
    Modified_On       TIMESTAMP,
    Modified_By       VARCHAR2(50),
    FOREIGN KEY (Customer_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CUSTOMER_TB(Customer_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (Payment_Method_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB(Payment_Method_ID)
);

-- 17
CREATE TABLE FIDE_SAMDESIGN.FIDE_ORDER_LINES_TB (
    Order_Line_ID NUMBER CONSTRAINT FIDE_PK_ORDER_LINE_ID PRIMARY KEY,
    Order_ID      NUMBER,
    Product_ID    NUMBER,
    Qty_Item      NUMBER,
    Comments      VARCHAR2(255),
    Status_ID     NUMBER,
    Total_Price   NUMBER,
    Created_On    TIMESTAMP,
    Created_By    VARCHAR2(50),
    Modified_On   TIMESTAMP,
    Modified_By   VARCHAR2(50),
    FOREIGN KEY (Order_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_ORDER_TB(Order_ID),
    FOREIGN KEY (Product_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PRODUCT_TB(Product_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 18
CREATE TABLE FIDE_SAMDESIGN.FIDE_BILLING_TB (
    Billing_ID         NUMBER CONSTRAINT FIDE_PK_BILLING_ID PRIMARY KEY,
    Order_ID           NUMBER,
    Customer_ID        NUMBER,
    Invoiced_Address_ID NUMBER,
    Billing_Date       TIMESTAMP,
    Total_Amount       NUMBER,
    Comments           VARCHAR2(255),
    Status_ID          NUMBER,
    Payment_Method_ID  NUMBER,
    Created_On         TIMESTAMP,
    Created_By         VARCHAR2(50),
    Modified_On        TIMESTAMP,
    Modified_By        VARCHAR2(50),
    FOREIGN KEY (Order_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_ORDER_TB(Order_ID),
    FOREIGN KEY (Customer_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CUSTOMER_TB(Customer_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (Payment_Method_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB(Payment_Method_ID)
);

-- 19
CREATE TABLE FIDE_SAMDESIGN.FIDE_CART_TB (
    Cart_ID           NUMBER CONSTRAINT FIDE_PK_CART_ID PRIMARY KEY,
    Customer_ID       NUMBER,
    Address_ID        NUMBER,
    Order_Date        TIMESTAMP,
    Comments          VARCHAR2(255),
    Status_ID         NUMBER,
    Payment_Method_ID NUMBER,
    Created_On        TIMESTAMP,
    Created_By        VARCHAR2(50),
    Modified_On       TIMESTAMP,
    Modified_By       VARCHAR2(50),
    FOREIGN KEY (Customer_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CUSTOMER_TB(Customer_ID),
    FOREIGN KEY (Address_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_ADDRESS_TB(Address_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID),
    FOREIGN KEY (Payment_Method_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PAYMENT_METHOD_TB(Payment_Method_ID)
);

-- 20
CREATE TABLE FIDE_SAMDESIGN.FIDE_CART_LINES_TB (
    Cart_Line_ID NUMBER CONSTRAINT FIDE_PK_CART_LINE_ID PRIMARY KEY,
    Cart_ID      NUMBER,
    Product_ID   NUMBER,
    Qty_Item     NUMBER,
    Comments     VARCHAR2(255),
    Status_ID    NUMBER,
    Total_Price  NUMBER,
    Created_On   TIMESTAMP,
    Created_By   VARCHAR2(50),
    Modified_On  TIMESTAMP,
    Modified_By  VARCHAR2(50),
    FOREIGN KEY (Cart_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CART_TB(Cart_ID),
    FOREIGN KEY (Product_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_PRODUCT_TB(Product_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);

-- 21
CREATE TABLE FIDE_SAMDESIGN.FIDE_SPECIAL_ORDER_TB (
    Order_ID    NUMBER CONSTRAINT FIDE_PK_SPECIAL_ORDER_ID PRIMARY KEY,
    Customer_ID NUMBER,
    Order_Date  TIMESTAMP,
    Order_Qty   NUMBER,
    Comments    VARCHAR2(255),
    Status_ID   NUMBER,
    Created_On  TIMESTAMP,
    Created_By  VARCHAR2(50),
    Modified_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    FOREIGN KEY (Customer_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_CUSTOMER_TB(Customer_ID),
    FOREIGN KEY (Status_ID) 
        REFERENCES FIDE_SAMDESIGN.FIDE_STATUS_TB(Status_ID)
);