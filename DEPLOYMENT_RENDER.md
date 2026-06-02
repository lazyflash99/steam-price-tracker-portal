# Standard Deployment Guide: Render.com

This guide explains how to deploy your Steam Price Tracker Portal on **Render**, an industry-standard Platform as a Service (PaaS).

## Prerequisites
1.  A **GitHub** account with your project pushed to a repository.
2.  A **Render.com** account.
3.  A **Hugging Face** API Token.

---

## Step 1: Create a Managed MySQL Database (The Industry Standard)
Render does not host MySQL natively (they prefer PostgreSQL). For a professional MySQL setup, the industry standard is to use a **Managed Database Provider**. 

I recommend **[Aiven.io](https://aiven.io)** because they offer a high-performance Free Tier and are standard in the DevOps community.

1.  **Sign up at Aiven.io.**
2.  **Create a New Service:** Select **MySQL**.
3.  **Plan:** Choose the **Free** plan.
4.  **Region:** Pick the same region as your Render services (e.g., `aws-us-east-1`) to minimize latency.
5.  **Get Credentials:** Once "Running", copy these fields for Step 2 and 3:
    *   **Host** (e.g., `mysql-123-abc.aivencloud.com`)
    *   **Port** (e.g., `24587`)
    *   **User** (`avnadmin`)
    *   **Password**
    *   **Database Name** (`defaultdb`)

---

## Step 2: Deploy the Python RAG API
1.  In Render, click **New +** > **Web Service**.
2.  Connect your GitHub repository.
3.  **Service Settings:**
    *   **Name:** `steam-tracker-api`
    *   **Runtime:** `Python 3`
    *   **Build Command:** `pip install -r rag/requirements.txt`
    *   **Start Command:** `python rag/chatbot_api.py`
4.  **Environment Variables (Critical):**
    *   `HF_TOKEN`: (Your Hugging Face Token)
    *   `DB_HOST`: (From your Database provider)
    *   `DB_PORT`: (Usually `3306` or `24587` for Aiven)
    *   `DB_USER`: (Database username)
    *   `DB_PASS`: (Database password)
    *   `DB_NAME`: (Database name)
5.  Click **Create Web Service**. Render will give you a URL like `https://steam-tracker-api.onrender.com`.

---

## Step 3: Deploy the PHP Web Portal
1.  Click **New +** > **Web Service**.
2.  Connect the **same** GitHub repository.
3.  **Service Settings:**
    *   **Name:** `steam-tracker-portal`
    *   **Runtime:** `PHP`
4.  **Environment Variables:**
    *   `DB_HOST`: (Same as above)
    *   `DB_PORT`: (Same as above)
    *   `DB_USER`: (Same as above)
    *   `DB_PASS`: (Same as above)
    *   `DB_NAME`: (Same as above)
    *   `RAG_API_URL`: `https://steam-tracker-api.onrender.com/chat` (Use the URL from Step 2)
5.  Click **Create Web Service**.

---

## Step 4: Finalize & Sync
1.  Once both services are "Live", visit your Portal URL.
2.  Go to `https://your-portal-url.com/import.php` to initialize the database schema and import data.
3.  Go to `https://your-portal-url.com/ml_engine.php` to run the clustering logic.

### Why this is "Standard":
- **Git-backed:** Every change you push to GitHub will automatically trigger a new deployment (CI/CD).
- **Environment Separation:** Sensitive data (passwords, API keys) are kept in Render's "Variables" section, not in your code.
- **Microservices:** Your frontend and AI backend are separated into two distinct services, allowing them to scale independently.
