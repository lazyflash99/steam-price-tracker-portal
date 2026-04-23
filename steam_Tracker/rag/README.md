# Steam Tracker RAG Bot

This folder contains the Python Retrieval-Augmented Generation (RAG) bot for the Steam Price Tracker. The bot allows you to ask natural language questions about games, prices, and reviews, and it translates those questions into SQL to fetch the answers.

## Prerequisites

Before running the bot, ensure you have Python installed along with the required libraries.

You can install the necessary dependencies using `pip`:
```bash
pip install langchain-community langchain-huggingface langchain-core python-dotenv mysql-connector-python sqlalchemy
```

## Setup Instructions

### 1. Get a Hugging Face Token

The bot uses the `Llama-3.1-8B-Instruct` model hosted on Hugging Face to generate SQL and natural language answers.
1. Go to [Hugging Face](https://huggingface.co/) and create an account or log in.
2. Navigate to your **Settings** > **Access Tokens**.
3. Create a new token with "Read" permissions and copy it.

### 2. Create the `.env` File

In this `rag` directory (`steam_Tracker/rag/`), create a new file named `.env`.

Add your Hugging Face token to the `.env` file like this:
```env
HF_TOKEN=your_hugging_face_token_here
```

**Optional Database Variables:**
If your MySQL database has a different configuration than the default XAMPP setup (root user, no password, `steam_tracker` DB), you can also add these variables to your `.env` file:
```env
DB_USER=root
DB_PASSWORD=your_password
DB_HOST=localhost
DB_NAME=steam_tracker
```

*(Note: Ensure that `.env` is added to your project's `.gitignore` so your private tokens are not uploaded to GitHub!)*

## Running the Bot

Once your `.env` file is set up and your MySQL server is running (e.g., via XAMPP), you can run the bot from the command line:

```bash
cd path/to/steam_Tracker/rag
python rag_bot.py
```

The script will launch an interactive loop in your terminal where you can ask questions like:
- *"What is the lowest price for ARK Survival Ascended?"*
- *"Compare Hollow Knight and Terraria. Which one should I buy?"*

Type `quit` or `exit` to stop the bot.
