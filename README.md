# Docu2Risk - Automated Financial Inherent Risk Analysis Application

## Project Context

It is important to note that **Docu2Risk is a university project** carried out by a group of five people as part of our studies in Computer Science at the IUT of Metz.

The application was developed as a team, and tasks were distributed among the members.

My personal contribution was mainly focused on the **technical core of the analysis (Python Backend)**:

*   **Analysis Algorithm**: Design and development of the Python algorithm managing information extraction.
*   **AI Integration**: Implementation of the **ChatGPT** API (OpenAI) for extracting textual data from documents.
*   **Web Scraping Development**: Creation of scripts (`requests`, `BeautifulSoup`) to collect external data (country risk, blacklists, etc.).
*   **OCR Implementation**: Integration of **Tesseract** to extract text from scanned documents (non-native PDFs).
*   **Backend API Creation**: Development of the **Flask API** serving as a bridge between the PHP frontend and the Python analysis engine.
*   **Preprocessing**: Design of the preprocessing algorithm for Wolfsberg questionnaires, to target only the relevant pages.
*   **Front-End Participation**: Contribution to the development of views in HTML and CSS.

---

Docu2Risk is a web application designed to help companies in the financial sector **automate the inherent risk analysis** of a counterparty. The application aims to transform a traditionally manual, tedious, and time-consuming task into a fast, AI-assisted process.

Users can upload standard financial documents (such as `Wolfsberg` questionnaires, `ICI` or `Invoices`) to obtain a complete analysis report in a few steps, including a final risk score.

## Features

*   **Multi-format Document Analysis**: Support for key financial documents (Wolfsberg, ICI) for information extraction.
*   **AI Extraction**: Use of AI to read documents and automatically answer a predefined analysis questionnaire.
*   **Enrichment by Web Scraping**: Automatic collection of external data (political risk of the country, blacklists of financial authorities, data leaks) to refine the analysis.
*   **Manual Validation**: Interface allowing the user to check, correct, or complete the answers provided by the AI before generating the score.
*   **Detailed Reports**: Generation of a final score (Low / Medium / High) based on a weighted scale, with the possibility of seeing the detail of the points.
*   **Analysis History**: Conservation of a history of all analyzes carried out for monitoring over time.
*   **Data Export**: Possibility to export the results of the analysis in `.csv` format.
*   **Role Management**: The application manages three levels of users (Standard, Project Manager, Administrator) with distinct access rights.

## Technical Explanations

### Architecture

The application is based on a dissociated client-server architecture:

1.  **Frontend / Web Portal (PHP)**: Developed in **PHP** according to an **MVC (Model-View-Controller)** structure. It manages the user interface, authentication, account management, and presentation of results.
2.  **Backend / Analysis Engine (Python)**: A separate service developed with the **Flask** framework. It exposes several *endpoints* that receive the documents sent by PHP, perform all the heavy processing (OCR, AI, Scraping) and return the results in JSON format.

### Database

We use **MariaDB** as a database management system.

### Detection Algorithms

To detect information that signals a risk, Docu2Risk combines several techniques:

*   **OCR (Tesseract)**: The `ocrmypdf` library (based on Tesseract) is used to extract raw text from scanned PDF files, thus making them readable by the AI.
*   **AI (ChatGPT API)**: The ChatGPT API is used to probe the extracted text. Specific *prompts* are sent with the content of the document for the AI to identify and return the answers to the analysis questions.
*   **Web Scraping (BeautifulSoup)**: For external data, Python scripts target specific websites (e.g., TradingEconomics, Pappers, financial authorities) to extract relevant information (country risk, SIRET, etc.).

### Frontend

The user interface is built in **HTML**, **CSS**, and **JavaScript/TypeScript**.

### 3. Important Note (Current Status)

**The project is not currently functional as is.**

The **MariaDB** database was hosted on the **PhpMyAdmin** instance provided by the IUT of Metz. At the end of the project, this environment was reset and the database could not be recovered.

To make the project functional, it would be necessary to:
1.  Recreate the MariaDB database schema.
2.  Adapt the PHP configuration files (DAO) to point to the new database.
