CREATE SCHEMA IF NOT EXISTS file_manager;

SET SEARCH_PATH TO file_manager, public;

CREATE TABLE IF NOT EXISTS users (
  id                serial     NOT NULL,
  login             VARCHAR(20)   NOT NULL,
  password_hash     VARCHAR(60)   NOT NULL, 
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tokens (
  id                serial          NOT NULL,
  user_id           serial          NOT NULL,
  token             VARCHAR(1024)   NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users (id)
);
