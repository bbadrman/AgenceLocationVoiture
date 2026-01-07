#!/bin/bash

cd ~/Server/Aigenrate/AgenceLocation/frontend

echo "🧹 Nettoyage..."
npm cache clean --force
rm -rf node_modules
rm -f package-lock.json

echo "📦 Installation des dépendances..."
npm install

echo "🎨 Installation de Tailwind CSS..."
npm install -D tailwindcss postcss autoprefixer

echo "⚙️ Création des fichiers de configuration..."

# tailwind.config.js
cat > tailwind.config.js << 'EOF'
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554',
        },
      },
    },
  },
  plugins: [],
}
EOF

# postcss.config.js
cat > postcss.config.js << 'EOF'
export default {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF

# src/index.css
cat > src/index.css << 'EOF'
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  body {
    @apply bg-gray-50 text-gray-900;
  }
}

@layer components {
  .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed;
  }
  
  .btn-secondary {
    @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200;
  }
  
  .input-field {
    @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none;
  }
  
  .card {
    @apply bg-white rounded-lg shadow-md overflow-hidden;
  }
}
EOF

echo "✅ Configuration terminée !"
echo ""
echo "📋 Résumé:"
ls -lh tailwind.config.js postcss.config.js src/index.css

echo ""
echo "🚀 Vous pouvez maintenant lancer: npm run dev"
