CREATE TABLE IF NOT EXISTS users (
  id                INT           AUTO_INCREMENT PRIMARY KEY,
  login             VARCHAR(20)   NOT NULL,
  password          VARCHAR(60)   NOT NULL
);

CREATE TABLE IF NOT EXISTS tokens (
  id                INT             AUTO_INCREMENT PRIMARY KEY,
  user_id           INT             NOT NULL,
  token             VARCHAR(1024)   NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
