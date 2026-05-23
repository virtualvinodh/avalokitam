FROM node:20-alpine

WORKDIR /app

# Install deps first (layer cache)
COPY package.json package-lock.json* ./
RUN npm ci --omit=dev

COPY . .

# Render injects PORT; default to 3001 locally
ENV PORT=3001

EXPOSE 3001

CMD ["node", "server.js"]
