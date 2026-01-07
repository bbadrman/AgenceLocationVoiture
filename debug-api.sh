#!/bin/bash

echo "🔍 Debug de l'API"
echo ""

cd ~/Server/Aigenrate/AgenceLocation

echo "1️⃣ Vérification DDEV..."
ddev describe | grep "HTTP URL"

echo ""
echo "2️⃣ Vérification des routes API..."
php bin/console debug:router | grep api | head -10

echo ""
echo "3️⃣ Test de l'API /cities..."
curl -s https://agencelocation.ddev.site/api/cities | head -c 200
echo ""

echo ""
echo "4️⃣ Test CORS..."
curl -X OPTIONS \
  -H "Origin: http://localhost:3001" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -I https://agencelocation.ddev.site/api/cities 2>&1 | grep -i "access-control"

echo ""
echo "5️⃣ Vérification base de données..."
php bin/console dbal:run-sql "SELECT COUNT(*) as total FROM city"

echo ""
echo "6️⃣ Configuration frontend..."
echo "VITE_API_URL=$(cat frontend/.env | grep VITE_API_URL)"

echo ""
echo "✅ Debug terminé"
