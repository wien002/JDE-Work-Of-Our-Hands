/* Create tables */

CREATE TABLE tbl_userType
(
    userTypeID INT PRIMARY KEY,
    userTypeName VARCHAR(50) NOT NULL
);

CREATE TABLE tbl_user
(
	userID INT PRIMARY KEY AUTO_INCREMENT,
    userName VARCHAR(40) NOT NULL,
    password VARCHAR(50) NOT NULL,
	firstName VARCHAR(40) NOT NULL,
    lastName VARCHAR(40) NOT NULL,
    middleName VARCHAR(40),
    birthday DATE NOT NULL,
    gender CHAR(1) NOT NULL,
    email VARCHAR(40) NOT NULL,
    phoneNumber VARCHAR(11) NOT NULL,
    userTypeID INT,
    createdBy VARCHAR(50),
    updatedBy VARCHAR(50),
    FOREIGN KEY (userTypeID)
    REFERENCES tbl_userType (userTypeID)
);

CREATE TABLE tbl_chatLogs
(
    chatID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    message TEXT NOT NULL,
    timeSent DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID)
    REFERENCES tbl_user (userID)
);

CREATE TABLE tbl_product
(
    productID INT PRIMARY KEY AUTO_INCREMENT,
    productName VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    category VARCHAR(40) NOT NULL,
    size VARCHAR(20) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stocks INT NOT NULL,
    userID INT NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    updatedBy VARCHAR(50),
    FOREIGN KEY (userID)
    REFERENCES tbl_user (userID)
);

CREATE TABLE tbl_customizeSize
(
    customizeID INT PRIMARY KEY AUTO_INCREMENT,
    productID INT,
    clothingType VARCHAR(40) NOT NULL,
    neck FLOAT,
    shoulder FLOAT,
    armhole FLOAT,
    bicep FLOAT,
    wrist FLOAT,
    sleeveLength FLOAT,
    chest FLOAT,
    waist FLOAT,
    hips FLOAT,
    shirtLength FLOAT,
    crotch FLOAT,
    thigh FLOAT,
    knee FLOAT,
    legOpening FLOAT,
    pantsLength FLOAT,
    FOREIGN KEY (productID)
    REFERENCES tbl_product (productID)
);

CREATE TABLE tbl_appointment
(
    appointmentID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    appointmentDate DATE NOT NULL,
    service VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending',
    updatedBy VARCHAR(50),
    FOREIGN KEY (userID)
    REFERENCES tbl_user (userID)
);

CREATE TABLE tbl_cart
(
    cartID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    productID INT,
    customizeID INT,
    quantity INT NOT NULL DEFAULT '1',
    price DECIMAL (10,2) NOT NULL,
    FOREIGN KEY (userID)
    REFERENCES tbl_user (userID),
    FOREIGN KEY (productID)
    REFERENCES tbl_product (productID),
    FOREIGN KEY (customizeID)
    REFERENCES tbl_customizeSize (customizeID)
);

CREATE TABLE tbl_order
(
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    cartID INT NOT NULL,
    totalPrice DECIMAL (10,2) NOT NULL,
    orderStatus VARCHAR(20) DEFAULT 'Unpaid',
    updatedBy VARCHAR(50),
    FOREIGN KEY (cartID)
    REFERENCES tbl_cart (cartID)
);

CREATE TABLE tbl_payment
(
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT NOT NULL,
    userID INT NOT NULL,
    modeOfPayment VARCHAR(50) NOT NULL,
    dateCreated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (orderID)
	REFERENCES tbl_order (orderID),
    FOREIGN KEY (userID)
    REFERENCES tbl_user (userID)
);

/* Drop */

Drop Table tbl_payment;
Drop Table tbl_order;
Drop Table tbl_cart;
Drop Table tbl_appointment;
Drop Table tbl_customizeSize;
Drop Table tbl_product;
Drop Table tbl_chatLogs;
Drop Table tbl_user;
Drop Table tbl_userType;

/* Insert */

INSERT INTO  tbl_userType
    VALUES (1, 'admin'),
    (2, 'employee'),
    (3, 'customer');