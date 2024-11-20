<?php
require_once "db_connect.php";

/*---- Cria tabela  ----*/
$query = "CREATE TABLE IF NOT EXISTS Usuarios (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Senha VARCHAR(60) NOT NULL
);


INSERT INTO Usuarios (Nome, Email, Senha) 
VALUES 
    ('Clarice', 'clariice@gmail.com', '1562'),
    ('Carmen', 'carmeen@outlook.com', 'loja125'),
    ('Carlos', 'carlos@gmail.com', 'senha123');


SELECT * FROM Usuarios;


CREATE TABLE IF NOT EXISTS Solicitacoes_Pedidos (
    Id_Solicitacao INT AUTO_INCREMENT PRIMARY KEY,
    Id_Usuario INT,
    Produto VARCHAR(100) NOT NULL,
    Quantidade_Produto INT NOT NULL,
    Tipo_caixa VARCHAR(100) NOT NULL,
    Tamanho_Caixa VARCHAR(100) NOT NULL,
    Quantidade_Caixa INT NOT NULL,
    Status_Separacao VARCHAR(100) NOT NULL,
    Cliente VARCHAR(100) NOT NULL,
    Prazo DATE NOT NULL,
    FOREIGN KEY (Id_Usuario) REFERENCES Usuarios(Id) ON DELETE CASCADE
);


ALTER TABLE Solicitacoes_Pedidos AUTO_INCREMENT = 100;


INSERT INTO Solicitacoes_Pedidos (Id_Usuario, Produto, Quantidade_Produto, Tipo_caixa, Tamanho_Caixa, Quantidade_Caixa, Status_Separacao, Cliente, Prazo)
VALUES 
    ((SELECT Id FROM Usuarios WHERE Nome = 'Clarice'), 'Vidros', 10, 'Papelão', 'G', 15, 'Em andamento', 'Transp. EUA', '2024-11-15'),
    ((SELECT Id FROM Usuarios WHERE Nome = 'Carmen'), 'Garrafa', 15, 'Papelão', 'Média', 20, 'Em andamento', 'Transportadora', '2024-12-12'),
    ((SELECT Id FROM Usuarios WHERE Nome = 'Carlos'), 'Caixa de Plástico', 5, 'Plástico', 'Pequena', 10, 'Entregue', 'Cliente XYZ', '2024-11-30');


SELECT * FROM Solicitacoes_Pedidos;


CREATE TABLE IF NOT EXISTS Estoque_Caixas (
    Tipo_Caixa VARCHAR(100) NOT NULL,
    Tamanho VARCHAR(100) NOT NULL,
    Quantidade INT NOT NULL,
    Prateleira CHAR(3) NOT NULL,
    Parte_Prateleira CHAR(4) NOT NULL
);

INSERT INTO Estoque_Caixas (Tipo_Caixa, Tamanho, Quantidade, Prateleira, Parte_Prateleira)
VALUES 
    ('Caixa de Papelão', 'Pequena', 3, 'PT1', 'A'), 
    ('Caixa de Plástico', 'Média', 4, 'PT2', 'B');


SELECT * FROM Estoque_Caixas;

CREATE TABLE IF NOT EXISTS RegistrosLogin (
  UsuarioId INT,
  Transportadora VARCHAR(100) NOT NULL,
  DataHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  IpAddress VARCHAR(45) NOT NULL,
  FOREIGN KEY (UsuarioId) REFERENCES Usuarios(Id)
);

INSERT INTO RegistrosLogin (UsuarioId, Transportadora, IpAddress)
VALUES 
    ((SELECT Id FROM Usuarios WHERE Nome = 'Clarice'), 'Transportadora Jofege', '192.168.0.1');


SELECT * FROM RegistrosLogin;


CREATE TABLE IF NOT EXISTS Resumo_Pedidos (
    Total_Pedidos_Abertos INT NOT NULL,
    Total_Pedidos_Concluidos INT NOT NULL,
    DataAtualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO Resumo_Pedidos (Total_Pedidos_Abertos, Total_Pedidos_Concluidos)
SELECT
    SUM(CASE WHEN Status_Separacao IN ('Em andamento') THEN 1 ELSE 0 END) AS Total_Abertos,
    SUM(CASE WHEN Status_Separacao = 'Entregue' THEN 1 ELSE 0 END) AS Total_Concluidos
FROM
    Solicitacoes_Pedidos
ON DUPLICATE KEY UPDATE
    Total_Pedidos_Abertos = VALUES(Total_Pedidos_Abertos),
    Total_Pedidos_Concluidos = VALUES(Total_Pedidos_Concluidos);


SELECT * FROM Resumo_Pedidos";

$pdo->exec($query);
echo "Tabelas criada com sucesso!";
$pdo = null;//encerra a conexão com banco de dados
?>