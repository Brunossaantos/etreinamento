# 📘 Sistema de Gestão de Treinamentos

---

## 📌 Sobre o Projeto

O **Sistema de Gestão de Treinamentos** é uma aplicação corporativa desenvolvida em PHP para **gerenciamento completo de treinamentos internos**, incluindo controle de colaboradores, empresas, departamentos, instrutores e registros de presença.

O sistema centraliza todo o ciclo de treinamento — desde o cadastro de estruturas organizacionais até a validação de presença em treinamentos — garantindo **organização, rastreabilidade e controle operacional**.

---

## 🎯 Objetivos

* Gerenciar treinamentos corporativos de forma estruturada
* Controlar presença de colaboradores e visitantes
* Organizar empresas, departamentos e instrutores
* Garantir rastreabilidade dos registros de participação
* Padronizar o fluxo de cadastro e manutenção de dados
* Evitar duplicidade e inconsistências de presença

---

## ⚙️ Funcionalidades Principais

* Cadastro e gestão de colaboradores
* Controle de empresas e departamentos
* Cadastro e manutenção de instrutores
* Criação e gerenciamento de treinamentos
* Registro de presença de colaboradores
* Registro de presença de visitantes (crachás inválidos)
* Validação de presença duplicada
* Controle de status de entidades (ativo/inativo)
* Pesquisa e filtros em todos os módulos
* Geração de listas de presença
* Controle de usuários do sistema
* Upload e tratamento de fotos e logotipos

---

## 🏗️ Arquitetura

O sistema segue uma arquitetura baseada em **camadas com padrão DAO (Data Access Object)**, promovendo separação clara de responsabilidades.

* **DAO (Data Access Object)** → Camada de acesso ao banco de dados
* **Models** → Representação das entidades do sistema
* **Util** → Funções auxiliares (datas, imagens, caminhos)
* **Config** → Configuração de ambiente (.env)
* **Database** → Conexões com MySQL

---

## 📁 Estrutura de Diretórios

```
/config        # Configuração de ambiente (.env)
/database      # Conexão com banco de dados
/dao           # Camada de acesso a dados (DAO)
/model         # Entidades do sistema (Models)
/util          # Funções auxiliares (datas, imagens, etc.)
/vendor        # Dependências do Composer
```

---

## 🧰 Tecnologias Utilizadas

* PHP 8.x
* MySQL / MariaDB
* Composer
* Dotenv
* Programação Orientada a Objetos (POO)
* Padrão DAO

---

## 🧩 Módulos do Sistema

### 👤 Usuários

* Cadastro e autenticação
* Controle de status
* Alteração de senha

### 👷 Colaboradores

* Cadastro completo
* Integração com empresa e departamento
* Controle de crachá e matrícula
* Suporte a dados legados e sistema gestor

### 🏢 Empresas

* Cadastro e manutenção
* Controle de status
* Geração de logotipos

### 🏬 Departamentos

* Organização estrutural da empresa
* Relacionamento com colaboradores

### 👨‍🏫 Instrutores

* Cadastro e gerenciamento
* Associação com departamentos

### 📚 Treinamentos

* Criação de treinamentos
* Definição de carga horária, conteúdo e local
* Associação com instrutor e departamento
* Controle de status

### 🧾 Presença

* Registro de presença de colaboradores
* Registro de visitantes (crachás inválidos)
* Validação de duplicidade
* Contagem de participantes

---

## 🔄 Fluxo de Treinamento

1. Cadastro de empresa, departamento e instrutor
2. Cadastro de colaboradores
3. Criação do treinamento
4. Liberação de lista de presença
5. Registro de presença dos participantes
6. Validação de duplicidade
7. Registro de visitantes inválidos (quando aplicável)
8. Geração de relatórios e consultas

---

## 🧠 Padrão DAO

O sistema utiliza fortemente o padrão DAO para isolamento da lógica de banco de dados.

Exemplos de DAOs:

* DaoUsuario
* DaoColaborador
* DaoEmpresa
* DaoDepartamento
* DaoInstrutor
* DaoTreinamento
* DaoPresenca

### Responsabilidades:

* Executar queries SQL
* Retornar objetos de Models
* Garantir separação entre regra de negócio e persistência

---

## 🧾 Models (Entidades)

As entidades representam os dados do sistema:

* Usuario
* Colaborador
* Empresa
* Departamento
* Instrutor
* Treinamento
* Presenca
* PresencaVisitante

### Características:

* Encapsulamento com getters e setters
* Suporte a dados legados (ID e texto)
* Métodos `__toString()` para debug
* Estrutura orientada a objetos

---

## 🛠️ Classe Util

Responsável por funções auxiliares do sistema:

* Formatação de datas
* Separação de data e hora
* Geração de caminhos de fotos de colaboradores
* Geração de logotipos de empresas
* Fallback automático de imagens

---

## 🔐 Configuração de Ambiente

O sistema utiliza `.env` para gerenciamento de variáveis sensíveis:

```
DB_HOST=
DB_USER=
DB_PASS=
DB_NAME=

DB2_HOST=
DB2_USER=
DB2_PASS=
DB2_NAME=
```

---

## ⚠️ Regras do Sistema

* Presença não pode ser duplicada por colaborador
* Visitantes são registrados separadamente
* Treinamentos possuem controle de status
* Dados podem existir em formato legado ou gestor
* Toda consulta é feita via DAO
* Conexões são centralizadas

---

## 🔒 Segurança

* Uso de prepared statements em todas as queries
* Credenciais protegidas via `.env`
* Separação entre camadas do sistema
* Controle de consistência de dados

---

## 📊 Status do Projeto

* Sistema corporativo interno
* Em desenvolvimento ativo
* Estrutura estável e modular
* Pronto para expansão de módulos

---

## 👤 Autor

**Bruno Carvalho |**
**| Danilo Franco**

Desenvolvimento do sistema de gestão de treinamentos com foco em:

* Organização de dados corporativos
* Controle de presença e treinamentos
* Arquitetura limpa em PHP
* Boas práticas de engenharia de software

---

> Sistema desenvolvido para fins corporativos, com foco em controle operacional, rastreabilidade e padronização de treinamentos internos.