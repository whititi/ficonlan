CREATE DATABASE freaksparty;

CREATE USER mi_usuario;
SET PASSWORD FOR 'rgeo2'@'localhost' = 'some_pass';

GRANT ALL PRIVILEGES ON freaksparty.* TO 'mi_usuario'@'localhost';
FLUSH PRIVILEGES;

USE freaksparty;

CREATE TABLE events(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(600) NOT NULL DEFAULT "",
    image VARCHAR(255) DEFAULT NULL,
    date_start DATE,
    date_end DATE,
    edition INT NOT NULL,
    enable TINYINT(1) DEFAULT 0,
    date_reg DATE
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE roles(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE users(
    dni CHAR(9) PRIMARY KEY,
    nick varchar(100) NOT NULL UNIQUE,
    email VARCHAR(200) NOT NULL UNIQUE,
    pass CHAR(98) NOT NULL,
    pass_rec CHAR(76) DEFAULT NULL,
    name VARCHAR(20) NOT NULL,
    lastname_1 VARCHAR(30) NOT NULL,
    lastname_2 VARCHAR(30) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    date_birth DATE DEFAULT NULL,
    size VARCHAR(4) NOT NULL,
    date_reg TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    rol INT DEFAULT NULL REFERENCES roles(id)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE inscribed(
    event INT REFERENCES events(id),
    user CHAR(9) REFERENCES users(dni),
    date_reg TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    payment VARCHAR(50),
    PRIMARY KEY(event, user)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE categories(
    id INT AUTO_INCREMENT,
    event INT REFERENCES events(id),
    name VARCHAR(60) NOT NULL,
    PRIMARY KEY(id, event)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE news(
    id INT AUTO_INCREMENT,
    category INT REFERENCES categories(id),
    image VARCHAR(255) DEFAULT NULL,
    author CHAR(9) REFERENCES users(dni),
    title VARCHAR(150) NOT NULL,
    subtitle VARCHAR(300) NOT NULL,
    body VARCHAR(5000) NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id, category)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE types_activities(
    id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(50) NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE activities(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description VARCHAR(5000) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    date_start DATE DEFAULT NULL,
    date_end DATE DEFAULT NULL,
    rules VARCHAR(5000) NOT NULL,
    max_users INT,
    type INT DEFAULT NULL REFERENCES types_activities(id)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE players(
    user CHAR(9) REFERENCES users(dni),
    event INT REFERENCES events(id),
    date_reg TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    position INT DEFAULT NULL,
    PRIMARY KEY(user, event)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE teams(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE teams_users(
    user CHAR(9) REFERENCES users(dni),
    team INT REFERENCES teams(id),
    PRIMARY KEY(user, team)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE awards(
    id INT PRIMARY KEY AUTO_INCREMENT,
    points INT NOT NULL,
    position INT NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE winners(
    activity INT REFERENCES activities(id),
    award INT REFERENCES teams(id),
    position INT NOT NULL,
    PRIMARY KEY(activity, award)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE sponsors(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    date_start DATE NOT NULL,
    enable TINYINT DEFAULT 1
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE sponsorship(
    sponsor INT REFERENCES sponsors(id),
    event INT REFERENCES events(id),
    PRIMARY KEY(sponsor, event)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;