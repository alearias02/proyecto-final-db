CREATE TABLE PDC_Users_TB (
    PK_User_ID NUMBER PRIMARY KEY,
    User_Name VARCHAR2(100),
    User_Email VARCHAR2(100),
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Status_TB (
    PK_Status_ID NUMBER PRIMARY KEY,
    Status_Name VARCHAR2(50),
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Category_TB (
    PK_Category_ID NUMBER PRIMARY KEY,
    Category_Name VARCHAR2(100),
    Description VARCHAR2(255),
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Payment_Method_TB (
    PK_Payment_Method_ID NUMBER PRIMARY KEY,
    Payment_Method_Name VARCHAR2(100),
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Address_TB (
    PK_Address_ID NUMBER PRIMARY KEY,
    Address VARCHAR2(255),
    City VARCHAR2(100),
    State VARCHAR2(100),
    Country VARCHAR2(100),
    Zip_Code VARCHAR2(20),
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Customer_TB (
    PK_Customer_ID NUMBER PRIMARY KEY,
    Customer_Name VARCHAR2(100),
    Customer_Email VARCHAR2(100),
    FK_Address_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Address_ID) REFERENCES PDC_Address_TB(PK_Address_ID)
);

CREATE TABLE PDC_Product_TB (
    PK_Product_ID NUMBER PRIMARY KEY,
    FK_Category_ID NUMBER,
    Description VARCHAR2(255),
    Unit_Price NUMBER,
    Quantity_Limit NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Category_ID) REFERENCES PDC_Category_TB(PK_Category_ID)
);

CREATE TABLE PDC_Order_TB (
    PK_Order_ID NUMBER PRIMARY KEY,
    FK_Customer_ID NUMBER,
    Order_Amount NUMBER,
    Order_Date TIMESTAMP,
    FK_Payment_Method_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Customer_ID) REFERENCES PDC_Customer_TB(PK_Customer_ID),
    FOREIGN KEY (FK_Payment_Method_ID) REFERENCES PDC_Payment_Method_TB(PK_Payment_Method_ID)
);

CREATE TABLE PDC_Cart_TB (
    PK_Cart_ID NUMBER PRIMARY KEY,
    FK_Customer_ID NUMBER,
    FK_Address_ID NUMBER,
    FK_Payment_Method_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Customer_ID) REFERENCES PDC_Customer_TB(PK_Customer_ID),
    FOREIGN KEY (FK_Address_ID) REFERENCES PDC_Address_TB(PK_Address_ID),
    FOREIGN KEY (FK_Payment_Method_ID) REFERENCES PDC_Payment_Method_TB(PK_Payment_Method_ID)
);

CREATE TABLE PDC_Cart_Items_TB (
    PK_Cart_Item_ID NUMBER PRIMARY KEY,
    FK_Cart_ID NUMBER,
    FK_Product_ID NUMBER,
    Quantity NUMBER,
    FK_Status_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Cart_ID) REFERENCES PDC_Cart_TB(PK_Cart_ID),
    FOREIGN KEY (FK_Product_ID) REFERENCES PDC_Product_TB(PK_Product_ID),
    FOREIGN KEY (FK_Status_ID) REFERENCES PDC_Status_TB(PK_Status_ID)
);

CREATE TABLE PDC_Billing_TB (
    PK_Billing_ID NUMBER PRIMARY KEY,
    FK_Order_ID NUMBER,
    FK_Billing_Address_ID NUMBER,
    Billing_Amount NUMBER,
    Billing_Date TIMESTAMP,
    FK_Payment_Method_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Order_ID) REFERENCES PDC_Order_TB(PK_Order_ID),
    FOREIGN KEY (FK_Billing_Address_ID) REFERENCES PDC_Address_TB(PK_Address_ID),
    FOREIGN KEY (FK_Payment_Method_ID) REFERENCES PDC_Payment_Method_TB(PK_Payment_Method_ID)
);

CREATE TABLE PDC_Vendor_TB (
    PK_Vendor_ID NUMBER PRIMARY KEY,
    Vendor_Name VARCHAR2(100),
    Vendor_Address_ID NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (Vendor_Address_ID) REFERENCES PDC_Address_TB(PK_Address_ID)
);

CREATE TABLE PDC_Employee_TB (
    PK_Employee_ID NUMBER PRIMARY KEY,
    Employee_FirstName VARCHAR2(100),
    Employee_LastName VARCHAR2(100),
    Employee_Position VARCHAR2(100),
    Employee_Salary NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP
);

CREATE TABLE PDC_Inventory_TB (
    PK_Inventory_ID NUMBER PRIMARY KEY,
    FK_Product_ID NUMBER,
    Quantity_Ordered NUMBER,
    Quantity_Left NUMBER,
    Created_By VARCHAR2(50),
    Created_On TIMESTAMP,
    Modified_By VARCHAR2(50),
    Modified_On TIMESTAMP,
    FOREIGN KEY (FK_Product_ID) REFERENCES PDC_Product_TB(PK_Product_ID)
);

BEGIN
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Users_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Status_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Category_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Payment_Method_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Address_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Customer_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Product_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Order_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Cart_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Cart_Items_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Billing_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Vendor_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Employee_TB CASCADE CONSTRAINTS';
    EXECUTE IMMEDIATE 'DROP TABLE PDC_Inventory_TB CASCADE CONSTRAINTS';
END;