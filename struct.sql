DROP TABLE IF EXISTS `order_item`;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS `product_type`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    user_id INT NOT NULL AUTO_INCREMENT,
    user_chat_id INT NOT NULL,
    user_name VARCHAR(255),
    user_phone VARCHAR(255),
    user_address VARCHAR(255),
    PRIMARY KEY (user_id),
    UNIQUE (user_chat_id)
);

CREATE TABLE `product` (
    product_id INT NOT NULL AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    PRIMARY KEY (product_id)
);

CREATE TABLE `product_type` (
    product_type_id INT NOT NULL AUTO_INCREMENT,
    product_id INT NOT NULL,
    product_type_name VARCHAR(255),
    product_type_price INT UNSIGNED NOT NULL,
    PRIMARY KEY (product_type_id),
    FOREIGN KEY (product_id) REFERENCES `product`(product_id)
);

CREATE TABLE `order` (
    order_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_amount INT NOT NULL,
    order_comment VARCHAR(255),
    order_status INT NOT NULL,
    PRIMARY KEY (order_id),
    FOREIGN KEY (user_id) REFERENCES `user`(user_id)
);

CREATE TABLE `order_item` (
    order_item_id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_type_id INT NOT NULL,
    order_item_quantity INT NOT NULL,
    PRIMARY KEY (order_item_id),
    FOREIGN KEY (order_id) REFERENCES `order`(order_id),
    FOREIGN KEY (product_type_id) REFERENCES product_type(product_type_id)
);