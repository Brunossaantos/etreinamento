-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/09/2023 às 11:59
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `etreinamento`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `colaboradores`
--

CREATE TABLE `colaboradores` (
  `ID_COLABORADOR` int(11) NOT NULL,
  `NOME` text NOT NULL,
  `EMPRESA` int(11) NOT NULL,
  `CARGO` text NOT NULL,
  `HEXADECIMAL` text NOT NULL,
  `MATRICULA` text NOT NULL,
  `DEPARTAMENTO` int(11) NOT NULL,
  `STATUS_COLABORADOR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `colaboradores`
--

INSERT INTO `colaboradores` (`ID_COLABORADOR`, `NOME`, `EMPRESA`, `CARGO`, `HEXADECIMAL`, `MATRICULA`, `DEPARTAMENTO`, `STATUS_COLABORADOR`) VALUES
(1, 'DANILO DE SOUSA FRANCO', 1, '', '4031721291', '354', 1, 1),
(2, 'GABRIEL  GONZAGA DE OLIVEIRA', 1, '', '2926388032', '343', 1, 1),
(3, 'KAIO LIMA DOS ANJOS', 1, '', '4031137035', '342', 1, 1),
(4, 'LEONARDO N. DOS SANTOS', 1, '', '2835603562', '218', 2, 1),
(5, 'NICOLAS NUNES SEVERINO', 1, '', '2927368576', '326', 3, 1),
(6, 'PAULO S. MINGA CAVALCANTE', 1, '', '2835778730', '183', 4, 1),
(7, 'MARIANNA MILLER SANTIAGO', 1, '', '0509133488', '214', 5, 1),
(8, 'DORIVAL JOSE GUILHERME', 1, '', '2835732298', '48', 4, 1),
(9, 'DOUGLAS PEREIRA DE OLIVEIRA', 1, '', '4031808955', '151', 3, 1),
(10, 'EDVALDO DA CRUZ ANDRADE', 1, '', '2033645414', '330', 4, 1),
(11, 'CRISTIANO F. DE SOUZA', 1, '', '2927648432', '296', 4, 1),
(12, 'DONIZETH W. DE BRITO CATTO', 1, '', '2041204928', '170', 6, 1),
(13, 'WESLEY DA GAMA ALVES', 1, '', '2835696842', '152', 4, 1),
(14, 'WILLIAM DA SILVA PEREIRA', 1, '', '0996485482', '198', 4, 1),
(15, 'RODRIGO DOS SANTOS RANGEL', 1, '', '3603797915', '353', 2, 1),
(16, 'VANESSA VESPASIANI', 2, '', '0983521770', '5085', 7, 1),
(17, 'VINICIUS ELIAS DA SILVA', 1, '', '4031092459', '352', 3, 1),
(18, 'PRISCILA VIEIRA BRAZ', 1, '', '2039997840', '10', 8, 1),
(19, 'RENATA TAQUETO P. DA SILVA', 1, '', '3021235611', '349', 9, 1),
(20, 'MARCELO G. DIAS RODRIGUES LIMA', 1, '', '2838785450', '90', 10, 1),
(21, 'DOUGLAS BARROS BARBOSA', 1, '', '2835741018', '133', 3, 1),
(22, 'WESLEY RONYEL B. DA SILVA', 1, '', '0985509866', '278', 4, 1),
(23, 'JOILSON CALISTO MARTINS', 1, '', '0998495978', '132', 2, 1),
(24, 'JOAO FRANCISCO DE LUNA', 1, '', '2835657450', '23', 11, 1),
(25, 'JOSE DONIZETE DE F. HOMEM', 1, '', '4034837243', '345', 4, 1),
(26, 'JOSE NETO DE SOUZA', 1, '', '0985085722', '272', 3, 1),
(27, 'JULIA MARTINS DE OLIVEIRA', 1, '', '0507539536', '258', 5, 1),
(28, 'GUSTAVO DE FREITAS R. BIZERRA', 1, '', '3606384651', '355', 3, 1),
(29, 'GIOVANI ALVES DE ANDRADE', 1, '', '2835514186', '182', 3, 1),
(30, 'JADSON FRANCA DOS SANTOS', 1, '', '3690351742', '146', 4, 1),
(31, 'JEFFERSON LUIZ MOREIRA', 1, '', '2079723546', '77', 11, 1),
(32, 'JOAO BATISTA STAMPINI', 1, '', '0421974178', '316', 4, 1),
(33, 'ROBERTO CARLOS ILLES', 3, '', '0984837434', '5049', 12, 1),
(34, 'SAMUEL REGIS N. BARBOSA', 1, '', '0985176442', '224', 3, 1),
(35, 'VINICIUS CAETANO ZANCANARO', 1, '', '0575804102', '289', 4, 1),
(36, 'RAFAEL SANTOS MARÇON', 1, '', '4031297211', '358', 4, 1),
(37, 'RICARDO FREITAS DE SOUZA', 1, '', '0998463978', '213', 4, 1),
(38, 'LUCAS NOGUEIRA', 1, '', '2657394830', '337', 3, 1),
(39, 'LORENA MIKAELLE LIMA SANTOS', 1, '', '3021337051', '348', 9, 1),
(40, 'JANAINA H. DE SOUZA RODRIGUES', 1, '', '3603918507', '338', 13, 1),
(41, 'JESSICA A. CARDOSO SAEZ', 1, '', '0998025338', '222', 9, 1),
(42, 'GUILHERME HARUO MATUNAGA', 1, '', '2835494138', '175', 3, 1),
(43, 'JOSE CUSTODIO DOS SANTOS', 1, '', '2080141546', '69', 11, 1),
(44, 'JACKSON LUIS DOS SANTOS LIMA', 1, '', '2835463226', '201', 3, 1),
(45, 'ADEMILDO DE OLIVEIRA SANTOS', 1, '', '2657249470', '247', 3, 1),
(46, 'ALESSANDRO ARRAIS MARTINS', 1, '', '0421266418', '318', 2, 1),
(47, 'CESAR JUNIOR MURCA', 1, '', '0996187402', '239', 2, 1),
(48, 'ANTONIO DA CUNHA PEREIRA', 1, '', '2147407082', '7', 6, 1),
(49, 'ADILSON LUCIO DE ALMEIDA', 1, '', '2838777002', '113', 4, 1),
(50, 'WILSON JOSE DA SILVA', 1, '', '0422457362', '317', 2, 1),
(51, 'MATHEUS SANTANA ARUDA', 1, '', '2040802864', '223', 3, 1),
(52, 'MARCIO MENESES DE ANDRADE', 1, '', '4031788219', '347', 4, 1),
(53, 'PRISCILA DE OLIVEIRA RIBEIRO', 1, '', '4030313979', '360', 8, 1),
(54, 'ODOALDO SILVA ROCHA', 1, '', '0983686282', '344', 4, 1),
(55, 'MIGUEL A. CRISTALDO FILHO', 1, '', '4031113451', '56', 10, 1),
(56, 'LUCAS PEREIRA RODRIGUES DIAS', 1, '', '4036358363', '350', 8, 1),
(57, 'VINYCIUS DE BRITO DOS SANTOS', 1, '', '0997199338', '335', 2, 1),
(58, 'WELITON M. DO NASCIMENTO', 1, '', '4034880283', '357', 2, 1),
(59, 'JOSE FELIPE SILVA DOS SANTOS', 1, '', '4030667803', '274', 2, 1),
(60, 'JUAREZ GOMES DO N. OLIVEIRA', 1, '', '0421067010', '322', 4, 1),
(61, 'JOHNNY WILLIAM P. GRACIANO', 1, '', '2926803328', '290', 4, 1),
(62, 'JONATHAN RICARTE DINIZ', 1, '', '2079910522', '186', 2, 1),
(63, 'FABRICIO SANTOS RIBEIRO', 1, '', '2657265230', '266', 6, 1),
(64, 'FERNANDO MATOS ANDRADE', 1, '', '0996835530', '231', 2, 1),
(65, 'ELITON GREGORIO DA SILVA', 1, '', '2925846240', '167', 2, 1),
(66, 'EVANDRO DE LIMA BONFIM', 1, '', '2835677978', '212', 2, 1),
(67, 'ALEX JOSE DA SILVA', 1, '', '2925596944', '299', 8, 1),
(68, 'BERTRAND THIMOT', 1, '', '2656699006', '260', 8, 1),
(69, 'ANDRE LUIZ G. ROCHA', 1, '', '2656632718', '261', 2, 1),
(70, 'ANDRE SOARES DA SILVA', 1, '', '3448076506', '194', 2, 1),
(71, 'CLAUDIO LEANDRO NASCIMBEN', 1, '', '0985195930', '282', 4, 1),
(72, 'EDILSON TAKEMI MIYAZATO', 1, '', '3425472170', '109', 2, 1),
(73, 'ELIANE MATA ISIDRO DE LIMA', 1, '', '2835474330', '62', 3, 1),
(74, 'ERNANDA GARCIA', 4, '', '2656054734', '5088', 14, 1),
(75, 'ELINALDO DOS SANTOS', 1, '', '2657159406', '230', 4, 1),
(76, 'FATIMA DOS SANTOS SILVA', 1, '', '2070739402', '209', 3, 1),
(77, 'MANOEL MARCOS S. DOS SANTOS', 1, '', '2041256080', '307', 2, 1),
(78, 'KLEBER MOREIRA DE ALMEIDA', 1, '', '4031830523', '346', 4, 1),
(79, 'LEANDRO REIS DOS SANTOS', 1, '', '4031715547', '238', 11, 1),
(80, 'JESSIKA W. SIMOES RODRIGUES', 5, '', '0997840090', '11', 5, 1),
(81, 'FABIANA C. G. BARDELA', 5, '', '2926580816', '6', 15, 1),
(82, 'JOELSON DOS SANTOS SILVA', 5, '', '2147469050', '193', 16, 1),
(83, 'GABRIELLE DA SILVA L. MOURATO', 5, '', '4027930971', '203', 16, 1),
(84, 'OSCAR VINICIUS A. DA SILVA', 5, '', '2926337488', '108', 16, 1),
(85, 'PAULO HENRIQUE MOREIRA', 5, '', '2922737744', '126', 16, 1),
(86, 'MAX DE OLIVEIRA SANTANA', 5, '', '0984820106', '139', 17, 1),
(87, 'CICERO GON?ALVES PEREIRA', 5, '', '4031092491', '204', 11, 1),
(88, 'FLAVIO BORGES DE CARVALHO', 1, '', '0996074538', '5043', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `departamentos`
--

CREATE TABLE `departamentos` (
  `ID_DEPARTAMENTO` int(11) NOT NULL,
  `DEPARTAMENTO` text NOT NULL,
  `STATUS_DEPARTAMENTO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `departamentos`
--

INSERT INTO `departamentos` (`ID_DEPARTAMENTO`, `DEPARTAMENTO`, `STATUS_DEPARTAMENTO`) VALUES
(1, 'TI', 1),
(2, 'CONFERENTE', 1),
(3, 'ATENDIMENTO AO CLIENTE', 1),
(4, 'OPERADOR DE EMPILHADEIRA', 1),
(5, 'FINANCEIRO', 1),
(6, 'LIDER DE OPERAÇÃO', 1),
(7, 'COLORNET', 1),
(8, 'FACILITIES', 1),
(9, 'RECURSOS HUMANOS', 1),
(10, 'COORD DE OPERAÇÕES', 1),
(11, 'AJUDANTE', 1),
(12, 'ILLES ELETRICA', 1),
(13, 'QUALIDADE', 1),
(14, 'TRILHOS', 1),
(15, 'GERENCIA', 1),
(16, 'TRANSPORTE', 1),
(17, 'MOTORISTA', 1),
(18, 'ENCARREGADO', 1),
(19, 'TODOS', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `ID_EMPRESA` int(11) NOT NULL,
  `EMPRESA` text NOT NULL,
  `STATUS_EMPRESA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`ID_EMPRESA`, `EMPRESA`, `STATUS_EMPRESA`) VALUES
(1, 'UDLOG', 1),
(2, 'COLORNET', 1),
(3, 'ILLES ELETRICA', 1),
(4, 'TRILHOS', 1),
(5, 'GUIBÓR', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `instrutores`
--

CREATE TABLE `instrutores` (
  `ID_INSTRUTORES` int(11) NOT NULL,
  `NOME` text NOT NULL,
  `DEPARTAMENTO` text NOT NULL,
  `STATUS_INSTRUTOR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `instrutores`
--

INSERT INTO `instrutores` (`ID_INSTRUTORES`, `NOME`, `DEPARTAMENTO`, `STATUS_INSTRUTOR`) VALUES
(1, 'ERNANDA GARCIA', '13', 1),
(2, 'FABRíCIO QUEIROS', '13', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_presenca`
--

CREATE TABLE `lista_presenca` (
  `ID_TREINAMENTO` int(11) NOT NULL,
  `ID_COLABORADOR` int(11) NOT NULL,
  `HORARIO_PRESENCA` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lista_presenca`
--

INSERT INTO `lista_presenca` (`ID_TREINAMENTO`, `ID_COLABORADOR`, `HORARIO_PRESENCA`) VALUES
(1, 1, '28-09-2023 17:02:27'),
(1, 2, '28-09-2023 17:02:29'),
(2, 1, '28-09-2023 17:14:44'),
(2, 2, '28-09-2023 17:14:47'),
(2, 27, '28-09-2023 17:24:34'),
(2, 88, '28-09-2023 17:59:31'),
(2, 80, '28-09-2023 18:01:28'),
(3, 3, '29-09-2023 07:52:09'),
(3, 63, '29-09-2023 07:53:10'),
(3, 8, '29-09-2023 07:53:12'),
(3, 30, '29-09-2023 07:53:15'),
(3, 45, '29-09-2023 07:53:41'),
(3, 23, '29-09-2023 07:53:44'),
(3, 39, '29-09-2023 07:54:13'),
(3, 49, '29-09-2023 07:54:18'),
(3, 1, '29-09-2023 07:58:00'),
(3, 64, '29-09-2023 08:05:29'),
(3, 58, '29-09-2023 08:05:40'),
(3, 82, '29-09-2023 08:05:43'),
(3, 43, '29-09-2023 08:05:48'),
(3, 78, '29-09-2023 08:05:50'),
(3, 67, '29-09-2023 08:05:58'),
(3, 66, '29-09-2023 08:06:01'),
(3, 35, '29-09-2023 08:06:03'),
(3, 56, '29-09-2023 08:06:05'),
(3, 28, '29-09-2023 08:06:07'),
(3, 87, '29-09-2023 08:06:12'),
(3, 53, '29-09-2023 08:06:13'),
(3, 21, '29-09-2023 08:06:16'),
(3, 46, '29-09-2023 08:06:20'),
(3, 83, '29-09-2023 08:06:26'),
(3, 27, '29-09-2023 08:06:32'),
(3, 29, '29-09-2023 08:06:35'),
(3, 34, '29-09-2023 08:06:37'),
(3, 9, '29-09-2023 08:06:39'),
(3, 76, '29-09-2023 08:06:40'),
(3, 17, '29-09-2023 08:06:42'),
(3, 38, '29-09-2023 08:06:44'),
(3, 2, '29-09-2023 08:06:46'),
(3, 42, '29-09-2023 08:06:47'),
(3, 25, '29-09-2023 08:06:50'),
(3, 62, '29-09-2023 08:06:54'),
(3, 44, '29-09-2023 08:07:01'),
(3, 69, '29-09-2023 08:07:03'),
(3, 68, '29-09-2023 08:07:05'),
(3, 31, '29-09-2023 08:07:08'),
(3, 40, '29-09-2023 08:07:20'),
(3, 74, '29-09-2023 08:07:24'),
(3, 24, '29-09-2023 08:07:33'),
(3, 36, '29-09-2023 08:07:38'),
(3, 70, '29-09-2023 08:07:44'),
(3, 15, '29-09-2023 08:07:48'),
(3, 52, '29-09-2023 08:07:55'),
(3, 61, '29-09-2023 08:07:59'),
(3, 22, '29-09-2023 08:08:20'),
(3, 47, '29-09-2023 08:08:23'),
(3, 71, '29-09-2023 08:08:25'),
(3, 10, '29-09-2023 08:09:30'),
(3, 48, '29-09-2023 08:11:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinamento`
--

CREATE TABLE `treinamento` (
  `ID_TREINAMENTO` int(11) NOT NULL,
  `DESCRICAO_TREINAMENTO` text NOT NULL,
  `DATA_TREINAMENTO` text NOT NULL,
  `INSTRUTOR` int(11) NOT NULL,
  `DEPARTAMENTO` int(11) NOT NULL,
  `CONTEUDO` text NOT NULL,
  `CARGAHORARIA` text NOT NULL,
  `STATUS_TREINAMENTO` int(11) NOT NULL,
  `LOCAL_TREINAMENTO` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treinamento`
--

INSERT INTO `treinamento` (`ID_TREINAMENTO`, `DESCRICAO_TREINAMENTO`, `DATA_TREINAMENTO`, `INSTRUTOR`, `DEPARTAMENTO`, `CONTEUDO`, `CARGAHORARIA`, `STATUS_TREINAMENTO`, `LOCAL_TREINAMENTO`) VALUES
(2, 'TESTE CADASTO TREINAMENTO', '2023-09-28', 1, 1, 'TESTE', '00:45', 1, 'UD LOG - MAUA'),
(3, 'DDS - VIDA SAUDAVEL', '2023-09-29', 2, 19, ' ', '00:15', 1, 'ADM ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_USUARIO` int(11) NOT NULL,
  `LOGIN` varchar(50) NOT NULL,
  `SENHA` varchar(50) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `STATUS_USUARIO` int(11) NOT NULL,
  `SENHA_HASH` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`ID_USUARIO`, `LOGIN`, `SENHA`, `NOME`, `EMAIL`, `STATUS_USUARIO`, `SENHA_HASH`) VALUES
(1, 'ADMIN', 'ADMIN', 'teste atualizar', 'update@update.com', 5, '$2y$10$n0hI57d4NDiK8bOLYmI90urFXOjgMrtOcO1U9Ej3CjA44Jh.q5h/6'),
(2, 'DANILO', '123456', 'Danilo Franco', 'engdanilofranco@gmail.com', 1, '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`ID_COLABORADOR`),
  ADD KEY `EMPRESA` (`EMPRESA`),
  ADD KEY `DEPARTAMENTO` (`DEPARTAMENTO`);

--
-- Índices de tabela `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`ID_DEPARTAMENTO`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`ID_EMPRESA`);

--
-- Índices de tabela `instrutores`
--
ALTER TABLE `instrutores`
  ADD PRIMARY KEY (`ID_INSTRUTORES`);

--
-- Índices de tabela `lista_presenca`
--
ALTER TABLE `lista_presenca`
  ADD KEY `ID_TREINAMENTO` (`ID_TREINAMENTO`),
  ADD KEY `ID_COLABORADOR` (`ID_COLABORADOR`);

--
-- Índices de tabela `treinamento`
--
ALTER TABLE `treinamento`
  ADD PRIMARY KEY (`ID_TREINAMENTO`),
  ADD KEY `INSTRUTOR` (`INSTRUTOR`),
  ADD KEY `DEPARTAMENTO` (`DEPARTAMENTO`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_USUARIO`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `ID_COLABORADOR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `ID_DEPARTAMENTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `ID_EMPRESA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `instrutores`
--
ALTER TABLE `instrutores`
  MODIFY `ID_INSTRUTORES` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `treinamento`
--
ALTER TABLE `treinamento`
  MODIFY `ID_TREINAMENTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
