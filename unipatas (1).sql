-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/06/2026 às 01:28
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `unipatas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `animais`
--

CREATE TABLE `animais` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `categoria` enum('cao','gato') NOT NULL,
  `genero` enum('macho','femea') NOT NULL,
  `idade` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `doacao_concluida` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `animais`
--

INSERT INTO `animais` (`id`, `nome`, `foto`, `categoria`, `genero`, `idade`, `peso`, `descricao`, `id_usuario`, `doacao_concluida`) VALUES
(1, 'Sol', 'imagens/1777251328_69eeb400e04c9.png', 'gato', 'femea', 5, 6.00, 'Sol Brilhante', 2, 1),
(2, 'Jilo', 'imagens/1777763890_69f686324b2c5.jpg', 'gato', 'macho', 3, 4.00, 'Jilo Ã© um gatinho amado mas com um passado difÃ­cil, foi abandonado quando era bebÃª e resgatado das ruas sÃ³ agora. ', 2, 0),
(3, 'Pituca', 'imagens/1777764317_69f687dd9c18e.jpg', 'cao', 'femea', 5, 15.00, 'Graciosa e charmosa.', 4, 1),
(4, 'Luna', 'imagens/1777768425_69f697e974195.jpg', 'gato', 'femea', 3, 5.00, 'Gatinha fofa e coleira, adora um carinho!', 1, 0),
(5, 'Pipa', 'imagens/1777768368_69f697b03388d.jpg', 'gato', 'femea', 1, 4.00, 'Uma gatinha que ama subir em tudo que Ã© lugar. Muito feliz e energÃ©tica.', 1, 0),
(6, 'LuÃ­sa', 'imagens/1777765373_69f68bfd0e17e.png', 'cao', 'femea', 5, 16.00, 'LuÃ­sa Ã© uma novinha com alma de idosa, adora descansar. ', 3, 1),
(7, 'Leo', 'imagens/1780265382_6a1cb1a63e344.jpg', 'cao', 'macho', 1, 7.00, 'CÃ£o dÃ³cil e amoroso.', 8, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clinicas`
--

CREATE TABLE `clinicas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `doacoes`
--

CREATE TABLE `doacoes` (
  `id` int(11) NOT NULL,
  `id_animal` int(11) DEFAULT NULL,
  `id_doador` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `doacoes`
--

INSERT INTO `doacoes` (`id`, `id_animal`, `id_doador`, `valor`) VALUES
(1, 1, 3, 150.00),
(2, 3, 5, 150.00),
(3, 6, 8, 150.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `endereco` varchar(200) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `cpf`, `endereco`, `telefone`, `email`, `senha`) VALUES
(1, 'Maria Eduarda Lucinda Pereira', '11738345912', '190 Wilson Martins', '43999546634', 'meduardalucinda1@gmail.com', '$2y$10$N/aZzeQaHa0Dr4Ii14RIjuYfNSMXT4sFEk27wg6ZkeQVs.qudWvQC'),
(2, 'JoÃ£o Mateus Lucinda', '12345678910', 'Wilson Martins 544', '43998546314', 'joao@gmail.com', '$2y$10$iNJEPG6yt66PBGF1aeEfK.9flAJPRoRSir2kDIngaZQZOh2I9SuMy'),
(3, 'NicÃ©ia Cristina Lucinda', '95781935991', 'Wilson Martins 190', '43996671848', 'niceia@gmail.com', '$2y$10$h1r94eL33XCQjm6JUfB1iOE3j7AcIk3e3GETubgBngyH6oFHejaTS'),
(4, 'Arildo Aparecido', '95715517915', '190 Wilson Martins', '43999022861', 'arildo@gmail.com', '$2y$10$LfYYcHZSX5T3lInK1rwgPe8.kC/eClewADJf5IacDiN9RZU93UWRy'),
(5, 'Teste DoaÃ§Ã£o', '78945612310', 'Testando 123', '43999999999', 'teste@gmail.com', '$2y$10$w/b1WPJLxmVlMnU8/Gd9ruYpRDdLj8NCpEF9X/xt2xoTa.d5pXBdu'),
(8, 'Teste', '1111111111', 'Testando 1', '2222222222', 'testando@gmail.com', '$2y$10$IKn/W4i/DM2ToSVIxi1NMOuZSZHj9u.QMLyRPCxd4ugFOrtfVXfri');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `animais`
--
ALTER TABLE `animais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `clinicas`
--
ALTER TABLE `clinicas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `doacoes`
--
ALTER TABLE `doacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_animal` (`id_animal`),
  ADD KEY `id_doador` (`id_doador`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animais`
--
ALTER TABLE `animais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `clinicas`
--
ALTER TABLE `clinicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `doacoes`
--
ALTER TABLE `doacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `animais`
--
ALTER TABLE `animais`
  ADD CONSTRAINT `animais_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `doacoes`
--
ALTER TABLE `doacoes`
  ADD CONSTRAINT `doacoes_ibfk_1` FOREIGN KEY (`id_animal`) REFERENCES `animais` (`id`),
  ADD CONSTRAINT `doacoes_ibfk_2` FOREIGN KEY (`id_doador`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
