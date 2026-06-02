# Free Deployment Guide: Steam Price Tracker Portal

This guide explains how to host your project for free using **Aiven** (Database), **Render** (Python API), and **InfinityFree** (PHP Frontend).

## Prerequisites
- A **GitHub** account.
- A **Hugging Face** account (for the Llama 3.1 model).

---

## Step 1: Managed MySQL Database (Aiven.io)
1. Go to [Aiven.io](https://aiven.io/) and create a free account.
2. Create a new **MySQL** service. Choose the **Free Plan**.
3. Once the service is "Running", note down:
   - **Host** (e.g., `mysql-xxx.aivencloud.com`)
   - **Port** (usually `24587`)
   - **User** (usually `avnadmin`)
   - **Password**
   - **Database Name** (usually `defaultdb`)

---

## Step 2: Python RAG API (Render.com)
1. Push your code to a **GitHub repository**.
2. Go to [Render.com](https://render.com/) and sign in with GitHub.
3. Click **New +** > **Web Service**.
4. Select your repository.
5. **Configuration:**
   - **Name:** `steam-rag-api`
   - **Runtime:** `Python 3`
   - **Build Command:** `pip install -r rag/requirements.txt`
   - **Start Command:** `cd rag && python chatbot_api.py` (Note: You might need to adjust the script to use `0.0.0.0` and the `$PORT` environment variable).
6. **Environment Variables:**
   - `HF_TOKEN`: Your Hugging Face API Token.
   - `DB_HOST`: Your Aiven Host.
   - `DB_PORT`: Your Aiven Port (e.g., `24587`).
   - `DB_USER`: `avnadmin`
   - `DB_PASS`: Your Aiven Password.
   - `DB_NAME`: `defaultdb`
7. Click **Deploy**. Note the URL (e.g., `https://steam-rag-api.onrender.com`).

---

## Step 3: PHP Frontend (InfinityFree)
1. Sign up at [InfinityFree.com](https://infinityfree.com/).
2. Create a new hosting account.
3. Use the **File Manager** or **FTP** to upload all project files (except the `rag/` folder) to the `htdocs/` directory.
4. **Configuration:**
   Since InfinityFree doesn't support system environment variables easily, edit `includes/db.php` manually with your Aiven credentials:
   ```php
   $host = 'mysql-xxx.aivencloud.com';
   $port = '24587';
   $user = 'avnadmin';
   $pass = 'your_password';
   $db   = 'defaultdb';
   ```
5. Edit `chatbot.php` (or use `.htaccess`) to set the `RAG_API_URL` to your Render URL:
   ```php
   $rag_api_url = 'https://steam-rag-api.onrender.com/chat';
   ```

---

## Step 4: Final Setup
1. Visit your InfinityFree URL (e.g., `http://your-site.infinityfreeapp.com/import.php`).
2. Click **Start Import** to populate the database.
3. Visit `/ml_engine.php` to run the clustering logic.
4. Your AI Chat should now be functional!
