from openai import OpenAI
import json
import datetime
import calendar
from OCR import *

def init_client(apikey ) : 
  client = OpenAI(api_key=apikey)
  return client

def wolfsberg_questions(client, contenu_pdf, jsonquestion) :
  try :
    response = client.chat.completions.create(
    model="gpt-3.5-turbo-1106",
    messages=[
      {"role": "user", "content": """Your task is to answer the questions contained in the json attached in this message, using the document delimited by <doc> tags, a Wolfsberg questionnaire. Always use the response format specified in the json to answer.You must use only this document and nothing you've learned before. It's quite possible that you won't find the answer to some questions, in which case answer "N/A". Only answer questions when the answer is cleary mentionned in the text, don't try to deduce answers. Only answer a question if you have a clear source in the document, otherwise return "N/A". Answer in JSON Format and don't put new keys in the json, you have to use only the given one."""},
      {"role": "user", "content": "<doc>" + contenu_pdf + "<doc>"},
      {"role": "user", "content":  jsonquestion}, ]
    )
    answer = response.choices[0].message.content
    jsonreturn = json.loads(answer[answer.find("{"):answer.find("}") + 1])
    return jsonreturn
  except Exception as e :
    print(e)
    return {}

def ICI_questions(client, contenu_pdf, jsonquestion) :
  try :
    response = client.chat.completions.create(
    model="gpt-3.5-turbo-1106",
    messages=[
      {"role": "user", "content": """Your task is to answer the questions contained in the json attached in this message, using the document delimited by <doc> tags, a ICI questionnaire. Always use the response format specified in the json to answer.You must use only this document and nothing you've learned before. It's quite possible that you won't find the answer to some questions, in which case answer "N/A" for the questions you couldn't find the answer for. Only answer questions when the answer is cleary mentionned in the text, don't try to deduce answers. Only answer a question if you have a clear source in the document. Always Answer in JSON Format, with all the questions."""},
      {"role": "user", "content": "<doc>" + contenu_pdf + "<doc>"},
      {"role": "user", "content":  jsonquestion}, ]
    )
    answer = response.choices[0].message.content
    jsonreturn = json.loads(answer[answer.find("{"):answer.find("}") + 1])
    return jsonreturn
  except Exception as e :
    print(e)
    return {}
  
def get_country_region(client, country) :
  try : 
    response = client.chat.completions.create(
    model="gpt-3.5-turbo-1106",
    messages=[
      {"role": "user", "content": """Your task is to tell me which category the country I'm about to give you belongs to. Just return the name of the category, nothing else. Here are the categories: "Other Countries", "West Europe", "USA" and the country is : """ + country},
    ]
    )
    return response.choices[0].message.content
  except Exception as e :
    print(e)
    return "N/A"
  
def is_6month__old(client, date) :
  try : 
    date_du_jour = datetime.date.today()
    nom_mois = calendar.month_name[date_du_jour.month]
    curdate = f"{nom_mois} {date_du_jour.day}, {date_du_jour.year}"
    response = client.chat.completions.create(
    model="gpt-3.5-turbo-1106",
    messages=[
      {"role": "user", "content": """Your task is to tell me if a company's creation date is less than 6 months before the current date. To do this, I'll give you today's date. Answer only with "YES" or "NO". Please note that the formats may differ. The current date is: """ + curdate + """ and the company creation date is : """ + date},
    ]
    )
    return response.choices[0].message.content
  except Exception as e:
    print(e)
    return "N/A"

def get_country_translation(client, country) :
   response = client.chat.completions.create(
  model="gpt-3.5-turbo-1106",
  messages=[
    {"role": "user", "content": """Your task is to translate the name of the country I'm about to send you into English. I don't know the language of the country in advance, so it's up to you to find out. Just send back the translated country name, nothing else. The country is : """ + country
    },
  ]
  )
   return response.choices[0].message.content


def get_SIREN(client, contenu_pdf) :
   response = client.chat.completions.create(
  model="gpt-3.5-turbo-1106",
  messages=[
    {"role": "user", "content": """Your task is to extract the SIREN number from this invoice. I just want that number back and nothing else. Base yourself solely on the document delimited by <doc> and return the SIRET number if you can find it or "N/A" if you haven't managed to find it. Please only return numbers <doc> """ + contenu_pdf + "<doc>"
    },
  ]
  )
   return response.choices[0].message.content