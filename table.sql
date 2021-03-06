CREATE TABLE products(
	ProductID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	ProductName TINYTEXT NOT NULL,
	Category TINYTEXT NOT NULL,
	Price FLOAT(10,2) NOT NULL,
	Comments TINYTEXT,
	Prescription BOOLEAN,
	Stock INT
);
CREATE TABLE sales(
	SaleID INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
	SaleDate DATE NOT NULL,
	NumberofProducts INT NOT NULL,
	PriceTotal INT NOT NULL,
	EmployeeID INT NOT NULL
);
CREATE TABLE productsalelinks(
	SaleID INT NOT NULL,
	ProductID INT NOT NULL,
	FOREIGN KEY (SaleID) references sales(SaleID),
	FOREIGN KEY (ProductID) references products(ProductID)
);