
-- book Shopping Cart (sc) MySQL script to create corresponding database

create database book_sc;

use book_sc;

create table customers(
	customerid int unsigned not null auto_increment primary key,
	name char(60) not null,
	address char(80) not null,
	city char(30) not null,
	state char(20),
	zip char(10),
	country char(20) not null
) ENGINE=InnoDB;

create table orders(
	orderid int unsigned not null auto_increment primary key,
	customerid int unsigned not null references customers(customerid),
	amount float(6,2),
	date date not null,
	order_status char(10),
	ship_name char(60) not null,
	ship_city char(30) not null,
	ship_state char(20),
	ship_zip char(10),
	ship_country char(20) not null
)ENGINE=InnoDB;

create table books(
	isbn char(13) not null primary key,
	author char(100),
	title char(100),
	catid int unsigned,        # category id
	price float(4,2) not null,
	description varchar(255)
) ENGINE=InnoDB;

create table categories(
	catid int unsigned not null auto_increment primary key,
	catname char(60) not null
) ENGINE=InnoDB;

create table order_items(
	orderid int unsigned not null references orders(orderid),
	isbn char(13) not null references books(isbn),
	item_price float(4,2) not null,
	quantity tinyint unsigned not null,
	primary key (orderid, isbn)
) ENGINE=InnoDB;

create table admin(
	username char(16) not null primary key,
	password char(40) not null
);

grant select, insert, update, delete
on book_sc.*
to book_sc@localhost identified by 'password';

