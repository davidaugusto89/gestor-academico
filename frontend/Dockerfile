# Use a imagem oficial Node.js como base
FROM node:latest

# Defina o diretório de trabalho dentro do container
WORKDIR /app

# Copie os arquivos de dependência para o container
COPY package.json package-lock.json ./

# Antes de instalar as dependências, remova caches antigos (opcional)
RUN npm cache clean --force

# Instale as dependências do projeto
RUN npm install

# Copie os arquivos do projeto para o container
COPY . .

# Exponha a porta utilizada pelo Vite
EXPOSE 5173

# Comando para rodar o servidor de desenvolvimento
CMD ["npm", "run", "dev"]
