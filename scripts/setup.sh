#!/bin/bash

# Voltar para a raiz do projeto
cd "$(dirname "$0")/.."

echo "🚀 Iniciando a configuração do projeto..."

# 📝 Copiar .env.example para .env no backend
echo "📄 Verificando .env no backend..."
cd backend
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Arquivo .env do backend criado a partir de .env.example."
else
    echo "ℹ️  Arquivo .env do backend já existe, não foi sobrescrito."
fi

# 📝 Verificar .env.test no backend (para testes)
if [ ! -f .env.test ]; then
    cp .env.example .env.test
    echo "✅ Arquivo .env.test do backend criado a partir de .env.example."
else
    echo "ℹ️  Arquivo .env.test do backend já existe, não foi sobrescrito."
fi
cd ..

# 📝 Copiar .env.example para .env no frontend
echo "📄 Verificando .env no frontend..."
cd frontend
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Arquivo .env do frontend criado a partir de .env.example."
else
    echo "ℹ️  Arquivo .env do frontend já existe, não foi sobrescrito."
fi
cd ..

# Subir containers com Docker Compose
echo "🐳 Subindo containers com Docker Compose..."
docker-compose up -d --build

# Instalar dependências no container backend
echo "📦 Instalando dependências do backend com Composer dentro do container..."
docker exec -it gestor-academico-backend composer install --no-interaction --prefer-dist

echo ""
echo "🎉 Projeto configurado com sucesso!"
echo ""
echo "👉 Frontend via proxy reverso: http://localhost"
echo "👉 Frontend sem proxy reverso: http://localhost:5173"
echo ""
echo "👉 Backend via proxy reverso: http://localhost/api"
echo "👉 Backend sem proxy reverso: http://localhost:8000/api"
echo ""
echo "👉 Swagger: http://localhost/api/docs"
echo ""
echo "👉 Coverage Report: http://localhost/api/report"
echo ""
echo "👉 PHPMyAdmin: http://localhost:8081"
echo ""